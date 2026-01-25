import mysql.connector

DB_CONFIG = {
    "host": "3.7.255.145",
    "user": "Tarun",      # TODO
    "password": "db_tarunji!123",  # TODO
    "database": "vowsls",     # TODO
}

def get_connection():
    return mysql.connector.connect(**DB_CONFIG)

 