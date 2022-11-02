<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\UserSocial;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialLoginController extends Controller
{

    public function __construct()
    {
        $this->middleware('social');
    }

    public function redirect(string $service)
    {
        return Socialite::driver($service)->stateless()->redirect();
    }
    public function callback(string $service)
    {
       $serviceUser =  Socialite::driver($service)->stateless()->user();
       
       $user = User::where('email', $serviceUser->getEmail())->first();

       if (!$user) {
        $user = User::create([
            'name' => $serviceUser->getName(),
            'email' => $serviceUser->getEmail(),
            'password' => Hash::make('unknown')
        ]);
       }

       if ($this->needToCreateSocial($user,$service)) {
        UserSocial::firstOrCreate([
            'user_id' => $user->id,
            'social_id' => $serviceUser->getId(),
            'service' => $service
        ]);
       }
       $oat = $user->createToken("API TOKEN")->plainTextToken;
       return redirect(env('CLIENT_BASE_URL').'/auth/social/callback?token='. $oat);
    }
    public function needToCreateSocial(User $user ,string $service)
    {
        return !$user->hasSocialLinked($service);
    } 
}
