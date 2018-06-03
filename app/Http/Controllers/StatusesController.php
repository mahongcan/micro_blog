<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Status;
class StatusesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     *  添加微博文章
    */
    public function store(Request $request)
    {
        $this->validate($request, [
            'content' => 'required|max:140'
        ]);

        //这样会将登录人的ID存入发布文章表 的ID
        Auth::user()->statuses()->create([
            'content' => $request['content']
        ]);
        return redirect()->back();
    }

    public function destroy(Status $status)
    {
        $this->authorize('destroy', $status); //只有是当前登录人才能删除
        $status->delete();
        session()->flash('success', '微博已被成功删除！');
        return redirect()->back();
    }
}
