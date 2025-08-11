<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\InventoryController;

Route::post('/predict-delivery-time', [DeliveryController::class, 'predict']);

Route::post('http://localhost:5001/rfid-scan', [InventoryController::class, 'rfid']);