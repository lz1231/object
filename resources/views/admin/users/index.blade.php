@extends('admin.layout.index')
@section('content')
	<!-- <div id="mws-searchbox" class="mws-inset"> -->
    	
    <!-- </div> -->
	<div class="mws-panel grid_8">
    	<div class="mws-panel-header">
        	<span><i class="icon-table"></i> 用户列表</span>
	        <form style="margin-left:950px;margin-top:-25px" action="/admin/users" method="get">
	        	<input type="text" name="search" class="mws-search-input" placeholder="用户名" value="{{$requests['search'] or ''}}">
	            <button type="submit" class="mws-search-submit"><i class="icon-search"></i></button>
	        </form>
        </div>
        <div class="mws-panel-body no-padding">
            <table class="mws-table">
                <thead>
                    <tr>
                    	<th>ID</th>
                        <th>用户名</th>
                        <th>邮箱</th>
                        <th>手机号</th>
                        <th>头像</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                	@foreach($users as $k=>$v)
                    <tr>
                        <td>{{$v->id}}</td>
                        <td>{{$v->uname}}</td>
                        <td>{{$v->email}}</td>
                        <td>{{$v->phone}}</td>
                        <td>
                        	<img style="width:50px;height:50px;text-align:center" src="/uploads/{{$v->userinfo->profile}}">
                        </td>
                        <td>
                        	<form style="display: inline" action="/admin/users/{{$v->id}}" method="post">
                        	{{csrf_field()}}
                        	{{method_field('DELETE')}}
								<input type="submit" value="删除" class="btn btn-danger">
                        	</form>
							<a href="/admin/users/{{$v->id}}/edit" class="btn btn-danger">修改</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div id="page_page">
				{{$users->appends($requests)->links()}}
            </div>
        </div>
    </div>
@endsection