<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Services\RegisterService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller {
    protected $userService;

    public function __construct(RegisterService $RegisterService)
    {
        $this->userService = $RegisterService;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        try {

            $user = $this->userService->register($request->validated());

            return response()->json([
                'message' => 'Registration successful',
            ], 201);

        } catch (\Exception $e) {
            print ($e);
            return response()->json(['error' => 'Registration failed'], 500);
        }
    }
}
