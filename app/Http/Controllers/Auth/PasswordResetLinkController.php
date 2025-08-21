<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use App\Domain\Auth\DTOs\SendPasswordResetLinkDTO;
use App\Domain\Auth\UseCases\SendPasswordResetLinkUseCase;
use Illuminate\Support\Facades\Password;

class PasswordResetLinkController extends Controller
{
    public function __construct(
        private SendPasswordResetLinkUseCase $sendResetLink
    ) {}

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $dto = SendPasswordResetLinkDTO::fromArray($request->only('email'));
        $status = $this->sendResetLink->execute($dto);

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('status', __($status));
        }

        throw ValidationException::withMessages([
            'email' => [trans($status)],
        ]);
    }
}
