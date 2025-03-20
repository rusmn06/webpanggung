<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('layouts.app');
});

Route::get('/dashboard', function () {
    return view('layouts.app');
});

// menu tenaga kerja
Route::get('/tenagakerja', function () {
    return view('pages.tenagakerja.index');
});
Route::get('/tenagakerja/listrt', function () {
    return view('pages.tenagakerja.listrt');
});
    for ($i = 1; $i <= 24; $i++) {
        Route::get("/tenagakerja/listrt/{$i}", function () use ($i) {
            return view("pages.tenagakerja.listrt.{$i}");
        });
    }
    
        
// menu jamsos
Route::get('/jamsos', function () {
    return view('pages.jamsos.index');
});

// menu difabel rentan
Route::get('/difabelrentan', function () {
    return view('pages.difabelrentan.index');
});