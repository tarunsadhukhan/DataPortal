#!/usr/bin/env python
# -*- coding: utf-8 -*-

import sys
import json
import csv
import mysql.connector
from db import get_connection   

def main():
    """
    Called from CodeIgniter via shell_exec.

    Args (sys.argv):
        1: csv_path       - full path to uploaded CSV
        2: period_from    - string date (e.g. '2025-11-01')
        3: period_to      - string date (e.g. '2025-11-30')
        4: company_id     - integer (session->companyId)
        5: payscheme_id   - integer (att_payschm)
    """

    # ---- 1. Basic argument validation ----
    if len(sys.argv) < 6:
        print(json.dumps({
            "success": False,
            "message": "Not enough arguments passed to process_csv.py"
        }))
        return

    csv_path       = sys.argv[1]
    period_from    = sys.argv[2]
    period_to      = sys.argv[3]

    try:
        company_id   = int(sys.argv[4])
    except ValueError:
        print(json.dumps({
            "success": False,
            "message": f"Invalid company_id (not int): {sys.argv[4]}"
        }))
        return

    try:
        payscheme_id = int(sys.argv[5])
    except ValueError:
        print(json.dumps({
            "success": False,
            "message": f"Invalid payscheme_id (not int): {sys.argv[5]}"
        }))
        return

    # ---- 2. Connect to MySQL ----
    try:
        conn = get_connection()
        cursor = conn.cursor(dictionary=True)
    except Exception as e:
        print(json.dumps({
            "success": False,
            "message": f"DB connection error: {e}"
        }))
        return

    savedata  = []   # rows successfully updated
    ebmissing = []   # EB numbers not found in employee master

    try:
        # ---- 3. Read CSV file ----
        # Assumption: CSV has at least these headers:
        #   eb_no, att_days
        # Adjust column names below to match your actual CSV.
    
        # ---- 3. Read CSV (no header) ----
        # Assumption:
        #   col 0 -> EB no
        #   col 1 -> present days / att_days
        # If different, change indices below.
        with open(csv_path, 'r', newline='', encoding='utf-8-sig') as f:
            reader = csv.reader(f)

            # Skip first 2 rows
            next(reader, None)  # row 1
            next(reader, None)  # row 2 (header)

            for row in reader:
                # Skip empty lines
                if not row or all((c.strip() == '' for c in row)):
                    continue

                # Make sure row has enough columns
                if len(row) < 4:
                    # Not enough columns for EMPID + WRK_HR100
                    continue

                # Column mapping:
                # 0: EMPID
                # 1: NAME
                # 2: PAYSCH
                # 3: WRK_HR100
                # 12: WRK_HR_EFF  (if you want effective hours instead)
                eb_no_raw   = row[0]
                wrkhr_raw   = row[3]   # use row[12] if you prefer WRK_HR_EFF

                eb_no = (eb_no_raw or '').strip()
                attdays = (wrkhr_raw or '').strip()

                print ({eb_no_raw})

                if not eb_no:
                    continue

                try:
                    present_days = float(attdays) if attdays != '' else 0
                except ValueError:
                    present_days = 0

                # ---- 4. Check employee exists ----
                # Adjust employee_master/columns as per your schema
                # cursor.execute(
                    # """
                    # SELECT id
                    # FROM employee_master
                    # WHERE eb_no = %s
                      # AND company_id = %s
                    # """,
                    # (eb_no, company_id)
                # )
                # #emp = cursor.fetchone()

                # if not emp:
                    # ebmissing.append(eb_no)
                    # continue

                # ---- 5. Update attendance/payroll table ----
                # TODO: change attendance_table & column names to your actual names.
                # cursor.execute(
                    # """
                    # UPDATE attendance_table
                    # SET present_days = %s,
                        # payscheme_id = %s
                    # WHERE eb_no = %s
                      # AND company_id = %s
                      # AND att_date BETWEEN %s AND %s
                    # """,
                    # (present_days, payscheme_id, eb_no, company_id, period_from, period_to)
                # )

                # savedata.append({
                    # "eb_no": eb_no,
                    # "present_days": present_days,
                    # "period_from": period_from,
                    # "period_to": period_to,
                    # "company_id": company_id,
                    # "payscheme_id": payscheme_id
                # })

        # ---- 6. Commit ----
        conn.commit()

        result = {
            "success": True,
            "message": "CSV processed and MySQL updated successfully",
            "savedata": savedata,
            "ebmissing": ebmissing
        }

    except Exception as e:
        conn.rollback()
        result = {
            "success": False,
            "message": f"Error while processing CSV: {e}",
            "savedata": savedata,
            "ebmissing": ebmissing
        }

    finally:
        cursor.close()
        conn.close()

    # ---- 7. Output JSON for CodeIgniter ----
    print(json.dumps(result, default=str))

if __name__ == "__main__":
    main()
