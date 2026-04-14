import sys
import json
from datetime import datetime
from db import get_connection


def main_wages_process_beaming(fromdate, todate, payscheme, company_id):
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
        WITH
        raw_beaming_data AS (
            SELECT
                mm.mechine_id,
                mm.mech_code,
                SUBSTR(bdp.spell, 1, 1) AS shift,
                ewql.wages_code,
                SUM(bdp.no_of_cuts) AS totcuts,
                bdp.tran_date,
                bdp.quality_code
            FROM vowsls.beaming_daily_production bdp
            LEFT JOIN vowsls.mechine_master mm
                ON mm.mechine_id = bdp.beam_mc_no
            LEFT JOIN vowsls.department_master dm
                ON dm.company_id = bdp.company_id
               AND dm.dept_code = '07'
            LEFT JOIN EMPMILL12.tbl_prod_wages_code_link ewql
                ON bdp.quality_code = ewql.prod_code
               AND ewql.dept_id = dm.dept_id
            WHERE bdp.company_id = %s
              AND bdp.tran_date BETWEEN %s AND %s
              AND bdp.is_active = 1
            GROUP BY
                mm.mechine_id,
                mm.mech_code,
                SUBSTR(bdp.spell, 1, 1),
                ewql.wages_code,
                bdp.tran_date,
                bdp.quality_code
        ),

        raw_with_rate AS (
            SELECT
                r.mechine_id,
                r.mech_code,
                r.shift,
                r.wages_code,
                r.totcuts,
                r.tran_date,
                IFNULL(tate.target_eff, 0) AS target_eff,
                IFNULL(tqr.rate, 0) AS rate
            FROM raw_beaming_data r
            LEFT JOIN EMPMILL12.tbl_all_trn_eff tate
                ON tate.qual_code = r.wages_code
               AND tate.date_from = %s
               AND tate.date_to = %s
            LEFT JOIN EMPMILL12.tbl_quality_rate tqr
                ON tqr.qcode = r.wages_code
        ),

        breakdown_hrs AS (
            SELECT
                mechine_id,
                SUBSTR(spell, 1, 1) AS shift,
                SUM(total_hours) AS brkhrs
            FROM EMPMILL12.break_down_entries
            WHERE tran_date BETWEEN %s AND %s
            GROUP BY mechine_id, SUBSTR(spell, 1, 1)
        ),

        available_hrs AS (
            SELECT
                dea.mc_id,
                SUBSTR(da.spell, 1, 1) AS shift,
                SUM(da.working_hours) / 3 AS whrs
            FROM vowsls.daily_attendance da
            LEFT JOIN vowsls.daily_ebmc_attendance dea
                ON dea.daily_atten_id = da.daily_atten_id
            WHERE da.attendance_date BETWEEN %s AND %s
              AND da.worked_department_id = 7
              AND da.company_id = %s
              AND da.worked_designation_id IN (501, 67)
            GROUP BY dea.mc_id, SUBSTR(da.spell, 1, 1)
        ),

        mc_shift_summary AS (
            SELECT
                r.mechine_id,
                r.mech_code,
                r.shift,
                MAX(r.target_eff) AS target_eff,
                IFNULL(MAX(ah.whrs), 0) AS whrs,
                IFNULL(MAX(bh.brkhrs), 0) AS brkhrs,
                IFNULL(MAX(ah.whrs), 0) - IFNULL(MAX(bh.brkhrs), 0) AS wkhrs,
                SUM(r.totcuts) AS total_qty,
                SUM(r.totcuts * r.rate) AS total_amount
            FROM raw_with_rate r
            LEFT JOIN breakdown_hrs bh
                ON bh.mechine_id = r.mechine_id
               AND bh.shift = r.shift
            LEFT JOIN available_hrs ah
                ON ah.mc_id = r.mechine_id
               AND ah.shift = r.shift
            GROUP BY r.mechine_id, r.mech_code, r.shift
        ),

        mc_shift_efficiency AS (
            SELECT
                mechine_id,
                mech_code,
                shift,
                target_eff,
                whrs,
                wkhrs,
                total_qty,
                total_amount,
                CASE
                    WHEN wkhrs > 0
                    THEN ROUND((total_amount / wkhrs) / 3, 4)
                    ELSE 0
                END AS rate_per_hour,
                CASE
                    WHEN target_eff > 0 AND wkhrs > 0
                    THEN ROUND(total_qty / ((target_eff / 8) * wkhrs) * 100, 2)
                    ELSE 0
                END AS act_eff
            FROM mc_shift_summary
        )

        SELECT
            %s AS date_from,
            %s AS date_to,
            tewas.eb_id,
            tewas.dept_code,
            tewas.occu_code,
            tewas.shift,
            'P' AS t_p,
            tewas.working_hours,
            tewas.ot_hours,

            CASE
                WHEN tewas.working_hours > 0 AND mse.act_eff > 0 AND mse.act_eff <= 100
                THEN ROUND(tewas.working_hours / 100 * mse.act_eff, 2)
                ELSE tewas.working_hours
            END AS working_hours_eff,

            CASE
                WHEN tewas.ot_hours > 0 AND mse.act_eff > 0 AND mse.act_eff <= 100
                THEN ROUND(tewas.ot_hours / 100 * mse.act_eff, 2)
                ELSE tewas.ot_hours
            END AS ot_hours_eff,

            tewas.pay_scheme_id,
            'WDGWT' AS update_for,
            'PROD' AS updt_from,

            ROUND(mse.rate_per_hour * tewas.working_hours, 2) AS prod_basic,

            CASE
                WHEN tewas.pay_scheme_id = 151 AND mse.act_eff < 100
                THEN ROUND((tewas.working_hours * twor.f_b_rate) / 3 * 2, 2)
                     + ROUND((twor.f_b_rate * tewas.working_hours / 3) * act_eff / 100, 2)

                WHEN tewas.pay_scheme_id = 151 AND mse.act_eff >= 100
                THEN ROUND(tewas.working_hours * twor.f_b_rate, 2)

                WHEN tewas.pay_scheme_id = 125 AND mse.act_eff < 100
                THEN ROUND((tewas.working_hours * 13.5) / 3 * 2, 2)
                     + ROUND(
                        ((mse.rate_per_hour * 8) / (mse.act_eff / 100))
                        * ((tewas.working_hours * 13.5) / 3),
                        2
                     )

                WHEN tewas.pay_scheme_id = 125 AND mse.act_eff >= 100
                THEN ROUND(tewas.working_hours * 13.5, 2)

                ELSE 0
            END AS time_basic,

            act_eff
        FROM EMPMILL12.tbl_ejm_wages_att_summary tewas
        LEFT JOIN mc_shift_efficiency mse
            ON mse.mech_code = tewas.mc_nos
           AND mse.shift = tewas.shift
        LEFT JOIN EMPMILL12.tbl_wages_occu_rate twor
            ON twor.dept_code = tewas.dept_code
           AND twor.occu_code = tewas.occu_code
        WHERE tewas.dept_code IN ('07')
          AND tewas.occu_code IN ('01', '02')
          AND tewas.pay_scheme_id = %s
          AND tewas.update_from = 'ATT'
          AND tewas.is_active = 1
          AND tewas.date_from = %s
          AND tewas.date_to = %s
        """

        params = (
            company_id, fromdate, todate,       # raw_beaming_data
            fromdate, todate,                   # raw_with_rate
            fromdate, todate,                   # breakdown_hrs
            fromdate, todate, company_id,       # available_hrs
            fromdate, todate,                   # final select fixed dates
            payscheme, fromdate, todate         # final where
        )

        with connection.cursor() as cursor:
            affected_rows = cursor.execute(sql, params)

        connection.commit()

        return {
            "success": True,
            "message": "Beaming processing completed successfully",
            "affected_rows": affected_rows,
            "status": "COMPLETED"
        }

    except ValueError:
        if connection:
            connection.rollback()
        return {
            "success": False,
            "message": "Invalid date format",
            "status": "FAILED"
        }

    except Exception as e:
        if connection:
            connection.rollback()
        return {
            "success": False,
            "message": f"Error: {str(e)}",
            "status": "FAILED",
            "action": "All changes rolled back - Database is safe"
        }

    finally:
        if connection:
            connection.close()


if __name__ == "__main__":
    try:
        if len(sys.argv) < 5:
            print(json.dumps({
                "success": False,
                "message": "Usage: python main_wages_process_beaming.py fromdate todate payscheme company_id"
            }))
            sys.exit(1)

        fromdate = sys.argv[1]
        todate = sys.argv[2]
        payscheme = sys.argv[3]
        company_id = sys.argv[4]

        result = main_wages_process_beaming(fromdate, todate, payscheme, company_id)
        print(json.dumps(result))

    except Exception as e:
        print(json.dumps({
            "success": False,
            "message": f"Unhandled Exception: {str(e)}",
            "status": "FAILED"
        }))