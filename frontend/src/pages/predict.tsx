import { useState } from 'react';
import axios from 'axios';

export default function PredictPage() {
  const [location, setLocation] = useState('');
  const [distance, setDistance] = useState(0);
  const [fuel, setFuel] = useState(0);
  const [result, setResult] = useState(null);

  const handleSubmit = async () => {
    try {
      const res = await axios.post('http://localhost:8000/api/predict-delivery-time', {
        location, // added
        distance_km: distance,
        fuel_used: fuel,
      });
      setResult(res.data.predicted_time);
    } catch (error) {
      console.error('Prediction failed:', error);
    }
  };

  return (
    <div className="p-6">
      {/* Embed Metabase dashboard */}
      <iframe src="http://localhost:3000/public/dashboard/xyz" width="100%" height="600px" />

      <h2 className="text-xl font-bold mt-4">Delivery Time Predictor</h2>

      <input
        type="text"
        placeholder="Location"
        value={location}
        onChange={(e) => setLocation(e.target.value)}
        className="block border p-2 mb-2 w-full"
      />

      <input
        type="number"
        placeholder="Distance (km)"
        onChange={(e) => setDistance(Number(e.target.value))}
        className="block border p-2 mb-2 w-full"
      />

      <input
        type="number"
        placeholder="Fuel Used (liters)"
        onChange={(e) => setFuel(Number(e.target.value))}
        className="block border p-2 mb-4 w-full"
      />

      <button onClick={handleSubmit} className="bg-blue-600 text-white px-4 py-2 rounded">
        Predict
      </button>

      {result && (
        <p className="mt-4 text-green-600 font-semibold">Estimated Time: {result} mins</p>
      )}
    </div>
  );
}
