<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    const SUPPORT_AGENT = 'support_agent';

    protected $fillable = [
        'name',
    ];

    public $timestamps = false;
}