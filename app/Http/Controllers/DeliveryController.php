<?php

namespace App\Http\Controllers;

use App\Jobs\PredictDeliveryTime;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DeliveryController extends Controller
{
    public function predict(Request $request)
    {
        $data = $request->only('location', 'distance_km', 'fuel_used');

        // Call Flask microservice
        $response = Http::post('http://127.0.0.1:5000/predict', [
            'distance_km' => $data['distance_km'],
            'fuel_used'   => $data['fuel_used'],
        ]);

        $predictedTime = $response->json()['eta_minutes'];

        // Store in database
        $delivery = Delivery::create([
            'location'     => $data['location'] ?? 'Unknown',
            'distance_km'  => $data['distance_km'],
            'fuel_used'    => $data['fuel_used'],
            'time_minutes' => $predictedTime,
        ]);

        // Dispatch background job (optional)
        PredictDeliveryTime::dispatch($delivery);

        // Return response
        return response()->json([
            'predicted_time' => $predictedTime,
        ]);
    }
}
