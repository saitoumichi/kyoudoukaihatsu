<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BkcAppController extends Controller
{
    /**
     * BKCアプリのメインページを表示
     */
    public function index()
    {
        return view('layouts.bkc-app');
    }
}
