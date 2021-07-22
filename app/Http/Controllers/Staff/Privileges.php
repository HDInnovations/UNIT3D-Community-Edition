<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;

class Privileges extends Controller
{
    public function index()
    {
        return view('Staff.privileges.index');
    }
}
