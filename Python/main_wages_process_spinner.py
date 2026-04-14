import sys
import json
from datetime import datetime
from db import get_connection


def main_wages_process_spinner(fromdate, todate, payscheme, company_id):
    connection = None

    try:
        # Validate date format
        datetime.strptime(fromdate, "%Y-%m-%d")
        datetime.strptime(todate, "%Y-%m-%d")

        connection = get_connection()

        with connection.cursor() as cursor:
            # Drop temp table if exists in same session
            cursor.execute("DROP TEMPORARY TABLE IF EXISTS tmp_view_proc_daily_doff_details")

            # Create temp table
            temp_sql = """
                CREATE TEMPORARY TABLE tmp_view_proc_daily_doff_details AS
                SELECT *
                FROM EMPMILL12.view_proc_daily_doff_details
                WHERE doffdate BETWEEN %s AND %s
                  AND compid = %s
            """
            cursor.execute(temp_sql, (fromdate, todate, company_id))

            queries = []

            # 1. occu_code 01, frameno < 60
            queries.append((
                "DOFFS_01",
                """
                INSERT INTO EMPMILL12.tbl_ejm_wages_data_collection
                (
                    date_from,date_to,eb_id,dept_code,occu_code,shift,t_p,working_hours,
                    ot_hours,working_hours_eff,ot_hours_eff,pay_scheme_id,update_for,updt_from,act_eff
                )
                SELECT
                    df,dt,tewas.eb_id,tewas.dept_code,tewas.occu_code,tewas.shift,'T',tewas.working_hours,tewas.ot_hours,
                    CASE WHEN (eff/target_eff*100)<100 THEN ROUND(eff/target_eff*tewas.working_hours,2) ELSE tewas.working_hours END,
                    CASE WHEN (eff/target_eff*100)<100 THEN ROUND(eff/target_eff*tewas.ot_hours,2) ELSE tewas.ot_hours END,
                    %s,'DOFFS','PROD',doff.eff
                FROM EMPMILL12.tbl_ejm_wages_att_summary tewas
                LEFT JOIN
                (
                    SELECT doff.*,tate.target_eff
                    FROM (
                        SELECT
                            %s df,%s dt,vpddd.eb_id,SUBSTR(vpddd.spell,1,1) shift,SUM(prod) prod,
                            SUM(prod100) targetprod,ROUND(SUM(prod)/SUM(prod100)*100,2) eff,4 eff_code
                        FROM tmp_view_proc_daily_doff_details vpddd
                        WHERE vpddd.doffdate BETWEEN %s AND %s
                          AND compid=%s
                          AND vpddd.frameno < 60
                        GROUP BY vpddd.eb_id,SUBSTR(vpddd.spell,1,1)
                    ) doff
                    LEFT JOIN EMPMILL12.tbl_all_trn_eff tate
                        ON tate.date_from=doff.df AND tate.date_to=doff.dt AND tate.eff_code=doff.eff_code
                ) doff
                    ON tewas.shift=doff.shift AND tewas.eb_id=doff.eb_id
                WHERE tewas.pay_scheme_id=%s
                  AND tewas.update_from='ATT'
                  AND tewas.is_active=1
                  AND tewas.occu_code='01'
                  AND tewas.dept_code='04'
                """,
                (payscheme, fromdate, todate, fromdate, todate, company_id, payscheme)
            ))

            # 2. occu_code 06, frameno >= 60
            queries.append((
                "DOFFS_06",
                """
                INSERT INTO EMPMILL12.tbl_ejm_wages_data_collection
                (
                    date_from,date_to,eb_id,dept_code,occu_code,shift,t_p,working_hours,
                    ot_hours,working_hours_eff,ot_hours_eff,pay_scheme_id,update_for,updt_from,act_eff
                )
                SELECT
                    df,dt,tewas.eb_id,tewas.dept_code,tewas.occu_code,tewas.shift,'T',tewas.working_hours,tewas.ot_hours,
                    CASE WHEN (eff/target_eff*100)<100 THEN ROUND(eff/target_eff*tewas.working_hours,2) ELSE tewas.working_hours END,
                    CASE WHEN (eff/target_eff*100)<100 THEN ROUND(eff/target_eff*tewas.ot_hours,2) ELSE tewas.ot_hours END,
                    %s,'DOFFS','PROD',doff.eff
                FROM EMPMILL12.tbl_ejm_wages_att_summary tewas
                LEFT JOIN
                (
                    SELECT doff.*,tate.target_eff
                    FROM (
                        SELECT
                            %s df,%s dt,vpddd.eb_id,SUBSTR(vpddd.spell,1,1) shift,SUM(prod) prod,
                            SUM(prod100) targetprod,ROUND(SUM(prod)/SUM(prod100)*100,2) eff,4 eff_code
                        FROM tmp_view_proc_daily_doff_details vpddd
                        WHERE vpddd.doffdate BETWEEN %s AND %s
                          AND vpddd.compid=%s
                          AND vpddd.frameno >= 60
                        GROUP BY vpddd.eb_id,SUBSTR(vpddd.spell,1,1)
                    ) doff
                    LEFT JOIN EMPMILL12.tbl_all_trn_eff tate
                        ON tate.date_from=doff.df AND tate.date_to=doff.dt AND tate.eff_code=doff.eff_code
                ) doff
                    ON tewas.shift=doff.shift AND tewas.eb_id=doff.eb_id
                WHERE tewas.pay_scheme_id=%s
                  AND tewas.update_from='ATT'
                  AND tewas.is_active=1
                  AND tewas.occu_code='06'
                  AND tewas.dept_code='04'
                """,
                (payscheme, fromdate, todate, fromdate, todate, company_id, payscheme)
            ))

            # Helper for designation-based DOFFS queries
            def designation_doffs_query(worked_designation_id, occu_code):
                return (
                    f"DOFFS_DESIG_{worked_designation_id}_{occu_code}",
                    """
                    INSERT INTO EMPMILL12.tbl_ejm_wages_data_collection
                    (
                        date_from,date_to,eb_id,dept_code,occu_code,shift,t_p,working_hours,
                        ot_hours,working_hours_eff,ot_hours_eff,pay_scheme_id,update_for,updt_from,act_eff
                    )
                    SELECT
                        df,dt,tewas.eb_id,tewas.dept_code,tewas.occu_code,tewas.shift,'T',tewas.working_hours,tewas.ot_hours,
                        CASE WHEN (eff/target_eff*100)<100 THEN ROUND(eff/target_eff*tewas.working_hours,2) ELSE tewas.working_hours END,
                        CASE WHEN (eff/target_eff*100)<100 THEN ROUND(eff/target_eff*tewas.ot_hours,2) ELSE tewas.ot_hours END,
                        %s,'DOFFS','PROD',doff.eff
                    FROM EMPMILL12.tbl_ejm_wages_att_summary tewas
                    LEFT JOIN
                    (
                        SELECT doff.*,tate.target_eff
                        FROM (
                            SELECT
                                %s df,%s dt,da.eb_id,SUBSTR(vpddd.spell,1,1) shift,SUM(prod) prod,
                                SUM(prod100) targetprod,ROUND(SUM(prod)/SUM(prod100)*100,2) eff,4 eff_code
                            FROM tmp_view_proc_daily_doff_details vpddd
                            LEFT JOIN vowsls.daily_ebmc_attendance dea
                                ON dea.attendace_date=vpddd.doffdate
                               AND dea.spell=vpddd.spell
                               AND dea.mc_id=vpddd.mechine_id
                               AND dea.is_active=1
                            LEFT JOIN vowsls.daily_attendance da
                                ON da.daily_atten_id=dea.daily_atten_id
                               AND da.is_active=1
                            WHERE vpddd.doffdate BETWEEN %s AND %s
                              AND vpddd.compid=%s
                              AND da.worked_designation_id=%s
                            GROUP BY da.eb_id,SUBSTR(vpddd.spell,1,1)
                        ) doff
                        LEFT JOIN EMPMILL12.tbl_all_trn_eff tate
                            ON tate.date_from=doff.df AND tate.date_to=doff.dt AND tate.eff_code=doff.eff_code
                    ) doff
                        ON tewas.shift=doff.shift AND tewas.eb_id=doff.eb_id
                    WHERE tewas.pay_scheme_id=%s
                      AND tewas.update_from='ATT'
                      AND tewas.is_active=1
                      AND tewas.occu_code=%s
                      AND tewas.dept_code='04'
                    """,
                    (payscheme, fromdate, todate, fromdate, todate, company_id, worked_designation_id, payscheme, occu_code)
                )

            queries.append(designation_doffs_query(51, '02'))
            queries.append(designation_doffs_query(196, '07'))
            queries.append(designation_doffs_query(52, '19'))
            queries.append(designation_doffs_query(197, '20'))

            # Helper for SPGAV queries without shift
            def spgav_query(label, qcode_condition_sql, eff_code):
                return (
                    label,
                    f"""
                    INSERT INTO EMPMILL12.tbl_ejm_wages_data_collection
                    (
                        date_from,date_to,eb_id,dept_code,occu_code,shift,t_p,working_hours,
                        ot_hours,working_hours_eff,ot_hours_eff,pay_scheme_id,update_for,updt_from,act_eff
                    )
                    SELECT
                        df,dt,tewas.eb_id,tewas.dept_code,tewas.occu_code,tewas.shift,'T',
                        tewas.working_hours,tewas.ot_hours,
                        CASE WHEN acteff < 100 THEN ROUND(acteff/100 * tewas.working_hours, 2) ELSE tewas.working_hours END,
                        CASE WHEN acteff < 100 THEN ROUND(acteff/100 * tewas.ot_hours, 2) ELSE tewas.ot_hours END,
                        %s,'SPGAV','PROD',tew.acteff
                    FROM EMPMILL12.tbl_ejm_wages_att_summary tewas
                    LEFT JOIN
                    (
                        SELECT %s df,%s dt,tewom.eff_code,tewom.deptcode,tewom.occucode,eff acteff
                        FROM EMPMILL12.tbl_ejm_wages_occu_mast tewom
                        JOIN
                        (
                            SELECT
                                SUM(totprd) totprd,
                                SUM(tottarget) tottarget,
                                SUM(tottarget*target_eff/100) tgpdeff,
                                ROUND(SUM(totprd)/SUM(tottarget*target_eff/100)*100,2) eff,
                                {eff_code} eff_code
                            FROM
                            (
                                SELECT
                                    eff_code,
                                    SUM(prd_a + prd_b + prd_c) totprd,
                                    SUM(tarprda + tarprdb + tarprdc) tottarget
                                FROM
                                (
                                    SELECT sdt.*,
                                           CASE WHEN SUBSTR(q_code,1,1) IN ('1','2') THEN 4 ELSE 3 END eff_code
                                    FROM EMPMILL12.spining_daily_transaction sdt
                                    WHERE sdt.tran_date BETWEEN %s AND %s
                                      AND sdt.company_id=%s
                                      AND {qcode_condition_sql}
                                ) g
                                GROUP BY eff_code
                            ) sdt
                            LEFT JOIN EMPMILL12.tbl_all_trn_eff tate
                                ON tate.eff_code=sdt.eff_code
                               AND tate.date_from=%s
                               AND tate.date_to=%s
                        ) sdt
                            ON tewom.eff_code=sdt.eff_code
                           AND tewom.effcheck='CG'
                    ) tew
                        ON tew.deptcode=tewas.dept_code
                       AND tew.occucode=tewas.occu_code
                    WHERE tewas.pay_scheme_id=%s
                      AND tewas.update_from='ATT'
                      AND tewas.is_active=1
                      AND df IS NOT NULL
                    """,
                    (payscheme, fromdate, todate, fromdate, todate, company_id, fromdate, todate, payscheme)
                )

            queries.append(spgav_query("SPGAV_2", "SUBSTR(q_code,1,1) IN ('1','2')", 2))
            queries.append(spgav_query("SPGAV_3", "SUBSTR(q_code,1,1)='3'", 3))
            queries.append(spgav_query("SPGAV_4", "SUBSTR(q_code,1,1) IN ('1','2')", 4))

            # SPGAV by shift eff_code 24
            queries.append((
                "SPGAV_24",
                """
                INSERT INTO EMPMILL12.tbl_ejm_wages_data_collection
                (
                    date_from,date_to,eb_id,dept_code,occu_code,shift,t_p,working_hours,
                    ot_hours,working_hours_eff,ot_hours_eff,pay_scheme_id,update_for,updt_from,act_eff
                )
                SELECT
                    df,dt,tewas.eb_id,tewas.dept_code,tewas.occu_code,tewas.shift,'T',
                    tewas.working_hours,tewas.ot_hours,
                    CASE WHEN acteff < 100 THEN ROUND(acteff/100 * tewas.working_hours, 2) ELSE tewas.working_hours END,
                    CASE WHEN acteff < 100 THEN ROUND(acteff/100 * tewas.ot_hours, 2) ELSE tewas.ot_hours END,
                    %s,'SPGAV','PROD',tew.acteff
                FROM EMPMILL12.tbl_ejm_wages_att_summary tewas
                LEFT JOIN
                (
                    SELECT %s df,%s dt,shift,tewom.eff_code,tewom.deptcode,tewom.occucode,eff acteff
                    FROM EMPMILL12.tbl_ejm_wages_occu_mast tewom
                    JOIN
                    (
                        SELECT shift,SUM(totprd) totprd,SUM(tottarget) tottarget,
                               SUM(tottarget*target_eff/100) tgpdeff,
                               ROUND(SUM(totprd)/SUM(tottarget*target_eff/100)*100,2) eff,
                               24 eff_code
                        FROM
                        (
                            SELECT shift,24 eff_code,SUM(prd) totprd,SUM(tarprd) tottarget
                            FROM
                            (
                                SELECT 'A' shift,prd_a prd,tarprda tarprd
                                FROM EMPMILL12.spining_daily_transaction sdt
                                WHERE sdt.tran_date BETWEEN %s AND %s AND sdt.company_id=%s
                                UNION ALL
                                SELECT 'B' shift,prd_b prd,tarprdb tarprd
                                FROM EMPMILL12.spining_daily_transaction sdt
                                WHERE sdt.tran_date BETWEEN %s AND %s AND sdt.company_id=%s
                                UNION ALL
                                SELECT 'C' shift,prd_c prd,tarprdc tarprd
                                FROM EMPMILL12.spining_daily_transaction sdt
                                WHERE sdt.tran_date BETWEEN %s AND %s AND sdt.company_id=%s
                            ) g
                            GROUP BY shift
                        ) sdt
                        LEFT JOIN EMPMILL12.tbl_all_trn_eff tate
                            ON tate.eff_code=sdt.eff_code
                           AND tate.date_from=%s
                           AND tate.date_to=%s
                        GROUP BY shift
                    ) sdt
                        ON tewom.eff_code=sdt.eff_code
                       AND tewom.effcheck='CG'
                ) tew
                    ON tew.deptcode=tewas.dept_code
                   AND tew.occucode=tewas.occu_code
                   AND tew.shift=tewas.shift
                WHERE tewas.pay_scheme_id=%s
                  AND tewas.update_from='ATT'
                  AND tewas.is_active=1
                  AND df IS NOT NULL
                """,
                (
                    payscheme, fromdate, todate,
                    fromdate, todate, company_id,
                    fromdate, todate, company_id,
                    fromdate, todate, company_id,
                    fromdate, todate,
                    payscheme
                )
            ))

            # Helper for subgroup shift queries
            def subgroup_shift_query(label, subgroup_list, eff_code):
                subgroup_placeholders = ",".join(["%s"] * len(subgroup_list))
                return (
                    label,
                    f"""
                    INSERT INTO EMPMILL12.tbl_ejm_wages_data_collection
                    (
                        date_from,date_to,eb_id,dept_code,occu_code,shift,t_p,working_hours,
                        ot_hours,working_hours_eff,ot_hours_eff,pay_scheme_id,update_for,updt_from,act_eff
                    )
                    SELECT
                        df,dt,tewas.eb_id,tewas.dept_code,tewas.occu_code,tewas.shift,'T',
                        tewas.working_hours,tewas.ot_hours,
                        CASE WHEN acteff < 100 THEN ROUND(acteff/100 * tewas.working_hours, 2) ELSE tewas.working_hours END,
                        CASE WHEN acteff < 100 THEN ROUND(acteff/100 * tewas.ot_hours, 2) ELSE tewas.ot_hours END,
                        %s,'SPGAV','PROD',tew.acteff
                    FROM EMPMILL12.tbl_ejm_wages_att_summary tewas
                    LEFT JOIN
                    (
                        SELECT %s df,%s dt,shift,tewom.eff_code,tewom.deptcode,tewom.occucode,IFNULL(eff,0) acteff
                        FROM EMPMILL12.tbl_ejm_wages_occu_mast tewom
                        JOIN
                        (
                            SELECT shift,SUM(totprd) totprd,SUM(tottarget) tottarget,
                                   CASE WHEN SUM(tottarget)>0 THEN SUM(tottarget*target_eff/100) ELSE 0 END tgpdeff,
                                   CASE WHEN SUM(totprd)>0 THEN ROUND(SUM(totprd)/SUM(tottarget*target_eff/100)*100,2) ELSE 0 END eff,
                                   {eff_code} eff_code
                            FROM
                            (
                                SELECT shift,{eff_code} eff_code,SUM(prd) totprd,SUM(tarprd) tottarget
                                FROM
                                (
                                    SELECT sdt.tran_date,'A' shift,prd_a prd,tarprda tarprd,SUBSTR(sm.subgroup_type,3,2) subgrp
                                    FROM EMPMILL12.spining_daily_transaction sdt
                                    LEFT JOIN EMPMILL12.spining_master sm ON sm.q_code=sdt.q_code
                                    WHERE sdt.tran_date BETWEEN %s AND %s AND sdt.company_id=%s
                                    UNION ALL
                                    SELECT sdt.tran_date,'B' shift,prd_b prd,tarprdb tarprd,SUBSTR(sm.subgroup_type,3,2) subgrp
                                    FROM EMPMILL12.spining_daily_transaction sdt
                                    LEFT JOIN EMPMILL12.spining_master sm ON sm.q_code=sdt.q_code
                                    WHERE sdt.tran_date BETWEEN %s AND %s AND sdt.company_id=%s
                                    UNION ALL
                                    SELECT sdt.tran_date,'C' shift,prd_c prd,tarprdc tarprd,SUBSTR(sm.subgroup_type,3,2) subgrp
                                    FROM EMPMILL12.spining_daily_transaction sdt
                                    LEFT JOIN EMPMILL12.spining_master sm ON sm.q_code=sdt.q_code
                                    WHERE sdt.tran_date BETWEEN %s AND %s AND sdt.company_id=%s
                                ) g
                                WHERE subgrp IN ({subgroup_placeholders})
                                GROUP BY shift
                            ) sdt
                            LEFT JOIN EMPMILL12.tbl_all_trn_eff tate
                                ON tate.eff_code=sdt.eff_code
                               AND tate.date_from=%s
                               AND tate.date_to=%s
                            GROUP BY shift
                        ) sdt
                            ON tewom.eff_code=sdt.eff_code
                           AND tewom.effcheck='CG'
                    ) tew
                        ON tew.deptcode=tewas.dept_code
                       AND tew.occucode=tewas.occu_code
                       AND tew.shift=tewas.shift
                    WHERE tewas.pay_scheme_id=%s
                      AND tewas.update_from='ATT'
                      AND tewas.is_active=1
                      AND df IS NOT NULL
                    """,
                    (
                        payscheme, fromdate, todate,
                        fromdate, todate, company_id,
                        fromdate, todate, company_id,
                        fromdate, todate, company_id,
                        *subgroup_list,
                        fromdate, todate,
                        payscheme
                    )
                )

            queries.append(subgroup_shift_query("SPGAV_7", ['WP', 'YN'], 7))
            queries.append(subgroup_shift_query("SPGAV_8", ['WT'], 8))

            affected = {}
            total_rows_affected = 0

            for label, sql, params in queries:
                cursor.execute(sql, params)
                row_count = cursor.rowcount
                affected[label] = row_count
                if row_count and row_count > 0:
                    total_rows_affected += row_count

        connection.commit()

        return {
            "success": True,
            "message": "Spinner processing completed",
            "total_rows_affected": total_rows_affected,
            "rows_affected": affected
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
                "message": "Usage: python main_wages_process_spinner.py fromdate todate payscheme company_id"
            }))
            sys.exit(1)

        fromdate = sys.argv[1]
        todate = sys.argv[2]
        payscheme = sys.argv[3]
        company_id = sys.argv[4]

        result = main_wages_process_spinner(fromdate, todate, payscheme, company_id)
        print(json.dumps(result))

    except Exception as e:
        print(json.dumps({
            "success": False,
            "message": f"Unhandled Exception: {str(e)}"
        }))