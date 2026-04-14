import sys
import json
from datetime import datetime
from db import get_connection


def main_wages_process_clear(fromdate, todate, payscheme,company_id):

    connection = None

    try:
        # validate date
        datetime.strptime(fromdate, "%Y-%m-%d")
        datetime.strptime(todate, "%Y-%m-%d")

        connection = get_connection()
        compid=company_id
        sql = """
        DELETE FROM EMPMILL12.tbl_ejm_wages_data_collection
        WHERE date_from=%s
        AND date_to=%s
        AND pay_scheme_id=%s
        AND update_for NOT IN ('M')
        """
        #print("Executing SQL: ", sql, " with parameters: ", fromdate, todate, payscheme, company_id)
        #with connection.cursor() as cursor:
        #    affected_rows = cursor.execute(sql, (fromdate, todate, payscheme))

        with connection.cursor() as cursor:
            result = cursor.execute(sql, (fromdate, todate, payscheme))
        #print("execute result =", result)
        #print("rowcount =", cursor.rowcount)
        affected_rows = cursor.rowcount


        connection.commit()

        return {
            "success": True,
            "affected_rows": affected_rows
        }

    except Exception as e:

        if connection:
            connection.rollback()

        return {
            "success": False,
            "message": str(e)
        }

    finally:

        if connection:
            connection.close()


if __name__ == "__main__":

    fromdate = sys.argv[1]
    todate = sys.argv[2]
    payscheme = sys.argv[3]
    company_id = sys.argv[4]

    result = main_wages_process_clear(fromdate, todate, payscheme, company_id)

    

    print(json.dumps(result))