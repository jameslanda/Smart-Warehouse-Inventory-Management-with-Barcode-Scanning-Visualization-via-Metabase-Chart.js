<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class InventoryController extends Controller
{
    public function scanItem(Request $request) {
        $response = Http::post('http://localhost:5001/rfid-scan', [
            'sku' => $request->sku,
            'product_name' => $request->product_name,
            'quantity' => $request->quantity,
            'status' => $request->status
        ]);

        return $response->json();
    }
}
