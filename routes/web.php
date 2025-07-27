<?php

use App\Events\MessageSent;
use App\Http\Controllers\PeopleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/ss', function () {
    return view('firebase');
});
