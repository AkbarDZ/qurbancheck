<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class KesehatanController extends Controller
{
    public function index(){
        return view('dashboards.kesehatan.index');
    }
    
}
