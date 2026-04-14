import sys
import json
from db import get_connection


def process_att_prep_clear(date_from, date_to, pay_scheme_id, dept_code=None):

    conn = None

    try:

        conn = get_connection()

        sql = """
        DELETE FROM EMPMILL12.tbl_ejm_wages_att_summary
        WHERE date_from = %s
        AND date_to = %s
        AND pay_scheme_id = %s
        AND update_from = 'ATT'
        AND updated_by IS NULL
        """

        with conn.cursor() as cursor:

            affected_rows = cursor.execute(
                sql,
                (date_from, date_to, pay_scheme_id)
            )

        conn.commit()

        return {
            "success": True,
            "message": "Attendance clear process completed",
            "deleted_rows": affected_rows
        }

    except Exception as e:

        if conn:
            conn.rollback()

        return {
            "success": False,
            "message": str(e)
        }

    finally:

        if conn:
            conn.close()


# CLI execution
if __name__ == "__main__":

    try:

        if len(sys.argv) < 4:

            print(json.dumps({
                "success": False,
                "message": "Usage: python process_att_prep_clear.py date_from date_to pay_scheme_id [dept_code]"
            }))
            sys.exit(1)

        date_from = sys.argv[1]
        date_to = sys.argv[2]
        pay_scheme_id = sys.argv[3]

        dept_code = None
        if len(sys.argv) > 4:
            dept_code = sys.argv[4]

        result = process_att_prep_clear(
            date_from,
            date_to,
            pay_scheme_id,
            dept_code
        )

        print(json.dumps(result))

    except Exception as e:

        print(json.dumps({
            "success": False,
            "message": str(e)
        }))