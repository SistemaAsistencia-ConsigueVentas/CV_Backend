<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginService {

    public function attempLogin(array $credentials) 
    {
        try {
            if (Auth::attempt($credentials)) {
                return true;
            }
            return false;
        } catch (\Exception $e) {
            throw new \Exception('Error al intentar iniciar sesión.', 500);
        }
    }

    public function createTokenForUser(User $user): string
    {
        try {
            return $user->createToken('User Access Token')->plainTextToken;
        } catch (\Exception $e) {
            throw new \Exception('Error al crear el token de acceso para el usuario.', 500);
        }
    }

    public function getUserByCredentials(array $credentials): ?User
    {
        try {
            return User::where('username', $credentials['username'])->first();
        } catch (\Exception $e) {
            throw new \Exception('Error al obtener el usuario por las credenciales proporcionadas.', 500);
        }
    }
    
    public function isUserBlocked(User $user): bool
    {
        try {
            return $user->status == 0;
        } catch (\Exception $e) {
            throw new \Exception('Error al verificar si el usuario está bloqueado.', 500);
        }
    }
}