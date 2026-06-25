<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;

Route::post('/documents', [DocumentController::class, 'store']);

