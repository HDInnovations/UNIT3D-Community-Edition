<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Privileges extends Controller
{
    public function index () {
        return view('Staff.privileges.index');
    }
}
