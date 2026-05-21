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

@app.route('/clientes_pedidos', methods=['GET'])
def get_clientes_pedidos():
    conn = None
    cur  = None
    try:
        conn = get_db_connection()
        if conn is None:
            return jsonify({"error": "No se pudo conectar a la base de datos."}), 500

        cur = conn.cursor()
        cur.execute("""
            SELECT
                c.Dni,
                c.NombreCompleto,
                c.Apellido,
                p.npedido,
                p.fecha,
                p.Cantidad
            FROM Cliente c
            LEFT JOIN Pedidos p ON c.Dni = p.cliente_Dni
            ORDER BY c.Dni, p.fecha
        """)
        resultados = cur.fetchall()

        clientes_pedidos = {}
        for row in resultados:
            dni, nombre, apellido, npedido, fecha, cantidad = row
            if dni not in clientes_pedidos:
                clientes_pedidos[dni] = {
                    'Dni':      dni,
                    'Nombre':   nombre,
                    'Apellido': apellido,
                    'Pedidos':  []
                }
            if npedido is not None:
                clientes_pedidos[dni]['Pedidos'].append({
                    'npedido':  npedido,
                    'fecha':    str(fecha),
                    'Cantidad': float(cantidad)
                })

        return jsonify(list(clientes_pedidos.values())), 200

    except psycopg2.Error as e:
        app.logger.error(f"Error al obtener clientes y pedidos: {e}")
        return jsonify({"error": "Error al obtener datos."}), 500

    finally:
        if cur  is not None: cur.close()
        if conn is not None: conn.close()

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5001, debug=True)