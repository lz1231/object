<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    // 设置模型表名
    public $table = 'users';

    // 获取用户头像
    public function userinfo()
    {
    	return $this->hasOne('App\Models\Usersinfo','uid');
    }


}
