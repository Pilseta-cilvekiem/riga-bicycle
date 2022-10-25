<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\PlaceController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\WeatherController;

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
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::prefix("dashboard")->group(function(){

        Route::get('/', function () {
            return Inertia::render('Dashboard');
        })->name('dashboard');

        Route::prefix("/places")->group(function () {
            Route::get('/', [PlaceController::class, 'index'])->name('dashboard.places');
            Route::get('/create', [PlaceController::class, 'create'])->name('dashboard.places.create');
            Route::get('/edit/{id}', [PlaceController::class, 'edit'])->name('dashboard.places.edit');
            Route::post('/store', [PlaceController::class, 'store'])->name('dashboard.places.store');
            Route::patch('/update/{id}', [PlaceController::class, 'update'])->name('dashboard.places.update');
            Route::delete('/destroy/{id}', [PlaceController::class, 'destroy'])->name('dashboard.places.destroy');
            Route::get('/places-list', [PlaceController::class, 'getPlaces'])->name('api.places.getplaces');
        });

        Route::prefix("/events")->group(function () {
            Route::get('/', [EventController::class, 'index'])->name('dashboard.events');
            Route::get('/create', [EventController::class, 'create'])->name('dashboard.events.create');
            Route::get('/edit/{id}', [EventController::class, 'edit'])->name('dashboard.events.edit');
            Route::post('/store', [EventController::class, 'store'])->name('dashboard.events.store');
            Route::patch('/update/{id}', [EventController::class, 'update'])->name('dashboard.events.update');
            Route::delete('/destroy/{id}', [EventController::class, 'destroy'])->name('dashboard.events.destroy');
            Route::get('/events-list', [EventController::class, 'getEvents'])->name('api.events.getevents');
        });

        Route::prefix("/weather")->group(function(){
            Route::post('/get', [WeatherController::class, 'getWeather'])->name('dashboard.weather.get');
        });
    });
});
