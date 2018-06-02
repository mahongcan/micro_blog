<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
class UsersController extends Controller
{

    /**
     * 未登录用户能访问  登录用户访问
    */
    public function __construct()
    {
        $this->middleware('auth',[
            'except' => ['show', 'create', 'store', 'index']
        ]);
//        只让未登录用户访问注册
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    /**
     * 用户列表
    */

    public function index()
    {
       $users = User::paginate(10);
        return view('users.index',compact('users'));
    }

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

    /**
     * 修改用户界面
    */
    public function edit(User $user)
    {
        $this->authorize('update',$user);  //用户授权
        return view('users.edit',compact('user'));
    }

    /**
     * 执行修改
    */
    public function update(Request $request, User $user)
    {
        $this->validate($request,[
            'name' => 'required|max:18',
            'password' => 'nullable|confirmed|min:6'
        ]);
        $this->authorize('update', $user);  //用户授权
        $data['name'] = $request->name;

        if($request->password) {
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);
        session()->flash('success','个人资料更新成功！');
        return redirect()->route('users.show', $user);
    }

    /**
     * 删除用户
    */
    public function destroy(User $user)
    {
        $user->delete();
        $this->authorize('destroy', $user); //
        session()->flash('success', '成功删除用户！');
        return back();
    }
}
