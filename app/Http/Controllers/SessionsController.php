<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{

    /**
     * 只让未登录用户访问
    */
    public function __construct()
    {
        $this->middleware('guest',[
            'only' => ['create']
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * 加载登录模板
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return view('login.create');
    }

    /**
     * 执行登录
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $credentials = $this->validate($request,[
            'email' => 'required|email|max:255',
            'password' => 'required'
        ]);

        /*
          attempt 方法执行的代码逻辑如下：
            1、使用 email 字段的值在数据库中查找；
            2、如果用户被找到：
            3、先将传参的 password 值进行哈希加密，然后与数据库中 password 字段中已加密的密码进行匹配；
            4、如果匹配后两个值完全一致，会创建一个『会话』给通过认证的用户。会话在创建的同时，也会种下一个名为
               laravel_session 的 HTTP Cookie，以此 Cookie 来记录用户登录状态，最终返回 true；
            5、如果匹配后两个值不一致，则返回 false；如果用户未找到，则返回 false。
            6、第二参数是记住我
        */
        if(Auth::attempt($credentials, $request->has('remember'))) {
            if (Auth::user()->activated) {
                //存入闪存
                session()->flash('success','欢迎回来！');
                //当登录成功后执行 Auth::User 能获取当前登录人的信息
                return redirect()->intended(route('users.show', [Auth::user()]));
            } else {
                Auth::logout();
                session()->flash('warning', '你的账号未激活，请检查邮箱中的注册邮件进行激活。');
                return redirect('/');
            }

        } else {
            return back()->with('danger','很抱歉，您的邮箱和密码不匹配');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * 退出登录
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy()
    {
        //退出登录
        Auth::logout();

        session()->flash('success','您已成功退出！');

        return redirect('login');
    }
}
