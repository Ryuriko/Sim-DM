<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Pages\Auth\Register as BaseRegister;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class Register extends BaseRegister
{
    protected function handleRegistration(array $data): User
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Assign role 'user'
        $user->assignRole('User');

        return $user;
    }

    protected function afterRegistration(): void
    {
        Auth::login($this->getRegisteredUser());
    }
}
