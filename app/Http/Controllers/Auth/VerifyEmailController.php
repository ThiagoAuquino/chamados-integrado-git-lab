<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Domain\Auth\UseCases\VerifyEmailUseCase;

class VerifyEmailController extends Controller
{
    public function __construct(
        private VerifyEmailUseCase $verifyEmail
    ) {}

    public function __invoke(Request $request): RedirectResponse
    {
        $this->verifyEmail->execute($request->user());

        return redirect()->intended('/dashboard?verified=1');
    }
}
