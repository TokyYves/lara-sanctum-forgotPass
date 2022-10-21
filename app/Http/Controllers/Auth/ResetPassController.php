<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Notifications\PasswordResetNotification;

class ResetPassController extends Controller
{

    public function forgot(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (is_null($user)) {
            return response()->json([
                'status' => false,
                'message' => 'this email doesn\'t exist.',
            ], 404);
        }

        $resetPasswordToken = str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);

        $userPassReset = PasswordReset::where('email', $user->email)->first();

        if (!$userPassReset) {
            PasswordReset::create([
                'email' => $user->email,
                'token' => $resetPasswordToken,
            ]);
        } else {
            $userPassReset->update([
                'email' => $user->email,
                'token' => $resetPasswordToken,
            ]);
        }

        $user->notify(
            new PasswordResetNotification(
                $resetPasswordToken,
            )
        );

        return response()->json([
            'message' => 'notification send in your email',
        ]);
    }

    public function reset(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (is_null($user)) {
            return response()->json([
                'status' => false,
                'message' => 'this email doesn\'t exist.',
            ], 404);
        }

        $resetRequest = PasswordReset::where('email', $request->email)->first();

        if(!$resetRequest || $resetRequest->token =! $request->token){
            return response()->json([
                'status' => false,
                'message' => 'please try again, token mismatch.',
            ], 400);
        }

        $user->fill([
            'password' => Hash::make($request->password)
        ]);

        $user->save();

        $user->tokens()->delete();

        $resetRequest->delete();

        $token = $user->createToken('new Token')->plainTextToken;


        return response()->json([
            'user' => $user,
            'token' => $token,
            'message' => 'password reset success.',
        ], 201);
    }

}
