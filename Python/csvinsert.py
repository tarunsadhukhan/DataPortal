import sys
import csv
import json
from db import get_connection  # make sure db.py is in same folder


# This SQL takes a JSON array (@json), extracts ebno & amount,
# joins with worker_master to find eb_id, and inserts into tranamt.
INSERT_JSON_SQL = """
INSERT INTO ATTENEMP.csvtranamt (eb_id, amount)
SELECT wm.eb_id, j.amount
FROM JSON_TABLE(
  @json, '$[*]'
  COLUMNS (
    ebno   VARCHAR(20)  PATH '$.ebno',
    amount DECIMAL(12,2) PATH '$.amount'
  )
) AS j
JOIN worker_master wm
  ON wm.eb_no = j.ebno;
"""


def send_json_batch(cursor, batch):
    """
    batch: list of dicts like:
      {"ebno": "12345", "amount": 456.78}
    Sends one JSON batch into MySQL and runs INSERT ... JSON_TABLE ...
    """
    if not batch:
        return

    # JSON array from Python list of dicts
    json_str = json.dumps(batch)

    # Set @json in MySQL session
    cursor.execute("SET @json = %s", (json_str,))
    # Insert rows using JSON_TABLE + join
    cursor.execute(INSERT_JSON_SQL)


def import_tranamt_from_csv(csv_path, batch_size=100, has_header=True):
    """
    Reads ebno,amount from CSV and inserts into tranamt using JSON_TABLE
    in batches of 'batch_size'.

    Returns total number of rows processed from CSV (not affected by whether
    ebno exists in worker_master or not).
    """
    conn = get_connection()
    cursor = conn.cursor()

    total_rows = 0

    try:
        with open(csv_path, "r", newline="", encoding="utf-8") as f:
            if has_header:
                # Expecting header: ebno,amount (case-insensitive is okay if you normalize)
                reader = csv.DictReader(f)
            else:
                reader = csv.reader(f)

            batch = []

            for row in reader:
                if has_header:
                    # Adjust keys if your header uses different names
                    ebno   = (row.get("ebno") or row.get("EBNO") or "").strip()
                    amount = (row.get("amount") or row.get("AMOUNT") or "").strip()
                else:
                    # No header: assume row[0]=ebno, row[1]=amount
                    if len(row) < 2:
                        continue
                    ebno   = row[0].strip()
                    amount = row[1].strip()

                if not ebno:
                    continue  # skip empty ebno

                # convert amount safely
                try:
                    amt_val = float(amount or 0)
                except ValueError:
                    amt_val = 0.0

                batch.append({
                    "ebno": ebno,
                    "amount": amt_val,
                })
                total_rows += 1

                # When we hit batch_size, send to DB
                if len(batch) >= batch_size:
                    send_json_batch(cursor, batch)
                    batch = []  # reset

            # send any remaining rows
            if batch:
                send_json_batch(cursor, batch)

        conn.commit()
        return total_rows

    except Exception as e:
        conn.rollback()
        raise e

    finally:
        cursor.close()
        conn.close()


def main():
    # CLI: python csv_tranamt_import.py path\to\file.csv
    if len(sys.argv) < 2:
        print(json.dumps({
            "success": False,
            "message": "Usage: csv_tranamt_import.py <csv_path>"
        }))
        return

    csv_path = sys.argv[1]

    # You can make has_header configurable if you want via argv[2]
    has_header = True  # set to False if your CSV has no header row

    try:
        total = import_tranamt_from_csv(csv_path, batch_size=100, has_header=has_header)
    except Exception as e:
        print(json.dumps({
            "success": False,
            "message": f"Error importing CSV: {e}"
        }))
        return

    print(json.dumps({
        "success": True,
        "message": "Inserted via JSON_TABLE successfully",
        "total_rows": total
    }))


if __name__ == "__main__":
    main()
