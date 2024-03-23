<?php

use App\Livewire\JoinForm;
use App\Livewire\Welcome;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/join', JoinForm::class)->name('join-form');
Route::get('/welcome', Welcome::class)->name('welcome');
Route::view('/', 'home')->name('home');
Route::view('/membership', 'membership')->name('membership');
Route::view('/gallery', 'gallery')->name('gallery');
Route::view('/about-us', 'about-us')->name('about-us');


Route::get('/debug', function () {

$user = Auth::user();
return ($user->role(['admin', 'sales'])) ? true : false;


});
 