<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domain\Auth\UseCases\SendEmailVerificationUseCase;

class EmailVerificationNotificationController extends Controller
{
    public function store(Request $request, SendEmailVerificationUseCase $useCase)
    {
        $user = $request->user();

        $useCase->execute($user);

        return back()->with('status', 'Link de verificação enviado!');
    }
}
