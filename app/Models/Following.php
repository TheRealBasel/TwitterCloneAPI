<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Following extends Model
{
    use HasFactory;

    protected $fillable = [
        'following',
        'follower'
    ];

    public function following(){
        return $this->hasOne(User::class);
    }
    public function follower(){
        return $this->hasOne(User::class);
    }
}
