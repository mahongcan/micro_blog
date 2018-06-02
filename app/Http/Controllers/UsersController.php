<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
class UsersController extends Controller
{
    /**
     * 注册登录页
    */
    public function create()
    {
        return view('users.create');
    }

    /**
     * 用户详情页
    */
    public function show(User $user)
    {
        return view('users.show',compact('user')); //compact转换为关联数组
    }

    /**
     * 执行注册
    */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:18',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);

        //执行注册
        $user = User::create([
            'name' => $request->name,
            'password' => bcrypt($request->password),
            'email' => $request->email,
        ]);

        //注册成功自动登录
        Auth::login($user);
        //显示用户注册成功信息 重定向显示详情页
        session()->flash('success','欢迎，您将在这里开启一段新的旅程~');
        return redirect()->route('users.show',[$user]);
    }

}
