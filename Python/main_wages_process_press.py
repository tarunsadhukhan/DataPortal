import sys
import json
from datetime import datetime
from db import get_connection


def main_wages_process_press(fromdate, todate, payscheme, company_id):
    connection = None

    try:
        datetime.strptime(fromdate, "%Y-%m-%d")
        datetime.strptime(todate, "%Y-%m-%d")

        connection = get_connection()

        with connection.cursor() as cursor:
            cursor.execute("START TRANSACTION")

            cursor.execute("DROP TEMPORARY TABLE IF EXISTS raw_press_data")
            cursor.execute("DROP TEMPORARY TABLE IF EXISTS max_press_target")
            cursor.execute("DROP TEMPORARY TABLE IF EXISTS conv_press_target")
            cursor.execute("DROP TEMPORARY TABLE IF EXISTS conv_press_target_wcode")
            cursor.execute("DROP TEMPORARY TABLE IF EXISTS conv_press_target_prod")
            cursor.execute("DROP TEMPORARY TABLE IF EXISTS conv_press_target_prod_da")
            cursor.execute("DROP TEMPORARY TABLE IF EXISTS conv_press_target_prod_dea")
            cursor.execute("DROP TEMPORARY TABLE IF EXISTS conv_press_target_prod_eff")

            sql = """
            CREATE TEMPORARY TABLE raw_press_data AS
            SELECT
                fe.company_id,
                SUBSTR(fe.entry_date, 1, 10) AS trandate,
                spell,
                tpwcl.wages_code,
                fe.machine_id,
                fe.production,
                tate.target_eff,
                ptm.process_code
            FROM vowsls.finishing_entries fe
            LEFT JOIN vowsls.process_type_master ptm
                ON fe.work_type = ptm.process_type_id
            LEFT JOIN EMPMILL12.tbl_prod_wages_code_link tpwcl
                ON tpwcl.prod_code = ptm.process_code
               AND tpwcl.dept_id = 9
            LEFT JOIN EMPMILL12.tbl_all_trn_eff tate
                ON tate.qual_code = tpwcl.wages_code
               AND tate.date_from = %s
               AND tate.date_to = %s
            WHERE SUBSTR(fe.entry_date, 1, 10) BETWEEN %s AND %s
              AND tpwcl.dept_id = tate.dept_id
              AND fe.is_active = 1
              AND ptm.michine_type = 31
            """
            cursor.execute(sql, (fromdate, todate, fromdate, todate))

            sql = """
            CREATE TEMPORARY TABLE max_press_target AS
            SELECT
                trandate,
                spell,
                machine_id,
                MAX(target_eff) AS maxtarget
            FROM raw_press_data
            GROUP BY trandate, spell, machine_id
            """
            cursor.execute(sql)

            sql = """
            CREATE TEMPORARY TABLE conv_press_target AS
            SELECT
                rpd.*,
                mpt.maxtarget,
                rpd.production / rpd.target_eff * mpt.maxtarget AS conv_prod
            FROM raw_press_data rpd
            LEFT JOIN max_press_target mpt
                ON rpd.trandate = mpt.trandate
               AND rpd.spell = mpt.spell
               AND rpd.machine_id = mpt.machine_id
            """
            cursor.execute(sql)

            sql = """
            CREATE TEMPORARY TABLE conv_press_target_wcode AS
            SELECT *
            FROM (
                SELECT
                    t.*,
                    ROW_NUMBER() OVER (
                        PARTITION BY trandate, spell, machine_id
                        ORDER BY target_eff DESC
                    ) rn
                FROM conv_press_target t
            ) x
            WHERE rn = 1
            """
            cursor.execute(sql)

            sql = """
            CREATE TEMPORARY TABLE conv_press_target_prod AS
            SELECT
                cpt.*,
                cptw.wages_code AS mxwgcode,
                cpt.conv_prod * tqr.rate AS totamt
            FROM conv_press_target cpt
            LEFT JOIN conv_press_target_wcode cptw
                ON cpt.trandate = cptw.trandate
               AND cpt.spell = cptw.spell
               AND cpt.machine_id = cptw.machine_id
            LEFT JOIN EMPMILL12.tbl_quality_rate tqr
                ON tqr.qcode = cptw.wages_code
               AND tqr.dept_code = 9
            """
            cursor.execute(sql)

            sql = """
            CREATE TEMPORARY TABLE conv_press_target_prod_da AS
            SELECT
                da.daily_atten_id,
                da.eb_id,
                da.attendance_date,
                spell,
                da.working_hours - da.idle_hours AS wkhrs
            FROM vowsls.daily_attendance da
            WHERE da.attendance_date BETWEEN %s AND %s
              AND da.is_active = 1
              AND da.worked_designation_id IN (98, 114)
            """
            cursor.execute(sql, (fromdate, todate))

            sql = """
            CREATE TEMPORARY TABLE conv_press_target_prod_dea AS
            SELECT *
            FROM vowsls.daily_ebmc_attendance dea
            WHERE dea.attendace_date BETWEEN %s AND %s
              AND dea.is_active = 1
              AND dea.designation_id IN (98, 114)
            """
            cursor.execute(sql, (fromdate, todate))

            sql = """
            CREATE TEMPORARY TABLE conv_press_target_prod_eff AS
            SELECT
                eb_id,
                shift,
                mech_code,
                ROUND(SUM(totamt / 4), 2) AS totamt,
                ROUND(SUM(convprod) / SUM(tgprod) * 100, 2) AS acteff
            FROM (
                SELECT
                    da.eb_id,
                    da.attendance_date,
                    SUBSTR(da.spell, 1, 1) AS shift,
                    da.wkhrs,
                    cpt.convprod,
                    cpt.totamt,
                    cpt.maxtarget,
                    cpt.maxtarget / 8 * da.wkhrs AS tgprod,
                    dea.mc_id,
                    mm.mech_code
                FROM conv_press_target_prod_da da
                LEFT JOIN conv_press_target_prod_dea dea
                    ON da.daily_atten_id = dea.daily_atten_id
                LEFT JOIN (
                    SELECT
                        trandate,
                        spell,
                        machine_id,
                        maxtarget,
                        SUM(conv_prod) AS convprod,
                        SUM(totamt) AS totamt
                    FROM conv_press_target_prod cptp
                    GROUP BY trandate, spell, machine_id, maxtarget
                ) cpt
                    ON da.attendance_date = cpt.trandate
                   AND da.spell = cpt.spell
                   AND dea.mc_id = cpt.machine_id
                LEFT JOIN vowsls.mechine_master mm
                    ON mm.mechine_id = dea.mc_id
                WHERE da.attendance_date BETWEEN %s AND %s
            ) g
            GROUP BY eb_id, shift, mech_code
            """
            cursor.execute(sql, (fromdate, todate))

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
                    WHEN IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0
                    THEN ROUND((ehd.acteff / 100) * tewas.working_hours, 2)
                    ELSE tewas.working_hours
                END AS working_hours_eff,
                CASE
                    WHEN IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0
                    THEN ROUND((ehd.acteff / 100) * tewas.ot_hours, 2)
                    ELSE tewas.ot_hours
                END AS ot_hours_eff,
                %s AS payschm,
                'PRESS' AS updt,
                'PROD' AS updtfr,
                CASE
                    WHEN IFNULL(ehd.acteff, 0) > 0 THEN ROUND(ehd.totamt / NULLIF(ehd.acteff, 0) * 100, 2)
                    ELSE 0
                END AS prdbas,
                CASE
                    WHEN tewas.pay_scheme_id = 125
                         AND IFNULL(ehd.acteff, 0) < 100
                         AND IFNULL(ehd.acteff, 0) > 0
                    THEN ROUND(((tewas.working_hours * 13.5) / 3 * 2) + (((tewas.working_hours * 13.5) / 3) * ehd.acteff / 100), 2)

                    WHEN tewas.pay_scheme_id = 125
                         AND IFNULL(ehd.acteff, 0) >= 100
                    THEN ROUND((tewas.working_hours * 13.5), 2)

                    WHEN tewas.pay_scheme_id = 151
                         AND IFNULL(ehd.acteff, 0) < 100
                         AND IFNULL(ehd.acteff, 0) > 0
                    THEN ROUND((tewas.working_hours * twor.f_b_rate) / 3 * 2 + ((tewas.working_hours * twor.f_b_rate) / 3) * ehd.acteff / 100, 2)

                    WHEN tewas.pay_scheme_id = 151
                         AND IFNULL(ehd.acteff, 0) >= 100
                    THEN ROUND((tewas.working_hours * twor.f_b_rate), 2)

                    ELSE 0
                END AS time_basic,
                ehd.acteff
            FROM EMPMILL12.tbl_ejm_wages_att_summary tewas
            LEFT JOIN EMPMILL12.tbl_wages_occu_rate twor
                ON tewas.dept_code = twor.dept_code
               AND tewas.occu_code = twor.occu_code
            LEFT JOIN conv_press_target_prod_eff ehd
                ON tewas.shift = ehd.shift
               AND tewas.mc_nos = ehd.mech_code
               AND tewas.eb_id = ehd.eb_id
            WHERE tewas.date_from = %s
              AND tewas.date_to = %s
              AND tewas.update_from = 'ATT'
              AND tewas.dept_code = '09'
              AND tewas.occu_code IN ('01', '02')
              AND tewas.pay_scheme_id = %s
              AND tewas.is_active = 1
            """
            affected_rows = cursor.execute(sql, (fromdate, todate, payscheme, fromdate, todate, payscheme))

            cursor.execute("COMMIT")

        return {
            "success": True,
            "message": "Press processing completed",
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
                "message": "Usage: python main_wages_process_press.py fromdate todate payscheme company_id"
            }))
            sys.exit(1)

        fromdate = sys.argv[1]
        todate = sys.argv[2]
        payscheme = sys.argv[3]
        company_id = sys.argv[4]

        result = main_wages_process_press(fromdate, todate, payscheme, company_id)
        print(json.dumps(result))

    except Exception as e:
        print(json.dumps({
            "success": False,
            "message": f"Unhandled Exception: {str(e)}",
            "status": "FAILED"
        }))