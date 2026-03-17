<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProdutoController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\DesperdicioController;

Route::get('desperdicios/resumo', [DesperdicioController::class, 'resumo']);

Route::apiResource('produtos', ProdutoController::class);
Route::apiResource('categorias', CategoriaController::class);
Route::apiResource('desperdicios', DesperdicioController::class);