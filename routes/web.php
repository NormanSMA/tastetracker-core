<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check() && auth()->user()->role === 'manager') {
        return redirect('/app');
    }
    return redirect('/admin');
});
