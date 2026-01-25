# db.py
import os
import pymysql

def get_conn():
    """
    Returns a MySQL connection (PyMySQL).
    You can also set env vars: DB_HOST, DB_USER, DB_PASS, DB_NAME.
    """
    return pymysql.connect(
        host=os.getenv("DB_HOST", "3.7.255.145"),
        user=os.getenv("DB_USER", "Tarun"),
        password=os.getenv("DB_PASS", "db_tarunji!123"),
        database=os.getenv("DB_NAME", "vowsls"),
        autocommit=False,
        cursorclass=pymysql.cursors.DictCursor,
        charset="utf8mb4"
    )
