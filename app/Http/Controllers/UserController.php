<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use App\Http\Resources\UserResource;

class UserController extends Controller
{

    public function index(){
        return UserResource::collection(User::paginate());
    }
    
    public function show($username){
        return new UserResource(User::where('username', $username)->firstOrFail());
    }

}
