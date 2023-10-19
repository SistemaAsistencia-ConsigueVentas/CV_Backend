<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\Attendance;
use App\Services\LoginService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    protected $loginService;
    protected $notificationService;

    public function __construct(LoginService $loginService, NotificationService $notificationService)
    {
        $this->loginService = $loginService;
        $this->notificationService = $notificationService;
    }

    public function __invoke(LoginRequest $request): JsonResponse
    {
        try {
            $credentials = $request->only(['username', 'password']);
            if (!$this->loginService->attempLogin($credentials)) {
                return response()->json(['message' => __('auth.unauthorized')], 401);
            }

            $loggedInUser = auth()->user();

            if (!$loggedInUser instanceof User) {
                // Manejar el caso cuando $loggedInUser no es una instancia de User
                return response()->json(['message' => 'El usuario autenticado no es válido'], 403);
            }
            if ($this->loginService->isUserBlocked($loggedInUser)) {
                return response()->json(['message' => 'La cuenta del usuario está bloqueada'], 403);
            }

            $this->notificationService->sanctionUserAbsences($loggedInUser->id);
            $this->notificationService->sanctionUserDelays($loggedInUser->id);

            if (!$loggedInUser->status) {
                return response()->json(['message' => 'Usuario deshabilitado por exceder el límite de inasistencias.']);
            }

            if (!$loggedInUser->attendance <= 4 && $loggedInUser->status) {
                $token = $this->loginService->createTokenForUser($loggedInUser);
                $user = User::where('username', $request['username'])->first(['id', 'name', 'surname', 'image', 'shift']);
                $role = $loggedInUser->roles->first();
            }

            return response()->json([
                'access_token' => $token,
                'user' => $user,
                'role' => $role
            ]);
        } catch (ValidationException $e) {
            // Manejar excepciones de validación
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (ModelNotFoundException $e) {
            // Manejar excepciones de modelo no encontrado
            return response()->json(['message' => __('auth.user_not_found')], 404);
        } // catch (\Exception $e) {
        //         // Manejar otras excepciones no esperadas
        //         return response()->json(['message' => __('auth.generic_error')], 500);
        //     }
    }
}
