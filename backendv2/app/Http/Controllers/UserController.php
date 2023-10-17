<?php

declare(strict_types=1);

namespace App\Http\Controllers;


use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Attendance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Models\User;

class UserController extends Controller
{
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function getAllUsers(Request $request)
    {
        $users = $this->userService->getFilteredUsers($request->all());
        return response()->json($users);
    }

    public function getUsersByID($id)
    {
        $userData = $this->userService->getUserDetails($id);
        if (is_null($userData)) {
            return response()->json(['message' => 'No encontrado'], 404);
        }
        return response()->json($userData);
    }

    public function updateUsers(Request $request, $user): JsonResponse
    {
        try {
            $user = $this->userService->update($user, $request->all());

            return response()->json([
                'message' => 'User updated successfully',
            ], 200);

        } catch (\Exception $e) {
            print ($e);
            return response()->json(['error' => 'User update failed'], 500);
        }
    }
}
