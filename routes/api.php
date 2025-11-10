<?php

use App\Http\Controllers\IndicacaoController;
use Illuminate\Support\Facades\Route;

Route::apiResource('indicacao', IndicacaoController::class);