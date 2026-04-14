import sys
import json
from datetime import datetime
from db import get_connection


def main_wages_process_others(fromdate, todate, payscheme, company_id=None):
    connection = None

    try:
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
            time_basic
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
            tewas.working_hours AS working_hours_eff,
            tewas.ot_hours AS ot_hours_eff,
            %s AS payscheme,
            'OTHER' AS updt,
            'ATT55' AS updtfr,
            0 AS prdbas,
            0 AS time_basic
        FROM EMPMILL12.tbl_ejm_wages_att_summary tewas
        WHERE tewas.date_from = %s
          AND tewas.date_to = %s
          AND tewas.update_from = 'ATT'
          AND tewas.occu_code = '55'
          AND tewas.pay_scheme_id = %s
          AND tewas.is_active = 1
        """

        params = (
            fromdate,
            todate,
            payscheme,
            fromdate,
            todate,
            payscheme
        )

        with connection.cursor() as cursor:
            affected_rows = cursor.execute(sql, params)

        connection.commit()

        return {
            "success": True,
            "message": "Others processing completed",
            "affected_rows": affected_rows
        }

    except ValueError:
        if connection:
            connection.rollback()
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
        if len(sys.argv) < 4:
            print(json.dumps({
                "success": False,
                "message": "Usage: python main_wages_process_others.py fromdate todate payscheme [company_id]"
            }))
            sys.exit(1)

        fromdate = sys.argv[1]
        todate = sys.argv[2]
        payscheme = sys.argv[3]
        company_id = sys.argv[4] if len(sys.argv) > 4 else None

        result = main_wages_process_others(fromdate, todate, payscheme, company_id)
        print(json.dumps(result))

    except Exception as e:
        print(json.dumps({
            "success": False,
            "message": f"Unhandled Exception: {str(e)}"
        }))