import sys
import json
from datetime import datetime
from db import get_connection


def main_wages_process_jute(fromdate, todate, payscheme, company_id):
    connection = None

    try:
        # Validate date format
        datetime.strptime(fromdate, "%Y-%m-%d")
        datetime.strptime(todate, "%Y-%m-%d")

        connection = get_connection()

        sql = """
        INSERT INTO EMPMILL12.tbl_ejm_wages_data_collection
        (
            date_from,
            date_to,
            eb_id,
            dept_code,
            occu_code,
            shift,
            t_p,
            working_hours,
            ot_hours,
            working_hours_eff,
            ot_hours_eff,
            pay_scheme_id,
            update_for,
            updt_from,
            prod_basic,
            time_basic,
            act_eff
        )
        SELECT
            %s AS df,
            %s AS dt,
            tewas.eb_id,
            tewas.dept_code,
            tewas.occu_code,
            tewas.shift,
            tewas.t_p,
            tewas.working_hours,
            tewas.ot_hours,
            tewas.working_hours AS working_hours_eff,
            tewas.ot_hours AS ot_hours_eff,
            %s AS payschm,
            'JUTIS' AS updt,
            'PROD' AS updtfr,
            ROUND((tate.target_eff / 8 * tewas.working_hours) * tqr.rate, 2) AS prodbas,
            ROUND(tewas.working_hours * twor.f_b_rate, 2) AS timebasic,
            100 AS acteff
        FROM EMPMILL12.tbl_ejm_wages_att_summary tewas
        JOIN (
            SELECT
                SUM(twkhrs) AS tkwhrs,
                SUM(weight) AS weight,
                '01' AS deptcode,
                '01' AS occucode,
                SUM(weight) / SUM(twkhrs) * 8 AS calc_val
            FROM (
                SELECT
                    SUM(working_hours - da.idle_hours) AS twkhrs,
                    0 AS weight
                FROM vowsls.daily_attendance da
                WHERE da.attendance_date BETWEEN %s AND %s
                  AND da.is_active = 1
                  AND da.worked_designation_id = %s

                UNION ALL

                SELECT
                    0 AS twkhrs,
                    SUM(weight) AS weight
                FROM EMPMILL12.issufile i
                WHERE i.issuedate BETWEEN %s AND %s
                  AND i.is_active = 1
            ) g
        ) jut
            ON jut.deptcode = tewas.dept_code
           AND jut.occucode = tewas.occu_code
        LEFT JOIN EMPMILL12.tbl_quality_rate tqr
            ON jut.deptcode = tqr.dept_code
           AND tqr.qcode = '004'
        LEFT JOIN vowsls.department_master dm
            ON dm.dept_code = jut.deptcode
           AND dm.company_id = 2
        LEFT JOIN EMPMILL12.tbl_all_trn_eff tate
            ON tate.dept_id = dm.dept_id
           AND tate.qual_code = '004'
        LEFT JOIN EMPMILL12.tbl_wages_occu_rate twor
            ON twor.dept_code = jut.deptcode
           AND twor.occu_code = jut.occucode
           AND tate.date_from = tewas.date_from
           AND tate.date_to = tewas.date_to
        WHERE tewas.dept_code = '01'
          AND tewas.occu_code = '01'
          AND tewas.date_from = %s
          AND tewas.date_to = %s
          AND tewas.is_active = 1
        """

        params = (
            fromdate,          # select df
            todate,            # select dt
            payscheme,         # payscheme
            fromdate,          # daily_attendance from
            todate,            # daily_attendance to
            company_id,        # worked_designation_id
            fromdate,          # issufile from
            todate,            # issufile to
            fromdate,          # tewas.date_from
            todate             # tewas.date_to
        )

        #with connection.cursor() as cursor:
        #    affected_rows = cursor.execute(sql, params)

        with connection.cursor() as cursor:
            result = cursor.execute(sql,params)
        #print("execute result =", result)
        #print("rowcount =", cursor.rowcount)
        affected_rows = cursor.rowcount



        connection.commit()

        return {
            "success": True,
            "message": "Jute processing completed",
            "affected_rows": affected_rows
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
                "message": "Usage: python main_wages_process_jute.py fromdate todate payscheme company_id"
            }))
            sys.exit(1)

        fromdate = sys.argv[1]
        todate = sys.argv[2]
        payscheme = sys.argv[3]
        company_id = sys.argv[4]

        result = main_wages_process_jute(fromdate, todate, payscheme, company_id)
        print(json.dumps(result))

    except Exception as e:
        print(json.dumps({
            "success": False,
            "message": f"Unhandled Exception: {str(e)}"
        }))