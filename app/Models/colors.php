<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class colors extends Model
{
    protected $fillable = ['color', 'size', 'products_id', 'available'];
    use HasFactory;
}