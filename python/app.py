from flask import Flask, jsonify
from flask_cors import CORS
import psycopg2
import os
import logging

app = Flask(__name__)
CORS(app)

logging.basicConfig(level=logging.ERROR)

def get_db_connection():
    db_host     = os.getenv('DB_HOST')     or 'localhost'
    db_name     = os.getenv('DB_NAME')     or 'Universidad'
    db_user     = os.getenv('DB_USER')     or 'postgres'
    db_password = os.getenv('DB_PASSWORD') or 'alejo0127'

    try:
        conn = psycopg2.connect(
            host=db_host,
            database=db_name,
            user=db_user,
            password=db_password
        )
        return conn
    except psycopg2.Error as e:
        app.logger.error(f"Error al conectar: {e}")
        return None

@app.route('/estudiantes', methods=['GET'])
def get_estudiantes():
    conn = None
    cur  = None
    try:
        conn = get_db_connection()
        if conn is None:
            return jsonify({"error": "No se pudo conectar a la base de datos."}), 500

        cur = conn.cursor()
        cur.execute('SELECT id, nombre, edad FROM estudiantes ORDER BY id')
        filas = cur.fetchall()

        estudiantes = [
            {"id": fila[0], "nombre": fila[1], "edad": fila[2]}
            for fila in filas
        ]
        return jsonify(estudiantes), 200

    except psycopg2.Error as e:
        app.logger.error(f"Error: {e}")
        return jsonify({"error": "Error al obtener estudiantes."}), 500

    finally:
        if cur  is not None: cur.close()
        if conn is not None: conn.close()

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True)