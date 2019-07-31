<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Users;
use App\Models\Usersinfo;
use Hash;
use Mail;

class RegisterController extends Controller
{
    public function index()
    {
    	//加载模板
    	return view('home.register.index');
    }

    public function store(Request $request)
    {
    	if($request->input('upass') != $request->input('repass')){
    		echo "<script>alert('两次密码不一致');location.href='/home/register';</script>";
    		exit;
    	}

    	//注册
    	$users = new Users;
    	$users->email = $request->input('email','');
    	$users->upass = Hash::make($request->input('upass',''));
    	$users->token = str_random(30);
    	// dump($users);die;
    	if($users->save()){
    		$uid = $users->id;

    		$usersinfo = new Usersinfo;

    		$usersinfo->profile = '20190724/Rz6F0FjVombhyTCr5HqxjgODTCRlnkAvRxIfmXt6.png';
    		
    		if($usersinfo->save()){
    			// 发送邮件 mail::send()=>return view()
    			Mail::send('home.email.email', ['id' => $users->id,'token'=>$users->token], function ($m) use ($users) {

		            $m->to($users->email)->subject('【lamp软件学院】注册激活邮件！');
		        });
    		}
    		echo "注册成功";
    	}else{
    		echo "注册失败";
    	}
    }

    public function  changeStatus(Request $request)
    {
    	$id = $request->input('id',0);
    	$token = $request->input('token',0);

    	$user = Users::find($id);

    	if($user->token != $token){
    		dd('链接失效');
    	}
    	
    	$user->status = 1;

    	$user->token = str_random(30);
    	
    	if($user->save()){
    		echo '激活成功';
    	}else{
    		echo '激活失败';
    	}
    }
}
