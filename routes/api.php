<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProdutoController;
use App\Http\Controllers\Api\CategoriaController;

Route::apiResource('produtos', ProdutoController::class);
Route::apiResource('categorias', CategoriaController::class);