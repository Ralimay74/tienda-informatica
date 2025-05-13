<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/admin/partes/{id}/pdf', [\App\Http\Controllers\PartePdfController::class, 'descargar'])
    ->name('admin.partes.pdf');

Route::get('/admin/clientes/{id}/politica-datos', [\App\Http\Controllers\PoliticaDatosPdfController::class, 'ver'])
    ->name('admin.clientes.politica-datos');


Route::get('/admin/clientes/{id}/politica-datos', [\App\Http\Controllers\PoliticaDatosPdfController::class, 'generar'])
    ->name('admin.clientes.politica-datos');
