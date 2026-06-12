<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SyariatController extends Controller
{
    public function index(){
        return view('dashboards.syariat.index');
    }
}
