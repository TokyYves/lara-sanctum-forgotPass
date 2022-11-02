<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function check(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'already verified'
            ],200);
        }
        $request->user()->sendEmailVerificationNotification();


        return response()->json([
            'message' => 'verification link send successfully!😁'
        ],201);
    }
    public function verify(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'already verified'
            ],200);
        }
        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return response()->json([
            'message' => 'email has been verified 😋'
        ],201);
    }
}
