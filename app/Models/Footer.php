<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Footer extends Model
{
    protected $fillable = [
        'direccion',
        'gmail_url',
        'whatsapp_url', 
        'facebook_url',
        'gmail_icon',
        'whatsapp_icon',
        'facebook_icon',
        'additional_socials'
    ];

    protected $casts = [
        'additional_socials' => 'array'
    ];
}
