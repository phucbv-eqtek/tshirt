<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as AppController;

class Controller extends AppController
{
    public function __construct() {

    }

    public function index() {
        return view('frontend.index');
    }
}
