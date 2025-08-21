<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Domain\Auth\DTOs\LoginDTO;
use App\Domain\Auth\UseCases\LoginUseCase;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function __construct(
        private LoginUseCase $loginUseCase
    ) {}

    /**
     * Exibe a tela de login (apenas web).
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Processa o login (web e API).
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $dto = LoginDTO::fromRequest($request);

        $this->loginUseCase->execute($dto);

        return redirect()->intended(route('dashboard'));
    }

    /**
     * Faz logout.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
