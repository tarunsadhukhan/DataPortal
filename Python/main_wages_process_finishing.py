import sys
import json
from datetime import datetime
from db import get_connection


def main_wages_process_finishing(fromdate, todate, payscheme, company_id):
    connection = None

    try:
        datetime.strptime(fromdate, "%Y-%m-%d")
        datetime.strptime(todate, "%Y-%m-%d")

        connection = get_connection()

        with connection.cursor() as cursor:
            cursor.execute("START TRANSACTION")

            # -----------------------------
            # 1. HEMM operator
            # -----------------------------
            sql1 = """
            INSERT INTO EMPMILL12.tbl_ejm_wages_data_collection
            (
                date_from, date_to, eb_id, dept_code, occu_code, shift, t_p, working_hours,
                ot_hours, working_hours_eff, ot_hours_eff, pay_scheme_id, update_for,
                updt_from, prod_basic, time_basic, act_eff
            )
            WITH raw_hemm_data AS (
                SELECT
                    fe.company_id,
                    SUBSTR(fe.entry_date, 1, 10) trandate,
                    spell,
                    tpwcl.wages_code,
                    fe.eb_no,
                    fe.production,
                    tate.target_eff,
                    ptm.process_code
                FROM vowsls.finishing_entries fe
                LEFT JOIN vowsls.process_type_master ptm
                    ON fe.work_type = ptm.process_type_id
                LEFT JOIN EMPMILL12.tbl_prod_wages_code_link tpwcl
                    ON tpwcl.prod_code = ptm.process_code
                   AND tpwcl.dept_id = 10
                LEFT JOIN EMPMILL12.tbl_all_trn_eff tate
                    ON tate.qual_code = tpwcl.wages_code
                   AND tate.date_from = %s
                   AND tate.date_to = %s
                WHERE SUBSTR(fe.entry_date, 1, 10) BETWEEN %s AND %s
                  AND SUBSTR(ptm.process_code, 1, 2) = '10'
            ),
            raw_hemmatt_data AS (
                SELECT
                    da.company_id,
                    eb_id,
                    da.eb_no,
                    da.attendance_date,
                    da.spell,
                    da.working_hours - da.idle_hours wkhrs,
                    dm.dept_code,
                    omn.OCCU_CODE,
                    rhd.wages_code,
                    production,
                    rhd.target_eff / 8 * (da.working_hours - da.idle_hours) targetprod
                FROM vowsls.daily_attendance da
                LEFT JOIN department_master dm
                    ON da.worked_department_id = dm.dept_id
                LEFT JOIN EMPMILL12.OCCUPATION_MASTER_NORMS omn
                    ON omn.desig_id = da.worked_designation_id
                LEFT JOIN raw_hemm_data rhd
                    ON da.eb_no = rhd.eb_no
                   AND da.company_id = rhd.company_id
                   AND da.attendance_date = rhd.trandate
                   AND da.spell = rhd.spell
                WHERE da.attendance_date BETWEEN %s AND %s
                  AND dept_code = '10'
                  AND omn.OCCU_CODE = '02'
            ),
            eff_hemm_data AS (
                SELECT
                    eb_id,
                    SUBSTR(spell, 1, 1) shift,
                    rhd.dept_code,
                    rhd.occu_code,
                    SUM(production) prod,
                    SUM(targetprod) targetprod,
                    SUM(tqr.rate / 25 * rhd.production) totamt,
                    ROUND(SUM(production) / SUM(targetprod) * 100, 2) acteff,
                    100 tareff
                FROM raw_hemmatt_data rhd
                LEFT JOIN EMPMILL12.tbl_quality_rate tqr
                    ON tqr.qcode = rhd.wages_code
                   AND tqr.dept_code = rhd.dept_code
                GROUP BY eb_id, SUBSTR(spell, 1, 1), dept_code, occu_code
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
                CASE
                    WHEN IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0
                    THEN ROUND((ehd.acteff / 100) * tewas.working_hours, 2)
                    ELSE tewas.working_hours
                END AS working_hours_eff,
                CASE
                    WHEN IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0
                    THEN ROUND((ehd.acteff / 100) * tewas.ot_hours, 2)
                    ELSE tewas.ot_hours
                END AS ot_hours_eff,
                %s AS payschm,
                'HEMM' AS updt,
                'PROD' AS updtfr,
                CASE
                    WHEN IFNULL(ehd.acteff, 0) > 0 THEN ROUND(ehd.totamt / NULLIF(ehd.acteff, 0) * 100, 2)
                    ELSE 0
                END AS prdbas,
                CASE
                    WHEN tewas.pay_scheme_id = 125 AND IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0
                    THEN ROUND(((tewas.working_hours * 13.5) / 3 * 2) + (((tewas.working_hours * 13.5) / 3) * ehd.acteff / 100), 2)
                    WHEN tewas.pay_scheme_id = 125 AND IFNULL(ehd.acteff, 0) >= 100
                    THEN ROUND((tewas.working_hours * 13.5), 2)
                    WHEN tewas.pay_scheme_id = 151 AND IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0
                    THEN ROUND((tewas.working_hours * twor.f_b_rate) / 3 * 2 + ((tewas.working_hours * twor.f_b_rate) / 3) * ehd.acteff / 100, 2)
                    WHEN tewas.pay_scheme_id = 151 AND IFNULL(ehd.acteff, 0) >= 100
                    THEN ROUND((tewas.working_hours * twor.f_b_rate), 2)
                    ELSE 0
                END AS time_basic,
                ehd.acteff AS act_eff
            FROM EMPMILL12.tbl_ejm_wages_att_summary tewas
            LEFT JOIN EMPMILL12.tbl_wages_occu_rate twor
                ON tewas.dept_code = twor.dept_code
               AND tewas.occu_code = twor.occu_code
            LEFT JOIN eff_hemm_data ehd
                ON tewas.eb_id = ehd.eb_id
               AND tewas.shift = ehd.shift
               AND tewas.dept_code = ehd.dept_code
               AND tewas.occu_code = ehd.occu_code
            WHERE tewas.date_from = %s
              AND tewas.date_to = %s
              AND tewas.update_from = 'ATT'
              AND tewas.dept_code = '10'
              AND tewas.occu_code = '02'
              AND tewas.pay_scheme_id = %s
              AND tewas.is_active = 1
            """
            r1 = cursor.execute(sql1, (
                fromdate, todate, fromdate, todate,
                fromdate, todate,
                fromdate, todate, payscheme,
                fromdate, todate, payscheme
            ))

            # -----------------------------
            # 2. HERA operator
            # -----------------------------
            sql2 = """
            INSERT INTO EMPMILL12.tbl_ejm_wages_data_collection
            (
                date_from, date_to, eb_id, dept_code, occu_code, shift, t_p, working_hours,
                ot_hours, working_hours_eff, ot_hours_eff, pay_scheme_id, update_for,
                updt_from, prod_basic, time_basic, act_eff
            )
            WITH raw_hera_data AS (
                SELECT
                    fe.company_id,
                    SUBSTR(fe.entry_date, 1, 10) trandate,
                    spell,
                    tpwcl.wages_code,
                    fe.eb_no,
                    fe.production,
                    tate.target_eff,
                    ptm.process_code
                FROM vowsls.finishing_entries fe
                LEFT JOIN vowsls.process_type_master ptm
                    ON fe.work_type = ptm.process_type_id
                LEFT JOIN EMPMILL12.tbl_prod_wages_code_link tpwcl
                    ON tpwcl.prod_code = ptm.process_code
                   AND tpwcl.dept_id = 10
                LEFT JOIN EMPMILL12.tbl_all_trn_eff tate
                    ON tate.qual_code = tpwcl.wages_code
                   AND tate.date_from = %s
                   AND tate.date_to = %s
                WHERE SUBSTR(fe.entry_date, 1, 10) BETWEEN %s AND %s
                  AND SUBSTR(ptm.process_code, 1, 2) = '20'
            ),
            raw_heraatt_data AS (
                SELECT
                    da.company_id,
                    eb_id,
                    da.eb_no,
                    da.attendance_date,
                    da.spell,
                    da.working_hours - da.idle_hours wkhrs,
                    dm.dept_code,
                    omn.OCCU_CODE,
                    rhd.wages_code,
                    production,
                    rhd.target_eff / 8 * (da.working_hours - da.idle_hours) targetprod
                FROM vowsls.daily_attendance da
                LEFT JOIN department_master dm
                    ON da.worked_department_id = dm.dept_id
                LEFT JOIN EMPMILL12.OCCUPATION_MASTER_NORMS omn
                    ON omn.desig_id = da.worked_designation_id
                LEFT JOIN raw_hera_data rhd
                    ON da.eb_no = rhd.eb_no
                   AND da.company_id = rhd.company_id
                   AND da.attendance_date = rhd.trandate
                   AND da.spell = rhd.spell
                WHERE da.attendance_date BETWEEN %s AND %s
                  AND dept_code = '10'
                  AND omn.OCCU_CODE = '01'
            ),
            eff_hera_data AS (
                SELECT
                    eb_id,
                    SUBSTR(spell, 1, 1) shift,
                    rhd.dept_code,
                    rhd.occu_code,
                    SUM(production) prod,
                    SUM(targetprod) targetprod,
                    SUM(tqr.rate / 25 * rhd.production) totamt,
                    ROUND(SUM(production) / SUM(targetprod) * 100, 2) acteff,
                    100 tareff
                FROM raw_heraatt_data rhd
                LEFT JOIN EMPMILL12.tbl_quality_rate tqr
                    ON tqr.qcode = rhd.wages_code
                   AND tqr.dept_code = rhd.dept_code
                GROUP BY eb_id, SUBSTR(spell, 1, 1), dept_code, occu_code
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
                CASE
                    WHEN IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0
                    THEN ROUND((ehd.acteff / 100) * tewas.working_hours, 2)
                    ELSE tewas.working_hours
                END AS working_hours_eff,
                CASE
                    WHEN IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0
                    THEN ROUND((ehd.acteff / 100) * tewas.ot_hours, 2)
                    ELSE tewas.ot_hours
                END AS ot_hours_eff,
                %s AS payschm,
                'HEMM' AS updt,
                'PROD' AS updtfr,
                CASE
                    WHEN IFNULL(ehd.acteff, 0) > 0 THEN ROUND(ehd.totamt / NULLIF(ehd.acteff, 0) * 100, 2)
                    ELSE 0
                END AS prdbas,
                CASE
                    WHEN tewas.pay_scheme_id = 125 AND IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0
                    THEN ROUND(((tewas.working_hours * 13.5) / 3 * 2) + (((tewas.working_hours * 13.5) / 3) * ehd.acteff / 100), 2)
                    WHEN tewas.pay_scheme_id = 125 AND IFNULL(ehd.acteff, 0) >= 100
                    THEN ROUND((tewas.working_hours * 13.5), 2)
                    WHEN tewas.pay_scheme_id = 151 AND IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0
                    THEN ROUND((tewas.working_hours * twor.f_b_rate) / 3 * 2 + ((tewas.working_hours * twor.f_b_rate) / 3) * ehd.acteff / 100, 2)
                    WHEN tewas.pay_scheme_id = 151 AND IFNULL(ehd.acteff, 0) >= 100
                    THEN ROUND((tewas.working_hours * twor.f_b_rate), 2)
                    ELSE 0
                END AS time_basic,
                ehd.acteff AS act_eff
            FROM EMPMILL12.tbl_ejm_wages_att_summary tewas
            LEFT JOIN EMPMILL12.tbl_wages_occu_rate twor
                ON tewas.dept_code = twor.dept_code
               AND tewas.occu_code = twor.occu_code
            LEFT JOIN eff_hera_data ehd
                ON tewas.eb_id = ehd.eb_id
               AND tewas.shift = ehd.shift
               AND tewas.dept_code = ehd.dept_code
               AND tewas.occu_code = ehd.occu_code
            WHERE tewas.date_from = %s
              AND tewas.date_to = %s
              AND tewas.update_from = 'ATT'
              AND tewas.dept_code = '10'
              AND tewas.occu_code = '01'
              AND tewas.pay_scheme_id = %s
              AND tewas.is_active = 1
            """
            r2 = cursor.execute(sql2, (
                fromdate, todate, fromdate, todate,
                fromdate, todate,
                fromdate, todate, payscheme,
                fromdate, todate, payscheme
            ))

            # -----------------------------
            # 3. Hand sewer
            # -----------------------------
            sql3 = """
            INSERT INTO EMPMILL12.tbl_ejm_wages_data_collection
            (
                date_from, date_to, eb_id, dept_code, occu_code, shift, t_p, working_hours,
                ot_hours, working_hours_eff, ot_hours_eff, pay_scheme_id, update_for,
                updt_from, prod_basic, time_basic, act_eff
            )
            WITH raw_hera_data AS (
                SELECT
                    fe.company_id,
                    SUBSTR(fe.entry_date, 1, 10) trandate,
                    spell,
                    tpwcl.wages_code,
                    fe.eb_no,
                    fe.production,
                    tate.target_eff,
                    ptm.process_code
                FROM vowsls.finishing_entries fe
                LEFT JOIN vowsls.process_type_master ptm
                    ON fe.work_type = ptm.process_type_id
                LEFT JOIN EMPMILL12.tbl_prod_wages_code_link tpwcl
                    ON tpwcl.prod_code = ptm.process_code
                   AND tpwcl.dept_id = 10
                LEFT JOIN EMPMILL12.tbl_all_trn_eff tate
                    ON tate.qual_code = tpwcl.wages_code
                   AND tate.date_from = %s
                   AND tate.date_to = %s
                   AND tate.dept_id = 10
                WHERE SUBSTR(fe.entry_date, 1, 10) BETWEEN %s AND %s
                  AND SUBSTR(ptm.process_code, 1, 2) = '77'
                  AND fe.is_active = 1
            ),
            raw_heraatt_data AS (
                SELECT
                    da.company_id,
                    eb_id,
                    da.eb_no,
                    da.attendance_date,
                    da.spell,
                    da.working_hours - da.idle_hours wkhrs,
                    dm.dept_code,
                    omn.OCCU_CODE,
                    rhd.wages_code,
                    production,
                    rhd.target_eff / 8 * (da.working_hours - da.idle_hours) targetprod
                FROM vowsls.daily_attendance da
                LEFT JOIN department_master dm
                    ON da.worked_department_id = dm.dept_id
                LEFT JOIN EMPMILL12.OCCUPATION_MASTER_NORMS omn
                    ON omn.desig_id = da.worked_designation_id
                LEFT JOIN raw_hera_data rhd
                    ON da.eb_no = rhd.eb_no
                   AND da.company_id = rhd.company_id
                   AND da.attendance_date = rhd.trandate
                   AND da.spell = rhd.spell
                WHERE da.attendance_date BETWEEN %s AND %s
                  AND dept_code = '10'
                  AND omn.OCCU_CODE = '04'
                  AND da.is_active = 1
            ),
            eff_hera_data AS (
                SELECT
                    eb_id,
                    SUBSTR(spell, 1, 1) shift,
                    rhd.dept_code,
                    rhd.occu_code,
                    SUM(production) prod,
                    SUM(targetprod) targetprod,
                    SUM(tqr.rate * rhd.production) totamt,
                    ROUND(SUM(production) / SUM(targetprod) * 100, 2) acteff,
                    100 tareff
                FROM raw_heraatt_data rhd
                LEFT JOIN EMPMILL12.tbl_quality_rate tqr
                    ON tqr.qcode = rhd.wages_code
                   AND tqr.dept_code = rhd.dept_code
                GROUP BY eb_id, SUBSTR(spell, 1, 1), dept_code, occu_code
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
                CASE
                    WHEN IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0
                    THEN ROUND((ehd.acteff / 100) * tewas.working_hours, 2)
                    ELSE tewas.working_hours
                END AS working_hours_eff,
                CASE
                    WHEN IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0
                    THEN ROUND((ehd.acteff / 100) * tewas.ot_hours, 2)
                    ELSE tewas.ot_hours
                END AS ot_hours_eff,
                %s AS payschm,
                'HEMM' AS updt,
                'PROD' AS updtfr,
                CASE
                    WHEN IFNULL(ehd.acteff, 0) > 0 THEN ROUND(ehd.totamt / NULLIF(ehd.acteff, 0) * 100, 2)
                    ELSE 0
                END AS prdbas,
                CASE
                    WHEN tewas.pay_scheme_id = 125 AND IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0
                    THEN ROUND(((tewas.working_hours * 13.5) / 3 * 2) + (((tewas.working_hours * 13.5) / 3) * ehd.acteff / 100), 2)
                    WHEN tewas.pay_scheme_id = 125 AND IFNULL(ehd.acteff, 0) >= 100
                    THEN ROUND((tewas.working_hours * 13.5), 2)
                    WHEN tewas.pay_scheme_id = 151 AND IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0
                    THEN ROUND((tewas.working_hours * twor.f_b_rate) / 3 * 2 + ((tewas.working_hours * twor.f_b_rate) / 3) * ehd.acteff / 100, 2)
                    WHEN tewas.pay_scheme_id = 151 AND IFNULL(ehd.acteff, 0) >= 100
                    THEN ROUND((tewas.working_hours * twor.f_b_rate), 2)
                    ELSE 0
                END AS time_basic,
                ehd.acteff
            FROM EMPMILL12.tbl_ejm_wages_att_summary tewas
            LEFT JOIN EMPMILL12.tbl_wages_occu_rate twor
                ON tewas.dept_code = twor.dept_code
               AND tewas.occu_code = twor.occu_code
            LEFT JOIN eff_hera_data ehd
                ON tewas.eb_id = ehd.eb_id
               AND tewas.shift = ehd.shift
               AND tewas.dept_code = ehd.dept_code
               AND tewas.occu_code = ehd.occu_code
            WHERE tewas.date_from = %s
              AND tewas.date_to = %s
              AND tewas.update_from = 'ATT'
              AND tewas.dept_code = '10'
              AND tewas.occu_code = '04'
              AND tewas.pay_scheme_id = %s
              AND tewas.is_active = 1
            """
            r3 = cursor.execute(sql3, (
                fromdate, todate, fromdate, todate,
                fromdate, todate,
                fromdate, todate, payscheme,
                fromdate, todate, payscheme
            ))

            # -----------------------------
            # 4. Temp tables for heracle helper
            # -----------------------------
            cursor.execute("DROP TEMPORARY TABLE IF EXISTS raw_hera_data")
            cursor.execute("DROP TEMPORARY TABLE IF EXISTS raw_heraatt_data")

            sql4a = """
            CREATE TEMPORARY TABLE raw_hera_data AS
            SELECT
                fe.company_id,
                SUBSTR(fe.entry_date, 1, 10) trandate,
                spell,
                tpwcl.wages_code,
                fe.eb_no,
                fe.production,
                tate.target_eff,
                ptm.process_code
            FROM vowsls.finishing_entries fe
            LEFT JOIN vowsls.process_type_master ptm
                ON fe.work_type = ptm.process_type_id
            LEFT JOIN EMPMILL12.tbl_prod_wages_code_link tpwcl
                ON tpwcl.prod_code = ptm.process_code
               AND tpwcl.dept_id = 10
            LEFT JOIN EMPMILL12.tbl_all_trn_eff tate
                ON tate.qual_code = tpwcl.wages_code
               AND tate.date_from = %s
               AND tate.date_to = %s
            WHERE SUBSTR(fe.entry_date, 1, 10) BETWEEN %s AND %s
              AND SUBSTR(ptm.process_code, 1, 2) = '20'
              AND fe.is_active = 1
            """
            cursor.execute(sql4a, (fromdate, todate, fromdate, todate))

            sql4b = """
            CREATE TEMPORARY TABLE raw_heraatt_data AS
            SELECT
                da.company_id,
                da.eb_id,
                da.eb_no,
                da.attendance_date trandate,
                da.spell,
                da.working_hours - da.idle_hours wkhrs,
                dm.dept_code,
                omn.OCCU_CODE,
                rhd.wages_code,
                production,
                rhd.target_eff / 8 * (da.working_hours - da.idle_hours) targetprod,
                dea.mc_id,
                rhd.target_eff
            FROM vowsls.daily_attendance da
            LEFT JOIN vowsls.daily_ebmc_attendance dea
                ON da.daily_atten_id = dea.daily_atten_id
               AND dea.is_active = 1
            LEFT JOIN department_master dm
                ON da.worked_department_id = dm.dept_id
            LEFT JOIN EMPMILL12.OCCUPATION_MASTER_NORMS omn
                ON omn.desig_id = da.worked_designation_id
            LEFT JOIN raw_hera_data rhd
                ON da.eb_no = rhd.eb_no
               AND da.company_id = rhd.company_id
               AND da.attendance_date = rhd.trandate
               AND da.spell = rhd.spell
            WHERE da.attendance_date BETWEEN %s AND %s
              AND dept_code = '10'
              AND omn.OCCU_CODE = '01'
              AND da.is_active = 1
            """
            cursor.execute(sql4b, (fromdate, todate))

            # -----------------------------
            # 5. Heracle helper (occu 14)
            # -----------------------------
            sql5 = """
            INSERT INTO EMPMILL12.tbl_ejm_wages_data_collection
            (
                date_from, date_to, eb_id, dept_code, occu_code, shift, t_p, working_hours,
                ot_hours, working_hours_eff, ot_hours_eff, pay_scheme_id, update_for,
                updt_from, prod_basic, time_basic, act_eff
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
                CASE
                    WHEN IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0
                    THEN ROUND((ehd.acteff / 100) * tewas.working_hours, 2)
                    ELSE tewas.working_hours
                END AS working_hours_eff,
                CASE
                    WHEN IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0
                    THEN ROUND((ehd.acteff / 100) * tewas.ot_hours, 2)
                    ELSE tewas.ot_hours
                END AS ot_hours_eff,
                %s AS payscheme,
                'HEMM' AS updt,
                'PROD' AS updtfr,
                CASE
                    WHEN IFNULL(ehd.acteff, 0) > 0 THEN ROUND(ehd.totamt / NULLIF(ehd.acteff, 0) * 100, 2)
                    ELSE 0
                END AS prdbas,
                CASE
                    WHEN tewas.pay_scheme_id = 125 AND IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0
                    THEN ROUND(((tewas.working_hours * 13.5) / 3 * 2) + (((tewas.working_hours * 13.5) / 3) * ehd.acteff / 100), 2)
                    WHEN tewas.pay_scheme_id = 125 AND IFNULL(ehd.acteff, 0) >= 100
                    THEN ROUND((tewas.working_hours * 13.5), 2)
                    WHEN tewas.pay_scheme_id = 151 AND IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0
                    THEN ROUND((tewas.working_hours * twor.f_b_rate) / 3 * 2 + ((tewas.working_hours * twor.f_b_rate) / 3) * ehd.acteff / 100, 2)
                    WHEN tewas.pay_scheme_id = 151 AND IFNULL(ehd.acteff, 0) >= 100
                    THEN ROUND((tewas.working_hours * twor.f_b_rate), 2)
                    ELSE 0
                END AS time_basic,
                ehd.acteff AS act_eff
            FROM EMPMILL12.tbl_ejm_wages_att_summary tewas
            LEFT JOIN EMPMILL12.tbl_wages_occu_rate twor
                ON tewas.dept_code = twor.dept_code
               AND tewas.occu_code = twor.occu_code
            LEFT JOIN (
                SELECT
                    da.eb_id,
                    SUBSTR(da.spell, 1, 1) shift,
                    dm.dept_code,
                    omn.OCCU_CODE,
                    SUM(production) production,
                    SUM(rhd.target_eff / 8 * (da.working_hours - da.idle_hours)) targetprod,
                    SUM(tqr.rate * rhd.wages_code) totamt,
                    ROUND(SUM(production) / SUM(rhd.target_eff / 8 * (da.working_hours - da.idle_hours)) * 100, 2) acteff,
                    100 targeteff
                FROM vowsls.daily_attendance da
                LEFT JOIN vowsls.daily_ebmc_attendance dea
                    ON da.daily_atten_id = dea.daily_atten_id
                   AND dea.is_active = 1
                LEFT JOIN department_master dm
                    ON da.worked_department_id = dm.dept_id
                LEFT JOIN EMPMILL12.OCCUPATION_MASTER_NORMS omn
                    ON omn.desig_id = da.worked_designation_id
                LEFT JOIN raw_heraatt_data rhd
                    ON da.company_id = rhd.company_id
                   AND da.attendance_date = rhd.trandate
                   AND da.spell = rhd.spell
                   AND rhd.mc_id = dea.mc_id
                LEFT JOIN EMPMILL12.tbl_quality_rate tqr
                    ON tqr.qcode = rhd.wages_code
                   AND tqr.dept_code = dm.dept_code
                WHERE da.attendance_date BETWEEN %s AND %s
                  AND dm.dept_code = '10'
                  AND omn.OCCU_CODE = '14'
                  AND da.is_active = 1
                GROUP BY da.eb_id, SUBSTR(da.spell, 1, 1), dm.dept_code, omn.OCCU_CODE
            ) ehd
                ON tewas.eb_id = ehd.eb_id
               AND tewas.dept_code = ehd.dept_code
               AND tewas.occu_code = ehd.OCCU_CODE
            WHERE tewas.date_from = %s
              AND tewas.date_to = %s
              AND tewas.update_from = 'ATT'
              AND tewas.dept_code = '10'
              AND tewas.occu_code = '14'
              AND tewas.pay_scheme_id = %s
              AND tewas.is_active = 1
            """
            r5 = cursor.execute(sql5, (
                fromdate, todate, payscheme,
                fromdate, todate,
                fromdate, todate, payscheme
            ))

            # -----------------------------
            # 6. Hemming helper (occu 10)
            # -----------------------------
            sql6 = """
            INSERT INTO EMPMILL12.tbl_ejm_wages_data_collection
            (
                date_from, date_to, eb_id, dept_code, occu_code, shift, t_p, working_hours,
                ot_hours, working_hours_eff, ot_hours_eff, pay_scheme_id, update_for,
                updt_from, prod_basic, time_basic, act_eff
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
                CASE
                    WHEN IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0
                    THEN ROUND((ehd.acteff / 100) * tewas.working_hours, 2)
                    ELSE tewas.working_hours
                END AS working_hours_eff,
                CASE
                    WHEN IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0
                    THEN ROUND((ehd.acteff / 100) * tewas.ot_hours, 2)
                    ELSE tewas.ot_hours
                END AS ot_hours_eff,
                %s AS payscheme,
                'HEMM' AS updt,
                'PROD' AS updtfr,
                CASE
                    WHEN IFNULL(ehd.acteff, 0) > 0 THEN ROUND(ehd.totamt / NULLIF(ehd.acteff, 0) * 100, 2)
                    ELSE 0
                END AS prdbas,
                CASE
                    WHEN tewas.pay_scheme_id = 125 AND IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0
                    THEN ROUND(((tewas.working_hours * 13.5) / 3 * 2) + (((tewas.working_hours * 13.5) / 3) * ehd.acteff / 100), 2)
                    WHEN tewas.pay_scheme_id = 125 AND IFNULL(ehd.acteff, 0) >= 100
                    THEN ROUND((tewas.working_hours * 13.5), 2)
                    WHEN tewas.pay_scheme_id = 151 AND IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0
                    THEN ROUND((tewas.working_hours * twor.f_b_rate) / 3 * 2 + ((tewas.working_hours * twor.f_b_rate) / 3) * ehd.acteff / 100, 2)
                    WHEN tewas.pay_scheme_id = 151 AND IFNULL(ehd.acteff, 0) >= 100
                    THEN ROUND((tewas.working_hours * twor.f_b_rate), 2)
                    ELSE 0
                END AS time_basic,
                ehd.acteff AS act_eff
            FROM EMPMILL12.tbl_ejm_wages_att_summary tewas
            LEFT JOIN EMPMILL12.tbl_wages_occu_rate twor
                ON tewas.dept_code = twor.dept_code
               AND tewas.occu_code = twor.occu_code
            LEFT JOIN (
                SELECT
                    da.eb_id,
                    SUBSTR(da.spell, 1, 1) shift,
                    dm.dept_code,
                    omn.OCCU_CODE,
                    SUM(production) production,
                    SUM(rhd.target_eff / 8 * (da.working_hours - da.idle_hours)) targetprod,
                    SUM(tqr.rate * rhd.wages_code) totamt,
                    ROUND(SUM(production) / SUM(rhd.target_eff / 8 * (da.working_hours - da.idle_hours)) * 100, 2) acteff,
                    100 targeteff
                FROM vowsls.daily_attendance da
                LEFT JOIN vowsls.daily_ebmc_attendance dea
                    ON da.daily_atten_id = dea.daily_atten_id
                   AND dea.is_active = 1
                LEFT JOIN department_master dm
                    ON da.worked_department_id = dm.dept_id
                LEFT JOIN EMPMILL12.OCCUPATION_MASTER_NORMS omn
                    ON omn.desig_id = da.worked_designation_id
                LEFT JOIN raw_heraatt_data rhd
                    ON da.company_id = rhd.company_id
                   AND da.attendance_date = rhd.trandate
                   AND da.spell = rhd.spell
                   AND rhd.mc_id = dea.mc_id
                LEFT JOIN EMPMILL12.tbl_quality_rate tqr
                    ON tqr.qcode = rhd.wages_code
                   AND tqr.dept_code = dm.dept_code
                WHERE da.attendance_date BETWEEN %s AND %s
                  AND dm.dept_code = '10'
                  AND omn.OCCU_CODE = '10'
                  AND da.is_active = 1
                GROUP BY da.eb_id, SUBSTR(da.spell, 1, 1), dm.dept_code, omn.OCCU_CODE
            ) ehd
                ON tewas.eb_id = ehd.eb_id
               AND tewas.dept_code = ehd.dept_code
               AND tewas.occu_code = ehd.OCCU_CODE
            WHERE tewas.date_from = %s
              AND tewas.date_to = %s
              AND tewas.update_from = 'ATT'
              AND tewas.dept_code = '10'
              AND tewas.occu_code = '10'
              AND tewas.pay_scheme_id = %s
              AND tewas.is_active = 1
            """
            r6 = cursor.execute(sql6, (
                fromdate, todate, payscheme,
                fromdate, todate,
                fromdate, todate, payscheme
            ))

            cursor.execute("COMMIT")

        return {
            "success": True,
            "message": "Finishing processing completed",
            "rows_affected": {
                "hemm_operator": r1,
                "hera_operator": r2,
                "hand_sewer": r3,
                "heracle_helper": r5,
                "hemming_helper": r6
            },
            "status": "COMPLETED"
        }

    except ValueError:
        if connection:
            try:
                with connection.cursor() as cursor:
                    cursor.execute("ROLLBACK")
            except Exception:
                pass
        return {
            "success": False,
            "message": "Invalid date format",
            "status": "FAILED"
        }

    except Exception as e:
        if connection:
            try:
                with connection.cursor() as cursor:
                    cursor.execute("ROLLBACK")
            except Exception:
                pass
        return {
            "success": False,
            "message": f"Exception: {str(e)}",
            "status": "FAILED"
        }

    finally:
        if connection:
            connection.close()


if __name__ == "__main__":
    try:
        if len(sys.argv) < 5:
            print(json.dumps({
                "success": False,
                "message": "Usage: python main_wages_process_finishing.py fromdate todate payscheme company_id"
            }))
            sys.exit(1)

        fromdate = sys.argv[1]
        todate = sys.argv[2]
        payscheme = sys.argv[3]
        company_id = sys.argv[4]

        result = main_wages_process_finishing(fromdate, todate, payscheme, company_id)
        print(json.dumps(result))

    except Exception as e:
        print(json.dumps({
            "success": False,
            "message": f"Unhandled Exception: {str(e)}",
            "status": "FAILED"
        }))