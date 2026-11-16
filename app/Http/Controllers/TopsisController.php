<?php

namespace App\Http\Controllers;

use App\Services\TopsisService;
use Illuminate\Http\JsonResponse;

class TopsisController extends Controller
{
    protected TopsisService $topsisService;

    public function __construct(TopsisService $topsisService)
    {
        $this->topsisService = $topsisService;
    }

    public function index(): JsonResponse
    {
        $ranking = $this->topsisService->hitung();

        return response()->json([
            'status'  => 'success',
            'message' => 'Hasil perankingan kemiskinan TOPSIS Pamekasan',
            'data'    => $ranking,
        ]);
    }
}