import sys
import json
from datetime import datetime, timedelta
from db import get_connection


def main_wages_process_winding(fromdate, todate, payscheme, company_id):
    connection = None

    try:
        datetime.strptime(fromdate, "%Y-%m-%d")
        to_dt = datetime.strptime(todate, "%Y-%m-%d")
        fndate = (to_dt + timedelta(days=1)).strftime("%Y-%m-%d")

        connection = get_connection()

        with connection.cursor() as cursor:
            # Drop temp table if exists
            cursor.execute("DROP TEMPORARY TABLE IF EXISTS tmp_allwindingdata")

            # Create temp table
            temp_sql = """
                CREATE TEMPORARY TABLE tmp_allwindingdata AS
                SELECT *
                FROM EMPMILL12.allwindingdata
                WHERE tran_date BETWEEN %s AND %s
                  AND company_id = %s
            """
            cursor.execute(temp_sql, (fromdate, todate, company_id))

            # Query 1: WDGWP for dept 05
            sql1 = """
            INSERT INTO EMPMILL12.tbl_ejm_wages_data_collection
            (
                date_from,date_to,eb_id,dept_code,occu_code,shift,t_p,working_hours,
                ot_hours,working_hours_eff,ot_hours_eff,pay_scheme_id,update_for,updt_from,
                prod_basic,time_basic,act_eff
            )
            SELECT
                %s AS df, %s AS dt,
                tewas.eb_id,
                tewas.dept_code,
                tewas.occu_code,
                tewas.shift,
                'P' AS t_p,
                tewas.working_hours,
                tewas.ot_hours,
                CASE
                    WHEN ((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff*100) < 100
                    THEN ROUND(((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff)*tewas.working_hours, 2)
                    ELSE tewas.working_hours
                END AS working_hours_eff,
                CASE
                    WHEN ((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff*100) < 100
                    THEN ROUND(((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff)*tewas.ot_hours, 2)
                    ELSE tewas.ot_hours
                END AS ot_hours_eff,
                %s AS payschm,
                'WDGWP' AS updt,
                'PROD' AS updtfr,
                ROUND(wnd.target_eff/8*(working_hours+ot_hours),2) AS prdbas,
                CASE
                    WHEN %s=151 AND ((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff*100)<100
                    THEN (tewas.working_hours*13.5)/3*2 + (((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff)*(tewas.working_hours*13.5)/3)
                    WHEN %s=151 AND ((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff*100)>=100
                    THEN (tewas.working_hours*13.5)
                    WHEN %s=125 AND ((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff*100)<100
                    THEN (tewas.working_hours*twor.f_b_rate)/3*2 + (((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff)*(tewas.working_hours*twor.f_b_rate)/3)
                    WHEN %s=125 AND ((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff*100)>=100
                    THEN (tewas.working_hours*twor.f_b_rate)
                    ELSE 0
                END AS time_basic,
                (wnd.prod/wnd.target_eff*100) AS act_eff
            FROM EMPMILL12.tbl_ejm_wages_att_summary tewas
            LEFT JOIN
            (
                SELECT
                    wm.eb_id,
                    g.eb_no,
                    deptcode,
                    occu_code,
                    shift,
                    wage_code,
                    prod,
                    tate.target_eff,
                    %s AS payschm
                FROM
                (
                    SELECT
                        CONCAT(deptcode,eb_no,shift,occu_code,wage_code,LPAD(prod, 4, '0'),
                               '00000000000000000000000000000', %s) AS prods,
                        eb_no,
                        deptcode,
                        occu_code,
                        shift,
                        wage_code,
                        prod
                    FROM
                    (
                        SELECT
                            b.deptcode,
                            eb_no,
                            shift,
                            b.wage_code,
                            b.OCCU_CODE,
                            CASE
                                WHEN deptcode='06' THEN ROUND(SUM(production)/14,0)
                                ELSE SUM(production)
                            END AS prod
                        FROM
                        (
                            SELECT
                                tran_date,
                                SUBSTR(spell,1,1) shift,
                                eb_no,
                                wnd_q_code,
                                SUM(prod) production
                            FROM tmp_allwindingdata a
                            WHERE tran_date BETWEEN %s AND %s
                              AND company_id = %s
                            GROUP BY tran_date, SUBSTR(spell,1,1), eb_no, wnd_q_code
                        ) a
                        LEFT JOIN EMPMILL12.winding_wages_link b
                            ON a.wnd_q_code = b.wnd_q_code
                        GROUP BY b.deptcode, eb_no, shift, b.wage_code, b.OCCU_CODE
                    ) g
                    WHERE SUBSTR(eb_no,1,1) IN ('1','0','5','8')
                      AND occu_code <> '55'
                ) g
                LEFT JOIN worker_master wm
                    ON wm.eb_no = g.eb_no
                   AND wm.company_id = %s
                LEFT JOIN EMPMILL12.tbl_all_trn_eff tate
                    ON tate.qual_code = g.wage_code
                   AND tate.date_from = %s
                   AND tate.date_to = %s
            ) wnd
                ON wnd.eb_id = tewas.eb_id
               AND wnd.deptcode = tewas.dept_code
               AND wnd.occu_code = tewas.occu_code
               AND wnd.shift = tewas.shift
            LEFT JOIN EMPMILL12.tbl_quality_rate tqr
                ON tqr.qcode = wnd.wage_code
               AND tqr.dept_code = wnd.deptcode
            LEFT JOIN EMPMILL12.tbl_wages_occu_rate twor
                ON wnd.deptcode = twor.dept_code
               AND wnd.occu_code = twor.occu_code
            WHERE tewas.dept_code IN ('05')
              AND tewas.occu_code IN ('01','02')
              AND tewas.pay_scheme_id = %s
            """

            # Query 2: WDGWT for dept 06
            sql2 = """
            INSERT INTO EMPMILL12.tbl_ejm_wages_data_collection
            (
                date_from,date_to,eb_id,dept_code,occu_code,shift,t_p,working_hours,
                ot_hours,working_hours_eff,ot_hours_eff,pay_scheme_id,update_for,updt_from,
                prod_basic,time_basic,act_eff
            )
            SELECT
                %s AS df, %s AS dt,
                tewas.eb_id,
                tewas.dept_code,
                tewas.occu_code,
                tewas.shift,
                'P' AS t_p,
                tewas.working_hours,
                tewas.ot_hours,
                CASE
                    WHEN ((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff*100) < 100
                    THEN ROUND(((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff)*tewas.working_hours, 2)
                    ELSE tewas.working_hours
                END AS working_hours_eff,
                CASE
                    WHEN ((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff*100) < 100
                    THEN ROUND(((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff)*tewas.ot_hours, 2)
                    ELSE tewas.ot_hours
                END AS ot_hours_eff,
                %s AS payschm,
                'WDGWT' AS updt,
                'PROD' AS updtfr,
                ROUND(wnd.target_eff/8*(working_hours+ot_hours),2) AS prdbas,
                CASE
                    WHEN %s=151 AND ((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff*100)<100
                    THEN (tewas.working_hours*13.5)/3*2 + (((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff)*(tewas.working_hours*13.5)/3)
                    WHEN %s=151 AND ((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff*100)>=100
                    THEN (tewas.working_hours*13.5)
                    WHEN %s=125 AND ((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff*100)<100
                    THEN (tewas.working_hours*twor.f_b_rate)/3*2 + (((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff)*(tewas.working_hours*twor.f_b_rate)/3)
                    WHEN %s=125 AND ((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff*100)>=100
                    THEN (tewas.working_hours*twor.f_b_rate)
                    ELSE 0
                END AS time_basic,
                (wnd.prod/wnd.target_eff*100) AS act_eff
            FROM EMPMILL12.tbl_ejm_wages_att_summary tewas
            LEFT JOIN
            (
                SELECT
                    wm.eb_id,
                    g.eb_no,
                    deptcode,
                    occu_code,
                    shift,
                    wage_code,
                    prod,
                    tate.target_eff,
                    %s AS payschm
                FROM
                (
                    SELECT
                        CONCAT(deptcode,eb_no,shift,occu_code,wage_code,LPAD(prod, 4, '0'),
                               '00000000000000000000000000000', %s) AS prods,
                        eb_no,
                        deptcode,
                        occu_code,
                        shift,
                        wage_code,
                        prod
                    FROM
                    (
                        SELECT
                            b.deptcode,
                            eb_no,
                            shift,
                            b.wage_code,
                            b.OCCU_CODE,
                            CASE
                                WHEN deptcode='06' THEN ROUND(SUM(production)/14,0)
                                ELSE SUM(production)
                            END AS prod
                        FROM
                        (
                            SELECT
                                tran_date,
                                SUBSTR(spell,1,1) shift,
                                eb_no,
                                wnd_q_code,
                                SUM(prod) production
                            FROM tmp_allwindingdata a
                            WHERE tran_date BETWEEN %s AND %s
                              AND company_id = %s
                            GROUP BY tran_date, SUBSTR(spell,1,1), eb_no, wnd_q_code
                        ) a
                        LEFT JOIN EMPMILL12.winding_wages_link b
                            ON a.wnd_q_code = b.wnd_q_code
                        GROUP BY b.deptcode, eb_no, shift, b.wage_code, b.OCCU_CODE
                    ) g
                    WHERE SUBSTR(eb_no,1,1) IN ('1','0','5','8')
                      AND occu_code <> '55'
                ) g
                LEFT JOIN worker_master wm
                    ON wm.eb_no = g.eb_no
                   AND wm.company_id = %s
                LEFT JOIN EMPMILL12.tbl_all_trn_eff tate
                    ON tate.qual_code = g.wage_code
                   AND tate.date_from = %s
                   AND tate.date_to = %s
            ) wnd
                ON wnd.eb_id = tewas.eb_id
               AND wnd.deptcode = tewas.dept_code
               AND wnd.occu_code = tewas.occu_code
               AND wnd.shift = tewas.shift
            LEFT JOIN EMPMILL12.tbl_quality_rate tqr
                ON tqr.qcode = wnd.wage_code
               AND tqr.dept_code = wnd.deptcode
            LEFT JOIN EMPMILL12.tbl_wages_occu_rate twor
                ON wnd.deptcode = twor.dept_code
               AND wnd.occu_code = twor.occu_code
            WHERE tewas.dept_code IN ('06')
              AND tewas.occu_code IN ('01','02')
              AND tewas.pay_scheme_id = %s
            """

            result1 = cursor.execute(
                sql1,
                (
                    fromdate, todate, payscheme,
                    payscheme, payscheme, payscheme, payscheme,
                    payscheme, fndate,
                    fromdate, todate, company_id,
                    company_id, fromdate, todate,
                    payscheme
                )
            )

            result2 = cursor.execute(
                sql2,
                (
                    fromdate, todate, payscheme,
                    payscheme, payscheme, payscheme, payscheme,
                    payscheme, fndate,
                    fromdate, todate, company_id,
                    company_id, fromdate, todate,
                    payscheme
                )
            )

        connection.commit()

        return {
            "success": True,
            "message": "Winding processing completed",
            "rows_affected": {
                "wdgwp": result1,
                "wdgwt": result2
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
                "message": "Usage: python main_wages_process_winding.py fromdate todate payscheme company_id"
            }))
            sys.exit(1)

        fromdate = sys.argv[1]
        todate = sys.argv[2]
        payscheme = sys.argv[3]
        company_id = sys.argv[4]

        result = main_wages_process_winding(fromdate, todate, payscheme, company_id)
        print(json.dumps(result))

    except Exception as e:
        print(json.dumps({
            "success": False,
            "message": f"Unhandled Exception: {str(e)}"
        }))