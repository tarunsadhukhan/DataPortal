import sys
import json
from datetime import datetime
from db import get_connection


def main_wages_process_drg(fromdate, todate, payscheme, company_id):
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
            CASE
                WHEN (drg.acteff / drg.eff_target) * 100 < 100
                THEN ROUND((drg.acteff / drg.eff_target) * tewas.working_hours, 2)
                ELSE tewas.working_hours
            END AS working_hours_eff,
            CASE
                WHEN (drg.acteff / drg.eff_target) * 100 < 100
                THEN ROUND((drg.acteff / drg.eff_target) * tewas.ot_hours, 2)
                ELSE tewas.ot_hours
            END AS ot_hours_eff,
            %s AS payschm,
            'DRG44' AS updt,
            'PROD' AS updtfr,
            drg.acteff AS act_eff
        FROM EMPMILL12.tbl_ejm_wages_att_summary tewas
        JOIN (
            SELECT
                tewom.eff_code,
                acteff.acteff,
                tate.target_eff AS eff_target,
                tewom.deptcode,
                tewom.occucode
            FROM EMPMILL12.tbl_ejm_wages_occu_mast tewom
            LEFT JOIN (
                SELECT
                    dm.mc_group,
                    SUBSTR(mech_code, 1, 2) AS mcgrp,
                    SUM(ddt.diff_meter) AS diffmeter,
                    SUM(ddt.const_meter / 8 * ddt.wrk_hours) AS tgmeter,
                    SUM(ddt.wrk_hours) AS wrkhrs,
                    ROUND(SUM(ddt.diff_meter) / SUM(ddt.const_meter / 8 * ddt.wrk_hours) * 100, 2) AS acteff,
                    43 AS effcode
                FROM EMPMILL12.daily_drawing_transaction ddt
                LEFT JOIN EMPMILL12.drawing_master dm
                    ON dm.drg_mc_id = ddt.drg_mc_id
                LEFT JOIN vowsls.mechine_master mm
                    ON mm.mechine_id = ddt.drg_mc_id
                WHERE ddt.tran_date BETWEEN %s AND %s
                  AND ddt.company_id = 2
                  AND ddt.diff_meter > 0
                  AND ddt.is_active = 1
                  AND SUBSTR(mech_code, 1, 2) = '25'
                GROUP BY dm.mc_group, SUBSTR(mech_code, 1, 2)
            ) acteff
                ON acteff.effcode = tewom.eff_code
            LEFT JOIN EMPMILL12.tbl_all_trn_eff tate
                ON tate.eff_code = tewom.eff_code
               AND tate.date_from = %s
               AND tate.date_to = %s
               AND tate.dept_id = 3
            WHERE tewom.eff_code = 43
        ) drg
            ON drg.deptcode = tewas.dept_code
           AND drg.occucode = tewas.occu_code
        WHERE tewas.date_from = %s
          AND tewas.date_to = %s
          AND tewas.pay_scheme_id = %s
          AND tewas.is_active = 1
          AND tewas.update_from = 'ATT'
        """

        sql2 = """
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
            CASE
                WHEN (drg.acteff / drg.eff_target) * 100 < 100
                THEN ROUND((drg.acteff / drg.eff_target) * tewas.working_hours, 2)
                ELSE tewas.working_hours
            END AS working_hours_eff,
            CASE
                WHEN (drg.acteff / drg.eff_target) * 100 < 100
                THEN ROUND((drg.acteff / drg.eff_target) * tewas.ot_hours, 2)
                ELSE tewas.ot_hours
            END AS ot_hours_eff,
            %s AS payschm,
            'DRG44' AS updt,
            'PROD' AS updtfr,
            drg.acteff AS act_eff
        FROM EMPMILL12.tbl_ejm_wages_att_summary tewas
        JOIN (
            SELECT
                tewom.eff_code,
                acteff.acteff,
                tate.target_eff AS eff_target,
                tewom.deptcode,
                tewom.occucode
            FROM EMPMILL12.tbl_ejm_wages_occu_mast tewom
            LEFT JOIN (
                SELECT
                    dm.mc_group,
                    SUBSTR(mech_code, 1, 2) AS mcgrp,
                    SUM(ddt.diff_meter) AS diffmeter,
                    SUM(ddt.const_meter / 8 * ddt.wrk_hours) AS tgmeter,
                    SUM(ddt.wrk_hours) AS wrkhrs,
                    ROUND(SUM(ddt.diff_meter) / SUM(ddt.const_meter / 8 * ddt.wrk_hours) * 100, 2) AS acteff,
                    44 AS effcode
                FROM EMPMILL12.daily_drawing_transaction ddt
                LEFT JOIN EMPMILL12.drawing_master dm
                    ON dm.drg_mc_id = ddt.drg_mc_id
                LEFT JOIN vowsls.mechine_master mm
                    ON mm.mechine_id = ddt.drg_mc_id
                WHERE ddt.tran_date BETWEEN %s AND %s
                  AND ddt.company_id = 2
                  AND ddt.diff_meter > 0
                  AND ddt.is_active = 1
                  AND SUBSTR(mech_code, 1, 2) = '29'
                GROUP BY dm.mc_group, SUBSTR(mech_code, 1, 2)
            ) acteff
                ON acteff.effcode = tewom.eff_code
            LEFT JOIN EMPMILL12.tbl_all_trn_eff tate
                ON tate.eff_code = tewom.eff_code
               AND tate.date_from = %s
               AND tate.date_to = %s
               AND tate.dept_id = 3
            WHERE tewom.eff_code = 44
        ) drg
            ON drg.deptcode = tewas.dept_code
           AND drg.occucode = tewas.occu_code
        WHERE tewas.date_from = %s
          AND tewas.date_to = %s
          AND tewas.pay_scheme_id = %s
          AND tewas.is_active = 1
          AND tewas.update_from = 'ATT'
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
                    fromdate,
                    todate,
                    fromdate,
                    todate,
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
                    fromdate,
                    todate,
                    fromdate,
                    todate,
                    fromdate,
                    todate,
                    payscheme
             
                )
            )
            result2 = cursor.rowcount

 
        connection.commit()

        return {
            "success": True,
            "message": "NS processing completed",
            "rows_affected": {
                "drg_43": result1,
                "drg_44": result2,
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
        if len(sys.argv) < 4:
            print(json.dumps({
                "success": False,
                "message": "Usage: python main_wages_process_drg.py fromdate todate payscheme [company_id]"
            }))
            sys.exit(1)

        fromdate = sys.argv[1]
        todate = sys.argv[2]
        payscheme = sys.argv[3]
        company_id = sys.argv[4] if len(sys.argv) > 4 else None

        result = main_wages_process_drg(fromdate, todate, payscheme, company_id)
        print(json.dumps(result))

    except Exception as e:
        print(json.dumps({
            "success": False,
            "message": f"Unhandled Exception: {str(e)}"
        }))