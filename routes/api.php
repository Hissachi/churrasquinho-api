<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProdutoController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\DesperdicioController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\MovimentacaoController;

Route::get('/dashboard', [DashboardController::class, 'index']);
Route::get('desperdicios/resumo', [DesperdicioController::class, 'resumo']);
Route::get('/movimentacoes', [MovimentacaoController::class, 'index']);
Route::post('/movimentacoes', [MovimentacaoController::class, 'store']);

Route::apiResource('produtos', ProdutoController::class);
Route::apiResource('categorias', CategoriaController::class);
Route::apiResource('desperdicios', DesperdicioController::class);