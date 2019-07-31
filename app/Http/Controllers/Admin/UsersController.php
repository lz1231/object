<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\UsersStore;
use App\Models\Users;
use App\Models\Usersinfo;
use Hash;
use DB;
use Storage;

class UsersController extends Controller
{
    /**
     * 后台首页
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->input('search','');
        //获取数据
        $users = Users::where('uname','like','%'.$search.'%')->paginate(2);
        //加载模板
        return view('admin.users.index',['users'=>$users,'requests'=>$request->input()]);
    }

    /**
     * 显示添加用户页面
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * 执行添加用户操作
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UsersStore $request)
    {
        // dump($request->input());
        // 检测用户头像是否上传
        if ($request->hasFile('profile')) {
            //如果头像上传则获取头像
            $path = $request->file('profile')->store(date('Ymd'));
        }else{
            //如果头像未上传则返回重新选取
            return back();
        }
        //开启事务
        DB::beginTransaction();
        // 实例化模型
        $user = new Users;
        //将数据存入数据库
        $user->uname = $request->input('uname','');
        $user->upass = Hash::make($request->input('upass',''));
        $user->email = $request->input('email','');
        $user->phone = $request->input('phone','');
        $res1 = $user->save();
        //添加用户头像
        $userinfo = new Usersinfo;
        $userinfo->uid = $user->id;
        $userinfo->profile = $path;
        $res2 = $userinfo->save();
        if($res1 && $res2){
            DB::commit();
            return redirect('admin/users')->with('success', '添加成功');
        }else{
            DB::rollBack();
            return back()->with('error', '添加失败');
        }
    }

    /**
     * 显示详情页面
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * 显示修改页面
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Users::find($id);
        $userinfo = Usersinfo::where('uid',$id)->first();
        $user->profile = $userinfo->profile;
        //加载视图
        return view('admin.users.edit',['user'=>$user]);
    }

    /**
     * 执行修改操作
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       // 检查用户是否有文件上传
        if(!$request->hasFile('profile')){
            $user = Users::find($id);
            $user->email = $request->input('email','');
            $user->phone = $request->input('phone','');
            if($user->save()){
                return redirect('admin/users')->with('success','修改成功');
            }else{
                return back()->with('error','修改失败');
            }

        }else{
            // 开启事务
            DB::beginTransaction();

            // 接收文件上传
            $path  = $request->file('profile')->store(date('Ymd'));

            $usersinfo = Usersinfo::where('uid',$id)->first();
            // 删除图片
            Storage::delete([$usersinfo->profile]);

            // 给用户设置新的图片
            $usersinfo->profile = $path;
            // 执行修改
            $res1 = $usersinfo->save();

            // 修改用户的主信息
            $user = Users::find($id);
            $user->email = $request->input('email','');
            $user->phone = $request->input('phone','');
            $res2 = $user->save();

            if($res1 && $res2){
                DB::commit();
                return redirect('admin/users')->with('success','修改成功');
            }else{
                DB::rollBack();
                return back()->with('error','修改失败');
            }
        }   
    }

    /**
     * 执行删除操作
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //开启事务
        DB::beginTransaction();
        //删除主用户
        $res1 = Users::destroy($id);
        //获取用户头像
        $userinfo = Usersinfo::where('uid',$id)->first();
        $path = $userinfo->profile;
        //删除用户详情
        $res2 = Usersinfo::where('uid',$id)->delete();
        //判断
        if($res1 && $res2){
            //删除图片
            Storage::delete([$path]);
            //提交事务
            DB::commit();
            return redirect('admin/users')->with('success', '删除成功');
        }else{
            //事务回滚
            DB::rollBack();
            return back()->with('error', '删除失败');
        }
    }
}
