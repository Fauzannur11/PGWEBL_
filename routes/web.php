<?php

use App\Http\Controllers\BerandaController;
use App\Http\Controllers\PointsController;
use App\Http\Controllers\PolygonsController;
use App\Http\Controllers\PolylinesController;
use App\Http\Controllers\TableController;
use Illuminate\Support\Facades\Route;

Route::get('/map', [PointsController::class, 'index'])->name('map');
Route::get('/table', [TableController::class, 'index'])->name('table');
Route::get('/', [PointsController::class, 'index'])->name('beranda');
Route::resource('points', PointsController::class);
Route::resource('polylines', PolylinesController::class);
Route::resource('polygons', PolygonsController::class);
