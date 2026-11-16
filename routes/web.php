<?php

use Illuminate\Support\Facades\Route;
use App\Services\TopsisService;

Route::get('/', function (TopsisService $topsisService) {
    $data = $topsisService->hitung();
    return view('peta', compact('data'));
});

