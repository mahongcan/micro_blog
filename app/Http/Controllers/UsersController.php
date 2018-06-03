<?php

namespace App\Http\Controllers;
use function foo\func;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
class UsersController extends Controller
{

    /**
     * 未登录用户能访问  登录用户访问
    */
    public function __construct()
    {
        $this->middleware('auth',[
            'except' => ['show', 'create', 'store', 'index','confirmEmail']
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
        //获取微博内容
        $statuses = $user->statuses()->orderBy('created_at', 'desc')->paginate(30);
        return view('users.show',compact('user', 'statuses')); //compact转换为关联数组
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
//
//        //注册成功自动登录
//        Auth::login($user);
        $this->sendEmailConfirmationTo($user);  //调用发送邮件方法
        //显示用户注册成功信息 重定向显示详情页
        session()->flash('success','验证邮件已发送到你的注册邮箱上，请注意查收。');
        return redirect('/');
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

    /**
     * 发送邮件
    */
    protected function sendEmailConfirmationTo($user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
        $from = 'make835215945@163.com';
        $name = '马可';
        $to = $user->email;
        $subject = "感谢注册 Sample 应用！请确认你的邮箱。";

        //执行发送
        Mail::send($view, $data, function ($message) use($from, $name, $to, $subject) {
            $message->to($to)->subject($subject);
        });
    }

    /**
     *  执行激活账号
    */
    public function confirmEmail($token)
    {
        $user = User::where('activation_token',$token)->firstOrFail();
        $user->activated = true;
        $user->activation_token = null;
        $user->save();

        Auth::login($user);  //执行登录
        session()->flash('success', '恭喜你，激活成功！');
        return redirect()->route('users.show', [$user]);
    }

    /**
     *  关注列表
    */
    public function followings(User $user)
    {
        $users = $user->followings()->paginate(30);
        $title = '关注的人';
        return view('users.show_follow', compact('users', 'title'));
    }

    /**
     *  粉丝列表
    */
    public function followers(User $user)
    {
        $users = $user->followers()->paginate(30);
        $title = '粉丝';
        return view('users.show_follow', compact('users', 'title'));
    }
}
