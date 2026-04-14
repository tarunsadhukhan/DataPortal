import sys
import json
from datetime import datetime
from db import get_connection


def process_att_prep(date_from, date_to, pay_scheme_id, dept_code=None, company_id=2):
    conn = None

    try:
        datetime.strptime(date_from, "%Y-%m-%d")
        datetime.strptime(date_to, "%Y-%m-%d")

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
            da.eb_id,
            da.dept_code,
            da.occu_code,
            da.shift,
            da.t_p,
            '' AS mcnos,

            SUM(CASE WHEN da.attendance_type = 'R' THEN da.whrs ELSE 0 END) AS whrs,
            SUM(CASE WHEN da.attendance_type = 'O' THEN da.whrs ELSE 0 END) AS othrs,
            SUM(da.nwhrs) AS nhrs,

            %s AS payschm,
            'ATT' AS update_from
        FROM (
            SELECT
                x.eb_id,
                x.eb_no,
                x.shift,
                x.attendance_type,
                x.occu_code,
                x.dept_code,
                x.t_p,
                x.dept_id,

                CASE
                    WHEN x.shift = 'C' AND x.attendance_type = 'R' THEN x.working_hours
                    WHEN x.shift = 'C' AND x.working_hours = 7.5 AND x.attendance_type = 'O' THEN x.working_hours
                    WHEN x.shift = 'C' AND x.working_hours <> 7.5 AND x.attendance_type = 'O' THEN x.working_hours
                    WHEN x.shift <> 'C' THEN x.working_hours
                    ELSE 0
                END AS whrs,

                CASE
                    WHEN x.shift = 'C' AND x.working_hours = 7.5 AND x.attendance_type = 'R' THEN 0.5
                    ELSE 0
                END AS nwhrs
            FROM (
                SELECT
                    da.eb_id,
                    da.eb_no,
                    SUBSTR(da.spell, 1, 1) AS shift,
                    da.attendance_type,
                    CASE
                        WHEN LENGTH(om.occu_code) > 2 THEN 55
                        ELSE om.occu_code
                    END AS occu_code,
                    dm.dept_code,
                    om.time_piece AS t_p,
                    SUM(da.working_hours - da.idle_hours) AS working_hours,
                    da.worked_department_id AS dept_id
                FROM vowsls.daily_attendance da
                LEFT JOIN EMPMILL12.OCCUPATION_MASTER om
                    ON om.vow_occu_id = da.worked_designation_id
                LEFT JOIN vowsls.department_master dm
                    ON da.worked_department_id = dm.dept_id
                WHERE da.attendance_date BETWEEN %s AND %s
                  AND da.company_id = %s
                GROUP BY
                    da.eb_id,
                    da.eb_no,
                    da.attendance_date,
                    SUBSTR(da.spell, 1, 1),
                    da.attendance_type,
                    CASE
                        WHEN LENGTH(om.occu_code) > 2 THEN 55
                        ELSE om.occu_code
                    END,
                    dm.dept_code,
                    om.time_piece,
                    da.worked_department_id
            ) x
        ) da
        JOIN vowsls.tbl_pay_employee_payscheme tpep
          ON tpep.employeeid = da.eb_id
         AND tpep.pay_scheme_id = %s
         AND tpep.status = 1
        WHERE NOT (
                da.dept_code = '07'
             OR (da.dept_code = '09' AND da.occu_code IN ('01', '02'))
             OR (da.dept_code = '08' AND da.occu_code IN ('02', '03', '05', '06'))
        )
        GROUP BY
            da.eb_id,
            da.dept_code,
            da.occu_code,
            da.shift,
            da.t_p
        """

        params = (
            date_from,
            date_to,
            pay_scheme_id,
            date_from,
            date_to,
            company_id,
            pay_scheme_id
        )

        with conn.cursor() as cursor:
            affected_rows = cursor.execute(sql, params)

        if affected_rows == 0:
            conn.rollback()
            return {
                "success": False,
                "message": "No attendance data found to process"
            }

        conn.commit()

        return {
            "success": True,
            "count": affected_rows,
            "message": f"{affected_rows} records processed"
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
            "message": f"Error executing query: {str(e)}"
        }

    finally:
        if conn:
            conn.close()


if __name__ == "__main__":
    try:
        if len(sys.argv) < 5:
            print(json.dumps({
                "success": False,
                "message": "Usage: python process_att_prep.py date_from date_to pay_scheme_id company_id [dept_code]"
            }))
            sys.exit(1)

        date_from = sys.argv[1]
        date_to = sys.argv[2]
        pay_scheme_id = sys.argv[3]
        company_id = sys.argv[4]
        dept_code = sys.argv[5] if len(sys.argv) > 5 else None

        result = process_att_prep(date_from, date_to, pay_scheme_id, dept_code, company_id)
        print(json.dumps(result))

    except Exception as e:
        print(json.dumps({
            "success": False,
            "message": f"Unhandled error: {str(e)}"
        }))