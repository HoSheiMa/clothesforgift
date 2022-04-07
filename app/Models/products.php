<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class products extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function colors()
    {
        return $this->hasMany(colors::class);
    }
    public function images()
    {
        return $this->hasMany(images::class);
    }
}