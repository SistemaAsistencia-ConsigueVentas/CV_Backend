<?php

namespace App\Http\Controllers\Password;

use App\Http\Controllers\Controller;
use App\Http\Requests\PasswordRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ChangeController extends Controller
{
    public function __invoke(PasswordRequest $request): JsonResponse
    {
        $user = $request->user();
        if(Hash::check($request->old_password, $user->password)){
            $user->update([
                'password' => Hash::make($request->password)
            ]);
            return response()->json([
                'message' => 'Contraseña cambiado correctamente',
            ],200);
        }else{
            return response()->json([
                'message' => 'La contraseña antigua no es correcta',
            ],400);
        }
    }
}
