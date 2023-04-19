<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Requests\User\UserLoginRequest;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login(UserLoginRequest $request): JsonResponse
    {
        $userData = $this->userRepository->getUserData($request['email']);
        if (empty($userData)) {
            return response()->json([
                'error' => 'There is no account matching for you email.'
            ], 404);
        }

        $authStatus = Auth::attempt(['email' => $request['email'], 'password' => $request['password']]);

        if ($authStatus) {
            $user = Auth::user();
            $accessToken = $user->createToken('Laravel Password Grant Client')->accessToken;
            $loggedUserData['token'] = $accessToken;
            $loggedUserData['name'] = $user->name;
            $loggedUserData['email'] = $user->email;

            return response()->json([
                'success' => $loggedUserData,
                'message' => 'User is logged in.'],
                200);
        } else {
            return response()->json([
                'error' => 'UnAuthorized. Please enter the correct password.'
            ], 400);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out',
        ], 200);
    }
}
