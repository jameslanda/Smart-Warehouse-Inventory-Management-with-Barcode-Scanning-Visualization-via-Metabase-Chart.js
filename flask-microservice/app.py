from flask import Flask, request, jsonify
from flask_cors import CORS
import joblib
import redis
from rq import Queue
import numpy as np
from datetime import datetime
import psycopg2

app = Flask(__name__)
CORS(app)

# Redis queue
r = redis.Redis()
q = Queue(connection=r)

# Dummy ML model (linear regression)
def train_model():
    from sklearn.linear_model import LinearRegression
    X = np.array([[1, 1], [2, 2], [3, 1.5], [4, 3]])
    y = np.array([10, 20, 22, 35])
    model = LinearRegression().fit(X, y)
    joblib.dump(model, 'model.pkl')

@app.route('/predict', methods=['POST'])
def predict():
    data = request.json
    X = [[data['distance_km'], data['fuel_used']]]

    model = joblib.load('model.pkl')
    prediction = model.predict(X)[0]
    return jsonify({'eta_minutes': round(prediction, 2)})

# Microservice for Barcode Scanning
@app.route('/rfid-scan', methods=['POST'])

def scan_barcode():
    data = request.json

    conn = psycopg2.connect(
        dbname="logistics_db",
        user="postgres",
        password="root123",
        host="localhost", 
        port="5432"
    )
    cur = conn.cursor()

    cur.execute("""
        INSERT INTO inventory (sku, product_name, quantity, last_scanned, status)
        VALUES (%s, %s, %s, %s, %s)
    """, (
        data['sku'],
        data['product_name'],
        data['quantity'],
        datetime.now(),
        data['status']
    ))

    conn.commit()
    cur.close()
    conn.close()

    return jsonify({"message": "Scanned successfully"})

if __name__ == '__main__':
    app.run(debug=True, port=5001)