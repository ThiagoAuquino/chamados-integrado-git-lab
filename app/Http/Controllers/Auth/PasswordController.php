<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use App\Domain\Auth\DTOs\UpdatePasswordDTO;
use App\Domain\Auth\UseCases\UpdatePasswordUseCase;

class PasswordController extends Controller
{
    public function __construct(
        private UpdatePasswordUseCase $updatePasswordUseCase
    ) {}

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $dto = UpdatePasswordDTO::fromArray([
            'user' => $request->user(),
            'current_password' => $validated['current_password'],
            'password' => $validated['password'],
        ]);

        $this->updatePasswordUseCase->execute($dto);

        return back()->with('status', 'password-updated');
    }
}
