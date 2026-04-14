import sys
import json
from datetime import datetime
from db import get_connection


def main_wages_process_ns(fromdate, todate, payscheme, company_id):
    connection = None

    try:
        # Validate date format
        datetime.strptime(fromdate, "%Y-%m-%d")
        datetime.strptime(todate, "%Y-%m-%d")

        connection = get_connection()

        sql1 = """
        INSERT INTO EMPMILL12.tbl_ejm_wages_data_collection
        (
            date_from,
            date_to,
            eb_id,
            ns_hours,
            ot_ns_hours,
            pay_scheme_id,
            update_for
        )
        SELECT
            %s AS df,
            %s AS dt,
            eb_id,
            SUM(rnhrs) AS ns_hours,
            SUM(onhrs) AS ot_ns_hours,
            %s AS payschm,
            'NSH' AS updt
        FROM (
            SELECT
                eb_id,
                da.attendance_date,
                spell,
                da.attendance_type,
                CASE
                    WHEN attendance_type = 'R'
                         AND SUM(da.working_hours - da.idle_hours) >= 7.5
                    THEN 0.5
                    ELSE 0
                END AS rnhrs,
                CASE
                    WHEN attendance_type = 'O'
                         AND SUM(da.working_hours - da.idle_hours) >= 7.5
                    THEN 0.5
                    ELSE 0
                END AS onhrs
            FROM vowsls.daily_attendance da
            WHERE da.attendance_date BETWEEN %s AND %s
              AND da.spell = 'C'
              AND da.company_id = %s
              AND da.is_active = 1
            GROUP BY
                eb_id,
                da.attendance_date,
                spell,
                attendance_type
        ) g
        LEFT JOIN vowsls.tbl_pay_employee_payscheme tpep
            ON tpep.EMPLOYEEID = g.eb_id
           AND tpep.PAY_SCHEME_ID = %s
           AND tpep.STATUS = 1
        WHERE tpep.PAY_SCHEME_ID IS NOT NULL
        GROUP BY eb_id
        """

        sql2 = """
        INSERT INTO EMPMILL12.tbl_ejm_wages_data_collection
        (
            date_from,
            date_to,
            eb_id,
            fest_hours,
            pay_scheme_id,
            update_for
        )
        SELECT
            %s AS df,
            %s AS dt,
            eb_id,
            SUM(thht.holiday_hours) AS hhrs,
            %s AS payschm,
            'FES' AS updt
        FROM vowsls.tbl_hrms_holiday_transactions thht
        LEFT JOIN vowsls.holiday_master hm
            ON hm.id = thht.holiday_id
        LEFT JOIN vowsls.tbl_pay_employee_payscheme tpep
            ON tpep.EMPLOYEEID = eb_id
           AND tpep.PAY_SCHEME_ID = %s
           AND tpep.STATUS = 1
        WHERE tpep.PAY_SCHEME_ID IS NOT NULL
          AND thht.is_active = 1
          AND hm.holiday_date BETWEEN %s AND %s
        GROUP BY eb_id
        """

        sql3 = """
        INSERT INTO EMPMILL12.tbl_ejm_wages_data_collection
        (
            date_from,
            date_to,
            eb_id,
            stl_days,
            pay_scheme_id,
            update_for
        )
        SELECT
            %s AS df,
            %s AS dt,
            eb_id,
            COUNT(*) AS stldays,
            %s AS payschm,
            'STL' AS updt
        FROM vowsls.leave_tran_details ltd
        LEFT JOIN vowsls.leave_transactions lt
            ON lt.leave_transaction_id = ltd.ltran_id
        LEFT JOIN vowsls.tbl_pay_employee_payscheme tpep
            ON tpep.EMPLOYEEID = eb_id
           AND tpep.PAY_SCHEME_ID = %s
           AND tpep.STATUS = 1
        WHERE tpep.PAY_SCHEME_ID IS NOT NULL
          AND ltd.leave_date BETWEEN %s AND %s
          AND ltd.is_active = 1
          AND lt.status = 3
          AND lt.leave_type_id = 24
        GROUP BY eb_id
        """

        with connection.cursor() as cursor:
            cursor.execute(
                sql1,
                (
                    fromdate,
                    todate,
                    payscheme,
                    fromdate,
                    todate,
                    company_id,
                    payscheme
                )
            )
            result1 = cursor.rowcount

            cursor.execute(
                sql2,
                (
                    fromdate,
                    todate,
                    payscheme,
                    payscheme,
                    fromdate,
                    todate
                )
            )
            result2 = cursor.rowcount

            cursor.execute(
                sql3,
                (
                    fromdate,
                    todate,
                    payscheme,
                    payscheme,
                    fromdate,
                    todate
                )
            )
            result3 = cursor.rowcount

        connection.commit()

        return {
            "success": True,
            "message": "NS processing completed",
            "rows_affected": {
                "nsh": result1,
                "fes": result2,
                "stl": result3
            }
        }
    except ValueError:
        return {
            "success": False,
            "message": "Invalid date format"
        }

    except Exception as e:
        if connection:
            connection.rollback()

        return {
            "success": False,
            "message": f"Exception: {str(e)}"
        }

    finally:
        if connection:
            connection.close()


if __name__ == "__main__":
    try:
        if len(sys.argv) < 5:
            print(json.dumps({
                "success": False,
                "message": "Usage: python main_wages_process_ns.py fromdate todate payscheme company_id"
            }))
            sys.exit(1)

        fromdate = sys.argv[1]
        todate = sys.argv[2]
        payscheme = sys.argv[3]
        company_id = sys.argv[4]

        result = main_wages_process_ns(fromdate, todate, payscheme, company_id)
        print(json.dumps(result))

    except Exception as e:
        print(json.dumps({
            "success": False,
            "message": f"Unhandled Exception: {str(e)}"
        }))