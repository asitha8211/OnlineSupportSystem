<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    const STATUS_OPEN = true;
    const STATUS_CLOSED = false;

    protected $fillable = [
        'name',
        'reference_id',
        'description',
        'phone_number',
        'status',
        'email'
    ];
}