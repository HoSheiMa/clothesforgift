<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Chat extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function from_user()
    {
        $user = Auth::user();
        if ($user->id == $this->from) {
            return User::find($this->to);
        } else {
            return User::find($this->from);
        }
    }
}