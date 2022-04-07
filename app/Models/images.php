<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class images extends Model
{
    protected $fillable = ['url', 'products_id', 'name'];
    use HasFactory;
}