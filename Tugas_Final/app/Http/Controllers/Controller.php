<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests; // Tambahkan use ini
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @mixin \Illuminate\Foundation\Auth\Access\AuthorizesRequests // <-- TAMBAHKAN BARIS INI
 */
class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}