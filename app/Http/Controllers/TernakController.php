<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TernakController extends Controller
{
    public function index(){
        return view('dashboards.ternak.index');
    }
}
