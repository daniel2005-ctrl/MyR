<?php

use Illuminate\Support\Facades\Cache;

if (!function_exists('salario_minimo')) {
    function salario_minimo()
    {
        if (app()->bound('cache')) {
            return Cache::get('salario_minimo', 1300000);
        }
        return 1300000; // fallback sin cache
    }
}
