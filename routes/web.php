<?php

use App\Http\Controllers\Test\TestController;
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

Route::prefix('internalApi')->namespace('Test')->group(function(){
    //首页
    Route::any('/cache', [TestController::class, 'testCache']);
    Route::any('/queue', [TestController::class, 'testQueue']);
});
