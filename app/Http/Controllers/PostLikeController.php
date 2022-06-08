<?php

namespace App\Http\Controllers;

use App\Models\PostLike;
use App\Models\User;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class PostLikeController extends Controller
{

    public function store(Request $request){
        $request->validate([
            'post' => ['required', 'exists:posts,id'],
        ]);

        $isAlreadyLiked = PostLike::where('post',$request->post)->where('user', $request->user()->id)->exists();
        
        if ( !$isAlreadyLiked ){
            PostLike::create([
                'post' => $request->post,
                'user' => $request->user()->id
            ]);
            return response()->json([
                'success' => true
            ], 200);
        }

        return response()->json([
            'success' => false,
            'message' => 'Already liked'
        ], 400);
    }

    
    public function destroy(Request $request){
        $request->validate([
            'post' => ['required', 'exists:posts,id'],
        ]);

        if ( PostLike::where('post',$request->post)->where('user', $request->user()->id)->delete() ){
            return response()->json([
                'success' => true
            ], 200);
        }
        
        return response()->json([
            'success' => false
        ], 400);
    }

    public function show ( $id ){
        $likers_ids = PostLike::where('post',$id)->pluck('user')->toArray();
        $likes_users = User::find( $likers_ids );

        return UserResource::collection($likes_users);
    }
}
