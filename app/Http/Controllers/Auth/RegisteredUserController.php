<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Domain\Auth\DTOs\RegisterUserDTO;
use App\Domain\Auth\UseCases\RegisterUserUseCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request, RegisterUserUseCase $useCase)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $dto = RegisterUserDTO::fromRequest($request);
        $user = $useCase->execute($dto);

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
