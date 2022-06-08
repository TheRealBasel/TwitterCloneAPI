<?php

namespace App\Http\Controllers;

use App\Models\Following;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Models\User;

class FollowingController extends Controller
{
    public function followings ($id){
        $followings_ids = Following::where('follower',$id)->pluck('following')->toArray();
        $following_users = User::find( $followings_ids );
        return UserResource::collection($following_users);

    }

    public function followers ($id){
        $followers_ids = Following::where('following',$id)->pluck('follower')->toArray();
        $followers_users = User::find( $followers_ids )->pagainte();
        return UserResource::collection($followers_users);
    }

    public function follow (Request $request){
        $request->validate([
            'following' => ['required', 'exists:users,id'],
        ]);

        $isAlreadyFollowing = Following::where('following',$request->following)->where('follower', $request->user()->id)->exists();
        
        if ( !$isAlreadyFollowing ){
            Following::create([
                'following' => $request->following,
                'follower' => $request->user()->id,
            ]);
            return response()->json([
                'success' => true
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Already following'
        ], 400);
    }

    public function unfollow (Request $request){
        $request->validate([
            'following' => ['required', 'exists:users,id'],
        ]);

        if ( Following::where('following',$request->following)->where('follower', $request->user()->id)->delete() ){
            return response()->json([
                'success' => true
            ], 200);
        }
        
        return response()->json([
            'success' => false
        ], 400);
    }
}
