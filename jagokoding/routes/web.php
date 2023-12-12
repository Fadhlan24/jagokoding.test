<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BarangmasukController;
use App\Http\Controllers\BarangkeluarController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/stembayo', function () {
    return "welcome to SIJA";
});

Route::get('/', function () {
    return view('welcome');
});

Route::resource('siswa',SiswaController::class)->middleware('auth');
Route::resource('barang', BarangController::class)->middleware('auth');
Route::resource('kategori', KategoriController::class)->middleware('auth');
Route::resource('barangmasuk', BarangmasukController::class)->middleware('auth');
Route::resource('barangkeluar', BarangKeluarController::class)->middleware('auth');

Route::group(['middleware' => 'guest'], function () {
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'registerPost'])->name('register');
    Route::get('/login', [AuthController::class, 'login'])->name('login')->middleware('guest');
    Route::post('/login', [AuthController::class, 'loginPost'])->name('login');
});
 
Route::group(['middleware' => 'auth'], function () {
    Route::delete('/logout', [AuthController::class, 'logout'])->name('logout');
});

