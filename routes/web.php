<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/mostrarVista', 'personasController@mostrarVista')->name('mostrarVista');
Route::get('/mostrarDatos/{rut}', 'personasController@mostrarDatos')->name('mostrarDatos');
Route::post('/enviarDatos', 'personasController@enviarDatos')->name('enviarDatos');
Route::post('/editarDatos','personasController@editarDatos')->name('editarDatos');
Route::post('/elminarFila','personasController@elminarFila')->name('elminarFila');