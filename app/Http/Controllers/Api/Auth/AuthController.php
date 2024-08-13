<?php

namespace App\Http\Controllers\Api\Auth;

// Controller
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgetPassword;
// Requests
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPassword;
// Illuminate
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Request;
// Models
use App\Models\User;

class AuthController extends Controller
{
    // Get a JWT via given credentials.
    public function login(LoginRequest $request)
    {
        $token = auth()->attempt($request->validated());
        if (!$token) {
            return messageResponse('Email Or Password is not correct', 401);
        }
        return authResponse($token, auth()->user(), 'Login Successfully');
    }

    // Get a JWT via given registred.
    public function register(RegisterRequest $request)
    {
        $user = User::create($request->validated());

        if (!$user) {
            return messageResponse('An error occured during registred account..!!', 500);
        }

        $token = auth()->login($user);
        return authResponse($token, 'Login Successfully');
    }

    // Get the authenticated User.
    public function me()
    {
        return contentResponse(auth()->user());
    }

    // Get the authenticated User Role.
    public function authRole()
    {
        return response()->json(auth()->user()->roles);
    }

    // Log the user out (Invalidate the token).
    public function logout()
    {
        auth()->logout();
        return messageResponse('Logged Out Successfully');
    }

    // Forget Password
    public function forgetPassowrd(ForgetPassword $request)
    {
        $status = Password::sendResetLink($request->validated());
        return $status === Password::RESET_LINK_SENT ? messageResponse('Reset Link Send Successfully') : messageResponse($status, 429);
    }

    // Reset Password
    public function resetPassword(ResetPassword $request)
    {
        $status = Password::reset($request->validated(), function (User $user, string $password) {
            $user->update(['password' => $password]);
        });
        return $status === Password::PASSWORD_RESET ? messageResponse('Password Reset Successfully') : messageResponse('Failed, Error occured when reseting password', 403);
    }

    // Refresh a token.
    public function refresh(Request $request)
    {
        $token = auth()->refresh();
        return response()->json(['token' => $token]);
    }

    // Get a token by id
    public function tokenById($id)
    {
        $token = auth()->tokenById($id);
        return authResponse($token, 'Get Token By ID Successfully');
    }
}
