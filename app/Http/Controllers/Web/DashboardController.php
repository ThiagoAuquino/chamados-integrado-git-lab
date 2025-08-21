<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('view-dashboard');

        // Consome API para estatísticas
        $apiUrl = config('app.url') . '/api';
        
        try {
            $stats = Http::withToken($request->user()->createToken('dashboard')->plainTextToken)
                ->get($apiUrl . '/demandas/stats')
                ->json();

            return view('pages.dashboard.index', compact('stats'));
        } catch (\Exception $e) {
            $stats = [
                'total' => 0,
                'em_branco' => 0,
                'em_analise' => 0,
                'em_execucao' => 0,
                'em_testes' => 0,
                'entregue' => 0
            ];

            return view('pages.dashboard.index', compact('stats'))
                ->with('error', 'Erro ao carregar estatísticas');
        }
    }
}