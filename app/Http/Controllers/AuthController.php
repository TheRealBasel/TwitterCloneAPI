<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Models\Profile;

use App\Http\Resources\UserResource;

class AuthController extends Controller
{
    public function register (Request $request){

        $request->validate([
            'email' => ['required','email:rfc,dns','unique:users,email'],
            'username' => ['required','unique:users,username'],
            'password' => ['required', 'string', 'min:6'],
            'phone_number' => ['numeric', 'digits:10'],
            'first_name' => ['required', 'alpha', 'min:3', 'max:12'],
            'last_name' => ['required', 'alpha', 'min:3', 'max:12'],
            'bio' => ['string', 'max:128'],
            'date_of_birth' => ['date'],
        ]);

        $user = User::create([
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number
        ]);
        $profile = new Profile([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'bio' => $request->bio,
            'date_of_birth' => $request->date_of_birth
        ]);
        $user->profile()->save($profile);

        return response()->json( [
            'success' => true,
            'data' => [
                'user' => $user,
                'token' => $user->createToken('twitter_api')->plainTextToken,
            ],
            'message' => 'You have registerd successfully.'
        ], 201 );
    
    }

    public function login (Request $request){

        $request->validate([
            'email' => ['required', 'exists:users'],
            'password' => ['required'],
        ]);
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            return response()->json( [
                'success' => true,
                'message' => 'Successfully Logged in',
                'data' => [
                    'user' => new UserResource($user),
                    'token' => $user->createToken('twitter_api')->plainTextToken,
                ]
            ], 200 );
        }

        return response()->json( [
            'success' => false,
            'message' => 'credeaintls dont match our records'
        ], 401 );
    }

    public function logout (Request $request){

        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'success' => true,
            'message' => 'You have been successfully logged out.'
        ], 200 );
    }

    public function randomizeUserName(Request $request){
        $request->validate([
            'first_name' => ['required','alpha', 'min:3'],
            'last_name' => ['required','alpha', 'min:3'],
        ]);

        $random_username = strtolower($request->first_name). strtolower($request->last_name) . rand(pow(10, 4 - 1), pow(10, 4) -1);

        while ( User::where('username',$random_username)->exists()) {
            $random_username = strtolower($request->first_name). strtolower($request->last_name) . rand(pow(10, 4 - 1), pow(10, 4) -1);
        }
        return $random_username;
    }

}
