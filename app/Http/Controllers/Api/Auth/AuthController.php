<?php

namespace App\Http\Controllers\Api\Auth;

// Controller   
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CheckEmailRequest;
use App\Http\Requests\Auth\CheckOTPRequest;
use App\Http\Requests\Auth\ForgetPassword;
// Requests
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ResetPassword;
// Illuminate
use Illuminate\Support\Facades\Password;
// Models
use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    // Get a JWT via given credentials.
    public function login(LoginRequest $request)
    {
        $token = auth()->attempt($request->validated());
        if (!$token) {
            return messageResponse('Email Or Password is not correct', 401);
        }
        return authResponse($token, 'Login Successfully');
    }

    // Get the authenticated User.
    public function me()
    {
        return contentResponse(auth()->user());
    }

    public function authRole()
    {
        $user = auth()->user();
        $rolesWithPermissions = $user->roles()->with('permissions:name')->get();
        $rolesWithPermissions = $rolesWithPermissions->map(function ($role) {
            return [
                'id' => $role->id,
                'role' => $role->name,
                'display_name' => $role->display_name,
                'description' => $role->description,
                'permissions' => $role->permissions->pluck('name'), // Extract permission names
            ];
        });

        return response()->json($rolesWithPermissions);
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
    public function refresh()
    {
        $token = auth()->refresh();
        return response()->json(['token' => $token]);
    }

    // Email Check.
    public function emailCheck(CheckEmailRequest $request)
    {
        $user = User::where('email', $request->validated('email'));
        return $user ? messageResponse() : messageResponse('Failed, Email not found..!', 404);
    }

    // Send OTP.
    public function otpSend(Request $request)
    {
        $user = User::firstWhere('email', $request->email);
        $user->notify(new VerifyEmailNotification());
        return messageResponse('OTP Send Successfully');
    }

    // Check OTP.
    public function otpCheck(CheckOTPRequest $request)
    {
        $checkOtp = (new Otp)->validate($request->validated('email'), $request->validated('code'));
        return $checkOtp->status ? messageResponse('OTP Send Successfully') : messageResponse('Failed, ' . $checkOtp->message, 404);
    }
}
