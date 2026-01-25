import sys
import json
import os
import csv
import time
import traceback
from datetime import datetime


from db import get_connection


def respond(obj):
    print(json.dumps(obj, default=str))
    sys.stdout.flush()

def safe_float(x):
    try:
        s = str(x).strip().replace(",", "")
        return float(s) if s else 0.0
    except Exception:
        return 0.0


def read_csv(csv_path: str):
    with open(csv_path, "r", newline="", encoding="utf-8-sig") as f:
        return [row for row in csv.reader(f) if row]


def main():
    start_time_clock = datetime.now().strftime("%H:%M:%S")
    start_perf = time.perf_counter()

    try:
        # ---------- Read JSON from STDIN ----------
        raw = sys.stdin.read().strip()
        if not raw:
            print(json.dumps({"success": False, "reason": "Empty JSON input"}))
            return

        params = json.loads(raw)

        csv_path = params.get("csv_path")
        from_date = params.get("periodfromdate")
        to_date = params.get("periodtodate")
        payschm = int(params.get("att_payschm", 0))
        company_id = int(params.get("company_id", 2))
        created_by = int(params.get("created_by", 26577))

        created_date = datetime.now().strftime("%Y-%m-%d")

        if not csv_path or not os.path.exists(csv_path):
            print(json.dumps({"success": False, "reason": "CSV not found", "csv_path": csv_path}))
            return
        if not from_date or not to_date:
            print(json.dumps({"success": False, "reason": "Missing periodfromdate/periodtodate"}))
            return
        if payschm == 0:
            print(json.dumps({"success": False, "reason": "att_payschm is 0"}))
            return

        # ---------- Read CSV ----------
        sheet = read_csv(csv_path)
        if len(sheet) <= 1:
            print(json.dumps({"success": False, "reason": "CSV has no data rows"}))
            return

        # EBNO list from first column (EMPID)
        ebnos = []
        for r in sheet[1:]:  # data starts from row 2
            if r and len(r) > 0 and str(r[0]).strip():
                ebnos.append(str(r[0]).strip())

        if not ebnos:
            print(json.dumps({"success": False, "reason": "No EMPID found in CSV"}))
            return

        # ---------- DB ----------
        cn = get_connection()
        cn.autocommit = False
        cur = cn.cursor(dictionary=True)

        inserted = 0
        updated = 0
        skipped_no_emp = 0

        totalEmployees = 0
        totalComponentRecords = 0
        totalSuccessRecords = 0
        totalFailedRecords = 0
        failedEbnos = []
        wmCache = {}  # EMPID -> eb_id cache

        try:
            placeholders = ",".join(["%s"] * len(ebnos))

            # ===== Pay scheme validation =====
            sql_distinct = f"""
                SELECT DISTINCT tpep.PAY_SCHEME_ID
                FROM tbl_pay_employee_payscheme tpep
                left JOIN tbl_hrms_ed_personal_details thepd on tpep.EMPLOYEEID=thepd.eb_id and thepd.is_active=1 and thepd.status=35
                left join  tbl_hrms_ed_official_details theod on tpep.EMPLOYEEID=theod.eb_id and theod.is_active=1
                 AND thepd.company_id = %s
                WHERE tpep.STATUS = 1
                  AND theod.emp_code IN ({placeholders})
            """
            cur.execute(sql_distinct, [company_id, *ebnos])
            ps = cur.fetchall()
            #print (ebnos)
            if len(ps) != 1 or int(ps[0]["PAY_SCHEME_ID"]) != int(payschm):
                sql_mismatch = f"""
                    SELECT theod.emp_code eb_no, tpep.PAY_SCHEME_ID
                    FROM tbl_pay_employee_payscheme tpep
                   JOIN tbl_hrms_ed_personal_details thepd on tpep.EMPLOYEEID=thepd.eb_id and is_active=1 and status=35
                left join  tbl_hrms_ed_official_details theod on tpep.EMPLOYEEID=theod.eb_id and is_active=1
                     AND thepd.company_id = %s
                    WHERE tpep.STATUS = 1
                      AND theod.emp_code IN ({placeholders})
                      AND tpep.PAY_SCHEME_ID <> %s
                """
                cur.execute(sql_mismatch, [company_id, *ebnos, payschm])
                bad = cur.fetchall()
                wrebs = ",".join([f"({r['eb_no']},{r['PAY_SCHEME_ID']})" for r in bad])

                elapsed = round(time.perf_counter() - start_perf, 3)
                print(json.dumps({
                    "success": False,
                    "savedata": "Not saved",
                    "reason": "Pay scheme mismatch",
                    "payschms": wrebs,
                    "start_time": start_time_clock,
                    "end_time": datetime.now().strftime("%H:%M:%S"),
                    "execution_time_sec": elapsed
                }))
                cn.rollback()
                return

            # ===== 2) Update status=0 for period rows =====
            sql_status0 = f"""
                UPDATE tbl_pay_components_custom tpcc
                JOIN worker_master wm ON wm.eb_id = tpcc.employeeid
                SET tpcc.status = 0
                WHERE tpcc.from_date = %s
                  AND tpcc.to_date = %s
                  AND wm.company_id = %s
                  AND wm.eb_no IN ({placeholders})
                  
            """
            cur.execute(sql_status0, [from_date, to_date, company_id, *ebnos])

            # ===== 3) component_id + linked_formula_id mapping =====
            cur.execute("""
                SELECT component_id, linked_formula_id
                FROM EMPMILL12.tbl_pay_custom_input_link
                WHERE payscheme_id = %s
                ORDER BY linked_formula_id
            """, [payschm])

            comp_map = [(int(r["component_id"]), int(r["linked_formula_id"])) for r in cur.fetchall()]

            if not comp_map:
                cn.rollback()
                elapsed = round(time.perf_counter() - start_perf, 3)
                print(json.dumps({
                    "success": False,
                    "savedata": "Not saved",
                    "reason": "No component_id linked to payscheme",
                    "start_time": start_time_clock,
                    "end_time": datetime.now().strftime("%H:%M:%S"),
                    "execution_time_sec": elapsed
                }))
                return

            # ===== 4) EBNO -> EB_ID map =====
            sql_emp = f"""
                SELECT emp_code eb_no, thepd.eb_id from 
                    tbl_hrms_ed_personal_details thepd 
                left join  tbl_hrms_ed_official_details theod on thepd.eb_id=theod.eb_id and theod.is_active=1
                WHERE thepd.company_id =%s   and thepd.is_active=1 and thepd.status=35
                  AND emp_code IN ({placeholders})
            """
            cur.execute(sql_emp, [company_id, *ebnos])
            emp_map = {r["eb_no"]: int(r["eb_id"]) for r in cur.fetchall()}

            # ===== 5) Insert/Update per employee (multi-values) =====
            for i in range(1, len(sheet)):  # row starts from 2 (skip header)
                row = sheet[i]
                ebno = row[0].strip() if len(row) > 0 and row[0] is not None else ""

                if not ebno:
                    failedEbnos.append(f"Row {i}: empty EMPID")
                    totalFailedRecords += 1
                    continue

                # cache eb_id
                if ebno in wmCache:
                    eb_id = wmCache[ebno]
                else:
                    eb_id = emp_map.get(ebno)
                    wmCache[ebno] = eb_id

                if not eb_id:
                    skipped_no_emp += 1
                    failedEbnos.append(f"EMPID not found: {ebno} (row {i})")
                    totalFailedRecords += 1
                    continue

                totalEmployees += 1

                # build tuples using linked_formula_id -> column mapping:
                # linked_formula_id=1 -> col D (index 3)
                values = []
                for comp_id, lf_id in comp_map:
                    col_idx = lf_id + 2  # 1->3 (D), 2->4 (E), ...
                    raw_val = row[col_idx] if col_idx < len(row) else ""
                    amt = safe_float(raw_val)

                    values.append((
                        comp_id,        # COMPONENT_ID
                        amt,            # VALUE
                        eb_id,          # EMPLOYEEID
                        1,              # STATUS
                        created_by,     # CREATED_BY
                        created_date,   # CREATED_DATE
                        from_date,      # FROM_DATE
                        to_date         # TO_DATE
                    ))

                if not values:
                    failedEbnos.append(f"No component values for EMPID {ebno} (row {i})")
                    totalFailedRecords += 1
                    continue

                totalComponentRecords += len(values)

                # multi-row insert placeholders
                ph = ",".join(["(%s,%s,%s,%s,%s,%s,%s,%s,CURRENT_TIMESTAMP())"] * len(values))

                sql_upsert = f"""
                INSERT INTO tbl_pay_components_custom
                (COMPONENT_ID, `VALUE`, EMPLOYEEID, `STATUS`, CREATED_BY, CREATED_DATE, FROM_DATE, TO_DATE, LUPDATE)
                VALUES {ph}
                """
 

                flat_params = []
                for t in values:
                    flat_params.extend(t)

                try:
                    # NOTE: do NOT call start_transaction() here (avoids "Transaction already in progress")
                    cur.execute(sql_upsert, flat_params)
                    cn.commit()

                    totalSuccessRecords += len(values)
                    inserted += len(values)  # approx like your PHP
                except Exception as e:
                    cn.rollback()
                    totalFailedRecords += len(values)
                    failedEbnos.append(f"DB error for EMPID {ebno} (row {i}): {str(e)}")
                    continue


                  # Handle any additional processing or logging here if needed

                sql_missing = """
                SELECT
                    tpep.*,
                    tpcc.empid,
                    theod.emp_code,
                    thepd.is_active,
                    thepd.first_name
                FROM tbl_pay_employee_payscheme tpep
                LEFT JOIN (
                    SELECT DISTINCT(employeeid) AS empid
                    FROM tbl_pay_components_custom tpcc
                    WHERE tpcc.status = 1
                    AND tpcc.from_date = %s
                    AND tpcc.to_date   = %s
                ) tpcc
                    ON tpep.employeeid = tpcc.empid
                LEFT JOIN tbl_hrms_ed_personal_details thepd
                    ON thepd.eb_id = tpep.employeeid AND thepd.is_active = 1
                LEFT JOIN tbl_hrms_ed_official_details theod
                    ON thepd.eb_id = theod.eb_id AND theod.is_active = 1
                WHERE tpep.status = 1
                AND tpep.pay_scheme_id = %s
                AND thepd.is_active = 1
                AND tpcc.empid IS NULL
                ORDER BY theod.emp_code
            """
            #print("Executing missing SQL", file=sys.stderr)
            #print(sql_missing, file=sys.stderr)

            cur.execute(sql_missing, (from_date, to_date, payschm))
            rows = cur.fetchall()
            #print(sql_missing  )
            ebids = []
            totalEmployees = 0
            ebmissing = 0

            for r in rows:
                # in your PHP: $record->EMPLOYEEID
                emp_id = int(r["EMPLOYEEID"]) if "EMPLOYEEID" in r else int(r["employeeid"])
                ebids.append(emp_id)
                totalEmployees += 1
                ebmissing += 1

            # Nothing to insert
            if ebmissing <= 0:
                elapsed = round(time.perf_counter() - start_perf, 3)
                respond({
                    "success": True,
                    "savedata": "Saved",
                    "inserted": inserted,
                    "updated": updated,
                    "allupdt": inserted + updated,
                    "skipped_no_emp": skipped_no_emp,
                    "totalEmployees": totalEmployees,
                    "totalComponentRecords": totalComponentRecords,
                    "totalSuccessRecords": totalSuccessRecords,
                    "totalFailedRecords": totalFailedRecords,
                    "failedEbnos": failedEbnos,
                    "start_time": start_time_clock,
                    "end_time": datetime.now().strftime("%H:%M:%S"),
                    "execution_time_sec": elapsed
                })
                return

            # --- Step 2: INSERT for those employees + selected components (like your insert-select) ---
            
            cur.execute("""
            SELECT component_id, linked_formula_id
            FROM EMPMILL12.tbl_pay_custom_input_link
            WHERE payscheme_id = %s
            ORDER BY linked_formula_id
        """, [payschm])

            comp_map = [(int(r["component_id"]), int(r["linked_formula_id"])) for r in cur.fetchall()]
            comp_ids = [cid for (cid, lf) in comp_map]   # only component_id

            emp_placeholders = ",".join(["%s"] * len(ebids))
            cmp_placeholders = ",".join(["%s"] * len(comp_ids))


            #print("ebids type:", type(ebids), "len:", len(ebids))
            #print("ebids sample:", ebids[:100])          # first 10 only
            #print("ebids last:", ebids[-5:])            # last 5

            #print("comp_ids type:", type(comp_ids), "len:", len(comp_ids))
            #print("comp_ids sample:", comp_ids[:100])          # first 10 only
            #print("comp_ids last:", comp_ids[-5:])            # last 5 
            #print("emp_placeholders:", emp_placeholders[:100] + " ...")  # show only first 100 chars
            #print("cmp_placeholders:", cmp_placeholders[:100] + " ...")

            # also check if any tuple exists
            bad_ebids = [x for x in ebids if isinstance(x, (tuple, list, dict, set))]
            bad_comp  = [x for x in comp_ids if isinstance(x, (tuple, list, dict, set))]
            #print("bad_ebids sample:", bad_ebids[:5])
            #print("bad_comp_ids sample:", bad_comp[:5])



            sql_insert = f"""
                INSERT INTO tbl_pay_components_custom
                    (COMPONENT_ID, VALUE, EMPLOYEEID, STATUS, CREATED_BY, CREATED_DATE, FROM_DATE, TO_DATE,LUPDATE)
                SELECT
                    tpes.component_id,
                    0 AS value,
                    tpep.employeeid,
                    1 AS status,
                    %s AS created_by,
                    CURDATE() AS created_date,
                    %s AS from_date,
                    %s AS to_date,CURRENT_TIMESTAMP()
                FROM tbl_pay_employee_payscheme tpep
                LEFT JOIN tbl_pay_employee_structure tpes
                    ON tpep.employeeid = tpes.employeeid AND tpes.status = 1
                WHERE tpep.employeeid IN ({emp_placeholders})
                AND tpep.status = 1
                AND tpes.component_id IN ({cmp_placeholders})
            """

            params = [created_by, from_date, to_date, *ebids, *comp_ids]   # ✅ comp_ids, NOT comp_map

            # ----- DEBUG: show first params + find tuples -----
            #print("params len:", len(params))
            for i, v in enumerate(params[:15]):
                #print(i, repr(v), type(v))

                tuple_params = [(i, repr(v), type(v)) for i, v in enumerate(params) if isinstance(v, tuple)]
            #print("tuple params:", tuple_params[:10])

            non_scalar = [(i, repr(v), type(v)) for i, v in enumerate(params)
                        if isinstance(v, (list, dict, set))]
            #print("list/dict/set params:", non_scalar[:10])


            #print(sql_insert)

            #params = [created_by, from_date, to_date, *ebids, *comp_ids]
            cur.execute(sql_insert, params)
            cn.commit()        



            elapsed = round(time.perf_counter() - start_perf, 3)
            #print(elapsed)
            respond({
                    "success": True,
                    "savedata": "Saved",
                    "inserted": inserted,
                    "updated": updated,
                    "allupdt": inserted + updated,
                    "skipped_no_emp": skipped_no_emp,
                    "totalEmployees": totalEmployees,
                    "totalComponentRecords": totalComponentRecords,
                    "totalSuccessRecords": totalSuccessRecords,
                    "totalFailedRecords": totalFailedRecords,
                    "failedEbnos": failedEbnos,
                    "start_time": start_time_clock,
                    "end_time": datetime.now().strftime("%H:%M:%S"),
                    "execution_time_sec": elapsed
            })
            return

        except Exception:
            cn.rollback()
            raise
        finally:
            try:
                cur.close()
            except Exception:
                pass
            try:
                cn.close()
            except Exception:
                pass

    except Exception as e:
        traceback.print_exc(file=sys.stderr)
        elapsed = round(time.perf_counter() - start_perf, 3)
        print(json.dumps({
            "success": False,
            "reason": str(e),
            "start_time": start_time_clock,
            "end_time": datetime.now().strftime("%H:%M:%S"),
            "execution_time_sec": elapsed
        }))


if __name__ == "__main__":
    main()
