import sys
import json
from datetime import datetime
from db import get_connection


def process_att_prep_bmg(date_from, date_to, pay_scheme_id, dept_code=None, company_id=None):
    conn = None

    try:
        datetime.strptime(date_from, "%Y-%m-%d")
        datetime.strptime(date_to, "%Y-%m-%d")

        if company_id is None:
            return {
                "success": False,
                "message": "company_id is required"
            }

        conn = get_connection()

        sql = """
        INSERT INTO EMPMILL12.tbl_ejm_wages_att_summary
        (
            date_from,
            date_to,
            eb_id,
            dept_code,
            occu_code,
            shift,
            t_p,
            mc_nos,
            working_hours,
            ot_hours,
            ns_hours,
            pay_scheme_id,
            update_from
        )
        SELECT
            %s AS df,
            %s AS dt,
            eb_id,
            '07' AS dept_code,
            occu_code,
            shift,
            t_p,
            amcnos,
            rwhrs,
            owhrs,
            0 AS nwhrs,
            %s,
            'ATT' AS update_from
        FROM
        (
            SELECT
                eb_id,
                shift,
                occu_code,
                t_p,
                CASE WHEN occu_code <> '55' THEN mcnos ELSE ' ' END AS amcnos,
                MAX(CASE WHEN regular_ot = 'R' THEN rwhrs ELSE 0 END) AS rwhrs,
                MAX(CASE WHEN regular_ot = 'O' THEN rwhrs ELSE 0 END) AS owhrs,
                SUM(nwhrs) AS nwhrs,
                0 AS fhrs
            FROM
            (
                SELECT
                    shift,
                    eb_id,
                    regular_ot,
                    occu_code,
                    t_p,
                    mcnos,
                    SUM(rwhrs) AS rwhrs,
                    SUM(nwhrs) AS nwhrs
                FROM
                (
                    SELECT
                        attendance_date,
                        spell,
                        shift,
                        eb_id,
                        regular_ot,
                        occu_code,
                        t_p,
                        rwhrs,
                        nwhrs,
                        GROUP_CONCAT(DISTINCT mech_code SEPARATOR '') AS mcnos
                    FROM
                    (
                        SELECT
                            da.attendance_date,
                            da.spell,
                            SUBSTR(da.spell, 1, 1) AS shift,
                            da.eb_id,
                            da.worked_department_id,
                            worked_designation_id,
                            da.attendance_type AS regular_ot,
                            om.occu_code,
                            d.time_piece AS t_p,
                            CASE
                                WHEN (da.spell = 'C' AND attendance_type = 'O' AND (working_hours - idle_hours) = 7.5) THEN 8
                                WHEN (da.spell = 'C' AND attendance_type = 'O' AND (working_hours - idle_hours) <> 7.5) THEN (working_hours - idle_hours)
                                WHEN (da.spell = 'C' AND attendance_type = 'R' AND (working_hours - idle_hours) = 7.5) THEN (working_hours - idle_hours)
                                WHEN da.spell <> 'C' THEN (working_hours - idle_hours)
                                ELSE 0
                            END AS rwhrs,
                            CASE
                                WHEN (da.spell = 'C' AND attendance_type = 'R' AND (working_hours - idle_hours) = 7.5) THEN 0.5
                                ELSE 0
                            END AS nwhrs,
                            mech_code
                        FROM vowsls.daily_attendance da
                        LEFT JOIN (
                            SELECT *
                            FROM vowsls.daily_ebmc_attendance
                            WHERE is_active = 1
                        ) dea
                            ON da.daily_atten_id = dea.daily_atten_id
                        LEFT JOIN vowsls.mechine_master mm
                            ON mm.mechine_id = dea.mc_id
                        LEFT JOIN vowsls.worker_master wm
                            ON wm.eb_id = da.eb_id
                        LEFT JOIN vowsls.designation d
                            ON d.id = da.worked_designation_id
                        LEFT JOIN EMPMILL12.OCCUPATION_MASTER om
                            ON om.vow_occu_id = da.worked_designation_id
                        LEFT JOIN vowsls.category_master cm
                            ON wm.cata_id = cm.cata_id
                        WHERE da.attendance_date BETWEEN %s AND %s
                          AND da.worked_department_id = 7
                          AND wm.cata_id IN (3,4,5,6,7,9)
                          AND da.is_active = 1
                          AND da.company_id = %s
                    ) g
                    GROUP BY
                        attendance_date,
                        spell,
                        shift,
                        eb_id,
                        regular_ot,
                        occu_code,
                        t_p,
                        rwhrs,
                        nwhrs
                ) g
                GROUP BY
                    eb_id,
                    shift,
                    eb_id,
                    regular_ot,
                    occu_code,
                    t_p,
                    mcnos
            ) h
            GROUP BY
                eb_id,
                shift,
                occu_code,
                t_p,
                mcnos
        ) g
        LEFT JOIN vowsls.tbl_pay_employee_payscheme tpep
            ON g.eb_id = tpep.EMPLOYEEID
        WHERE tpep.PAY_SCHEME_ID = %s
          AND tpep.STATUS = 1
        """

        params = (
            date_from,
            date_to,
            pay_scheme_id,
            date_from,
            date_to,
            company_id,
            pay_scheme_id,
        )

        with conn.cursor() as cursor:
            affected_rows = cursor.execute(sql, params)

        conn.commit()

        return {
            "success": True,
            "message": "Beaming attendance preparation completed",
            "inserted_rows": affected_rows
        }

    except ValueError:
        if conn:
            conn.rollback()
        return {
            "success": False,
            "message": "Invalid date format"
        }

    except Exception as e:
        if conn:
            conn.rollback()
        return {
            "success": False,
            "message": f"Exception: {str(e)}"
        }

    finally:
        if conn:
            conn.close()


if __name__ == "__main__":
    try:
        if len(sys.argv) < 5:
            print(json.dumps({
                "success": False,
                "message": "Usage: python process_att_prep_bmg.py date_from date_to pay_scheme_id company_id [dept_code]"
            }))
            sys.exit(1)

        date_from = sys.argv[1]
        date_to = sys.argv[2]
        pay_scheme_id = sys.argv[3]
        company_id = sys.argv[4]
        dept_code = sys.argv[5] if len(sys.argv) > 5 else None

        result = process_att_prep_bmg(date_from, date_to, pay_scheme_id, dept_code, company_id)
        print(json.dumps(result))

    except Exception as e:
        print(json.dumps({
            "success": False,
            "message": f"Unhandled error: {str(e)}"
        }))