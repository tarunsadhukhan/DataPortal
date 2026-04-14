import sys
import json
from datetime import datetime
from db import get_connection


def main_wages_process_weaving(fromdate, todate, payscheme, company_id):
    connection = None

    try:
        # Validate inputs
        fromdate = fromdate.strip()
        todate = todate.strip()
        payscheme = str(payscheme).strip()

        datetime.strptime(fromdate, "%Y-%m-%d")
        datetime.strptime(todate, "%Y-%m-%d")

        connection = get_connection()

        with connection.cursor() as cursor:
            # Start transaction
            cursor.execute("START TRANSACTION")

            # Drop temp table if exists in current session
            cursor.execute("DROP TEMPORARY TABLE IF EXISTS tmp_view_proc_ejm_loom_data_spell")

            # Create temp table
            temp_sql = """
                CREATE TEMPORARY TABLE tmp_view_proc_ejm_loom_data_spell AS
                SELECT *
                FROM EMPMILL12.view_proc_ejm_loom_data_spell vpelds
                WHERE vpelds.loom_date BETWEEN %s AND %s
            """
            cursor.execute(temp_sql, (fromdate, todate))

            # Main insert query
            sql = """
            INSERT INTO EMPMILL12.tbl_ejm_wages_data_collection
            (
                date_from, date_to, eb_id, dept_code, occu_code, shift, t_p, working_hours,
                ot_hours, working_hours_eff, ot_hours_eff, pay_scheme_id, update_for,
                updt_from, prod_basic, time_basic, act_eff
            )
            SELECT
                %s AS df,
                %s AS dt,
                tewas.eb_id,
                tewas.dept_code,
                tewas.occu_code,
                tewas.shift,
                'P' AS t_p,
                tewas.working_hours,
                tewas.ot_hours,
                CASE
                    WHEN IFNULL(prd.acteff, 0) < 100 AND IFNULL(prd.acteff, 0) > 0
                    THEN ROUND((prd.acteff / 100) * tewas.working_hours, 2)
                    ELSE tewas.working_hours
                END AS working_hours_eff,
                CASE
                    WHEN IFNULL(prd.acteff, 0) < 100 AND IFNULL(prd.acteff, 0) > 0
                    THEN ROUND((prd.acteff / 100) * tewas.ot_hours, 2)
                    ELSE tewas.ot_hours
                END AS ot_hours_eff,
                %s AS payschm,
                'LOOMI' AS updt,
                'PROD' AS updtfr,
                CASE
                    WHEN IFNULL(prd.acteff, 0) > 0 THEN ROUND(prd.amount / NULLIF(prd.acteff, 0) * 100, 2)
                    ELSE 0
                END AS prdbas,
                CASE
                    WHEN tewas.pay_scheme_id = 125
                         AND IFNULL(prd.acteff, 0) < 100
                         AND IFNULL(prd.acteff, 0) > 0
                    THEN ROUND(((tewas.working_hours * 13.5) / 3 * 2)
                         + (((tewas.working_hours * 13.5) / 3) * prd.acteff / 100), 2)

                    WHEN tewas.pay_scheme_id = 125
                         AND IFNULL(prd.acteff, 0) >= 100
                    THEN ROUND((tewas.working_hours * 13.5), 2)

                    WHEN tewas.pay_scheme_id = 151
                         AND IFNULL(prd.acteff, 0) < 100
                         AND IFNULL(prd.acteff, 0) > 0
                    THEN ROUND((tewas.working_hours * twor.f_b_rate) / 3 * 2
                         + ((tewas.working_hours * twor.f_b_rate) / 3) * prd.acteff / 100, 2)

                    WHEN tewas.pay_scheme_id = 151
                         AND IFNULL(prd.acteff, 0) >= 100
                    THEN ROUND((tewas.working_hours * twor.f_b_rate), 2)

                    ELSE 0
                END AS time_basic,
                prd.acteff AS act_eff
            FROM EMPMILL12.tbl_ejm_wages_att_summary tewas
            LEFT JOIN (
                SELECT
                    eb_id,
                    shift,
                    dept_code,
                    occu_code,
                    SUM(no_of_cuts * rate) AS amount,
                    ROUND(SUM(eff * tot_hrs) / NULLIF(SUM(tot_hrs), 0), 2) AS avgeff,
                    ROUND(SUM(target_eff * tot_hrs) / NULLIF(SUM(tot_hrs), 0), 2) AS tareff,
                    ROUND(
                        ROUND(SUM(eff * tot_hrs) / NULLIF(SUM(tot_hrs), 0), 2)
                        / NULLIF(ROUND(SUM(target_eff * tot_hrs) / NULLIF(SUM(tot_hrs), 0), 2), 0)
                        * 100,
                        2
                    ) AS acteff
                FROM (
                    SELECT
                        vpelds.*,
                        ewql.wages_code,
                        IFNULL(tqr.rate, 0) AS rate,
                        %s AS company_id,
                        (vpelds.diffm / NULLIF(vpelds.finished_length, 0)) AS no_of_cuts,
                        tate.target_eff,
                        dm.dept_code,
                        omn.OCCU_CODE AS occu_code,
                        wm.eb_id
                    FROM tmp_view_proc_ejm_loom_data_spell vpelds
                    LEFT JOIN EMPMILL12.tbl_prod_wages_code_link ewql
                        ON ewql.prod_code = vpelds.qcod
                       AND ewql.dept_id = 8
                    LEFT JOIN EMPMILL12.tbl_quality_rate tqr
                        ON ewql.wages_code = tqr.qcode
                       AND tqr.dept_code = '08'
                    LEFT JOIN EMPMILL12.tbl_all_trn_eff tate
                        ON tate.qual_code = vpelds.qcod
                       AND tate.dept_id = 8
                       AND tate.date_from = %s
                       AND tate.date_to = %s
                    LEFT JOIN vowsls.daily_attendance da
                        ON da.attendance_date = vpelds.loom_date
                       AND da.spell = vpelds.spell
                       AND da.eb_no = vpelds.tktno
                       AND da.is_active = 1
                       AND da.company_id = %s
                    LEFT JOIN vowsls.department_master dm
                        ON dm.dept_id = da.worked_department_id
                    LEFT JOIN EMPMILL12.OCCUPATION_MASTER_NORMS omn
                        ON omn.desig_id = da.worked_designation_id
                    LEFT JOIN vowsls.worker_master wm
                        ON wm.eb_no = vpelds.tktno
                       AND wm.company_id = %s
                    WHERE vpelds.loom_date BETWEEN %s AND %s
                      AND eff > 0
                ) g
                GROUP BY eb_id, shift, dept_code, occu_code
            ) prd
                ON tewas.eb_id = prd.eb_id
               AND tewas.shift = prd.shift
               AND tewas.dept_code = prd.dept_code
               AND tewas.occu_code = prd.occu_code
            LEFT JOIN EMPMILL12.tbl_wages_occu_rate twor
                ON tewas.dept_code = twor.dept_code
               AND tewas.occu_code = twor.occu_code
            WHERE tewas.dept_code = '08'
              AND tewas.occu_code IN ('01', '04')
              AND tewas.is_active = 1
              AND tewas.pay_scheme_id = %s
            """

            params = (
                fromdate,                 # final df
                todate,                   # final dt
                payscheme,                # final payschm
                company_id,               # subquery company_id selected literal
                fromdate,                 # tate.date_from
                todate,                   # tate.date_to
                company_id,               # da.company_id
                company_id,               # wm.company_id
                fromdate,                 # vpelds.loom_date from
                todate,                   # vpelds.loom_date to
                payscheme                 # final tewas.pay_scheme_id
            )

            affected_rows = cursor.execute(sql, params)

            cursor.execute("COMMIT")

        return {
            "success": True,
            "message": "Weaving processing completed",
            "affected_rows": affected_rows,
            "status": "COMPLETED"
        }

    except ValueError:
        if connection:
            try:
                with connection.cursor() as cursor:
                    cursor.execute("ROLLBACK")
            except Exception:
                pass
        return {
            "success": False,
            "message": "Invalid date format",
            "status": "FAILED"
        }

    except Exception as e:
        if connection:
            try:
                with connection.cursor() as cursor:
                    cursor.execute("ROLLBACK")
            except Exception:
                pass
        return {
            "success": False,
            "message": f"Exception: {str(e)}",
            "status": "FAILED"
        }

    finally:
        if connection:
            connection.close()


if __name__ == "__main__":
    try:
        if len(sys.argv) < 5:
            print(json.dumps({
                "success": False,
                "message": "Usage: python main_wages_process_weaving.py fromdate todate payscheme company_id"
            }))
            sys.exit(1)

        fromdate = sys.argv[1]
        todate = sys.argv[2]
        payscheme = sys.argv[3]
        company_id = sys.argv[4]

        result = main_wages_process_weaving(fromdate, todate, payscheme, company_id)
        print(json.dumps(result))

    except Exception as e:
        print(json.dumps({
            "success": False,
            "message": f"Unhandled Exception: {str(e)}",
            "status": "FAILED"
        }))