<?php

use App\Livewire\JoinForm;
use App\Livewire\Welcome;
use App\Models\Attendance;
use Carbon\Carbon;
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

/* 
Route::get('/', function () {

    $attendences = Attendance::query()->where('created_at', '>', Carbon::now()->subDays(100))->latest()->get()->groupBy(function ($item) {
        return $item->created_at->format('d-M-y');
    });

    foreach($attendences as $attendence){
        echo '<p>'. $attendence->first()->created_at->format('d-M-y') .' :: '. $attendence->count(). '</p>'.PHP_EOL;
    }

    return;
    return view('welcome');
});
 */