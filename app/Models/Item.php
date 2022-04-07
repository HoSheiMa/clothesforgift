<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $guarded = [];
    public function colors()
    {
        return $this->belongsTo(colors::class);
    }
    use HasFactory;
}