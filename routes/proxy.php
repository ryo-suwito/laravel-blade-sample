<?php

use App\Http\Controllers\ProxyController;
use Illuminate\Support\Facades\Route;

Route::post('store/users/production-check', [ProxyController::class, 'productionCheck']);