import sys
import json
from datetime import datetime
from db import get_connection


def main_wages_process_sprd(fromdate, todate, payscheme, company_id=None):
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
            sprd.df,
            sprd.dt,
            tewas.eb_id,
            tewas.dept_code,
            tewas.occu_code,
            tewas.shift,
            tewas.t_p,
            tewas.working_hours,
            tewas.ot_hours,
            CASE
                WHEN sprd.acteff < 100 THEN ROUND(sprd.acteff / 100 * tewas.working_hours, 2)
                ELSE tewas.working_hours
            END AS working_hours_eff,
            CASE
                WHEN sprd.acteff < 100 THEN ROUND(sprd.acteff / 100 * tewas.ot_hours, 2)
                ELSE tewas.ot_hours
            END AS ot_hours_eff,
            %s AS payschm,
            'SPRDI' AS updt,
            'PROD' AS updtfr,
            sprd.acteff AS act_eff
        FROM EMPMILL12.tbl_ejm_wages_att_summary tewas
        LEFT JOIN
        (
            SELECT
                df,
                dt,
                eb_id,
                tewom.deptcode,
                tewom.occucode,
                shift,
                sprd.eff_code,
                SUM(weight) AS weight,
                SUM(tate.target_eff / 8 * whrs) AS tweight,
                ROUND(SUM(weight) / SUM(tate.target_eff / 8 * whrs) * 100, 2) AS acteff,
                100 AS tareff
            FROM
            (
                SELECT
                    sle.*,
                    sle.hours - IFNULL(bde.total_hours, 0) AS whrs,
                    CASE
                        WHEN mech_code IN ('12001', '12002') THEN sle.production * 102
                        ELSE sle.production * 58
                    END AS weight
                FROM
                (
                    SELECT
                        tran_date,
                        spell,
                        %s AS df,
                        %s AS dt,
                        SUBSTR(spell, 1, 1) AS shift,
                        1 AS eff_code,
                        feeder_id AS eb_id,
                        sle.hours,
                        sle.production,
                        sle.prod_type,
                        sle.mechine_id,
                        sle.is_active
                    FROM EMPMILL12.spreader_lapping_entries sle

                    UNION ALL

                    SELECT
                        tran_date,
                        spell,
                        %s AS df,
                        %s AS dt,
                        SUBSTR(spell, 1, 1) AS shift,
                        1 AS eff_code,
                        receiver_id AS eb_id,
                        sle.hours,
                        sle.production,
                        sle.prod_type,
                        sle.mechine_id,
                        sle.is_active
                    FROM EMPMILL12.spreader_lapping_entries sle
                ) sle
                LEFT JOIN EMPMILL12.break_down_entries bde
                    ON sle.mechine_id = bde.mechine_id
                   AND sle.tran_date = bde.tran_date
                LEFT JOIN vowsls.mechine_master mm
                    ON mm.mechine_id = sle.mechine_id
                   AND sle.spell = bde.spell
                WHERE sle.prod_type = 0
                  AND sle.tran_date BETWEEN %s AND %s
                  AND sle.is_active = 1
            ) sprd
            LEFT JOIN EMPMILL12.tbl_all_trn_eff tate
                ON tate.date_from = sprd.df
               AND tate.date_to = sprd.dt
               AND tate.eff_code = sprd.eff_code
            LEFT JOIN EMPMILL12.tbl_ejm_wages_occu_mast tewom
                ON tewom.eff_code = sprd.eff_code
               AND tewom.effcheck = 'CI'
            GROUP BY df, dt, eff_code, shift, eb_id, tewom.deptcode, tewom.occucode
        ) sprd
            ON tewas.dept_code = sprd.deptcode
           AND tewas.occu_code = sprd.occucode
           AND sprd.shift = tewas.shift
           AND tewas.eb_id = sprd.eb_id
        WHERE tewas.pay_scheme_id = %s
          AND tewas.update_from = 'ATT'
          AND sprd.acteff IS NOT NULL
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
            sprd.df,
            sprd.dt,
            tewas.eb_id,
            tewas.dept_code,
            tewas.occu_code,
            tewas.shift,
            tewas.t_p,
            tewas.working_hours,
            tewas.ot_hours,
            CASE
                WHEN sprd.acteff < 100 THEN ROUND(sprd.acteff / 100 * tewas.working_hours, 2)
                ELSE tewas.working_hours
            END AS working_hours_eff,
            CASE
                WHEN sprd.acteff < 100 THEN ROUND(sprd.acteff / 100 * tewas.ot_hours, 2)
                ELSE tewas.ot_hours
            END AS ot_hours_eff,
            %s AS payschm,
            'SPRDG' AS updt,
            'PROD' AS updtfr,
            sprd.acteff AS act_eff
        FROM EMPMILL12.tbl_ejm_wages_att_summary tewas
        LEFT JOIN
        (
            SELECT
                df,
                dt,
                tewom.deptcode,
                tewom.occucode,
                sprd.eff_code,
                SUM(weight) AS weight,
                SUM(tate.target_eff / 8 * whrs) AS tweight,
                ROUND(SUM(weight) / SUM(tate.target_eff / 8 * whrs) * 100, 2) AS acteff,
                100 AS tareff
            FROM
            (
                SELECT
                    sle.*,
                    sle.hours - IFNULL(bde.total_hours, 0) AS whrs,
                    CASE
                        WHEN mech_code IN ('12001', '12002') THEN sle.production * 102
                        ELSE sle.production * 58
                    END AS weight
                FROM
                (
                    SELECT
                        tran_date,
                        spell,
                        %s AS df,
                        %s AS dt,
                        SUBSTR(spell, 1, 1) AS shift,
                        1 AS eff_code,
                        feeder_id AS eb_id,
                        sle.hours,
                        sle.production,
                        sle.prod_type,
                        sle.mechine_id,
                        sle.is_active
                    FROM EMPMILL12.spreader_lapping_entries sle

                    UNION ALL

                    SELECT
                        tran_date,
                        spell,
                        %s AS df,
                        %s AS dt,
                        SUBSTR(spell, 1, 1) AS shift,
                        1 AS eff_code,
                        receiver_id AS eb_id,
                        sle.hours,
                        sle.production,
                        sle.prod_type,
                        sle.mechine_id,
                        sle.is_active
                    FROM EMPMILL12.spreader_lapping_entries sle
                ) sle
                LEFT JOIN EMPMILL12.break_down_entries bde
                    ON sle.mechine_id = bde.mechine_id
                   AND sle.tran_date = bde.tran_date
                LEFT JOIN vowsls.mechine_master mm
                    ON mm.mechine_id = sle.mechine_id
                   AND sle.spell = bde.spell
                WHERE sle.prod_type = 0
                  AND sle.tran_date BETWEEN %s AND %s
                  AND sle.is_active = 1
            ) sprd
            LEFT JOIN EMPMILL12.tbl_all_trn_eff tate
                ON tate.date_from = sprd.df
               AND tate.date_to = sprd.dt
               AND tate.eff_code = sprd.eff_code
            LEFT JOIN EMPMILL12.tbl_ejm_wages_occu_mast tewom
                ON tewom.eff_code = sprd.eff_code
               AND tewom.effcheck = 'CG'
            GROUP BY df, dt, eff_code, tewom.deptcode, tewom.occucode
        ) sprd
            ON tewas.dept_code = sprd.deptcode
           AND tewas.occu_code = sprd.occucode
        WHERE tewas.pay_scheme_id = %s
          AND tewas.update_from = 'ATT'
          AND sprd.acteff IS NOT NULL
        """

        with connection.cursor() as cursor:
            cursor.execute(
                sql1,
                (
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
                "sprdci": result1,
                "sprdcg": result2,
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
                "message": "Usage: python main_wages_process_sprd.py fromdate todate payscheme [company_id]"
            }))
            sys.exit(1)

        fromdate = sys.argv[1]
        todate = sys.argv[2]
        payscheme = sys.argv[3]
        company_id = sys.argv[4] if len(sys.argv) > 4 else None

        result = main_wages_process_sprd(fromdate, todate, payscheme, company_id)
        print(json.dumps(result))

    except Exception as e:
        print(json.dumps({
            "success": False,
            "message": f"Unhandled Exception: {str(e)}"
        }))