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

        $apiUrl = config('app.url') . '/api';

        try {
            $token = $request->user()->createToken('dashboard')->plainTextToken;

            $statistics = Http::withToken($token)
                ->get($apiUrl . '/demandas/stats')
                ->json();

            $demandas = Http::withToken($token)
                ->get($apiUrl . '/demandas/kanban')
                ->json();

            $chart_data = Http::withToken($token)
                ->get($apiUrl . '/demandas/charts')
                ->json();

            $recent_activities = Http::withToken($token)
                ->get($apiUrl . '/logs/recent')
                ->json();

            return view('dashboard.index', compact(
                'statistics',
                'demandas',
                'chart_data',
                'recent_activities'
            ));
        } catch (\Exception $e) {

            $stats = [
                'total' => 0,
                'em_branco' => 0,
                'em_analise' => 0,
                'em_execucao' => 0,
                'em_testes' => 0,
                'entregue' => 0
            ];

            return view('dashboard.index', compact('stats'))
                ->with('error', 'Erro ao carregar estatísticas');
        }        
    }
}
