<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class indexController extends Controller
{
    //前台首页
    public function index()
    {
    	//显示模板
    	return view('home.index.index');
    }
}
