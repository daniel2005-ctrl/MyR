<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FooterInfo extends Model
{
    protected $table = 'footer_info'; // ✅ todo en minúsculas

    protected $fillable = ['direccion'];
}
