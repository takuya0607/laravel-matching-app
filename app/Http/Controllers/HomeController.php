<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Reaction;
use App\User; //追加
use Auth; // 追加

use App\Constants\Status;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // $got_reaction_ids変数に、Reactionテーブルから値をwhereで取得して代入する
        $got_reaction_ids = Reaction::where([
        // to_user_idが自分になる。
        // 自分がLIKEをしてるかの確認
        ['to_user_id', Auth::id()],
        ['status', Status::LIKE]
        // pluckを使うことで、自分へLIKEしてくれた人(from_user_id)のID情報のみを取得
        ])->pluck('from_user_id');

        // whereInメソッドは指定した配列の中にカラムの値が含まれている条件を加える
        // LIKEしてくれた人の中で、'to_user_id'カラムから、自分がLIKEした人だけ抽出
        $matching_ids = Reaction::whereIn('to_user_id', $got_reaction_ids)
        ->where('status', Status::LIKE)
        ->where('from_user_id', Auth::id())
        ->pluck('to_user_id');

        $users = User::whereNotIn('id', $matching_ids)
        // ログインユーザー以外の'id'を表示させる
        ->where('id', '!=', auth()->id())
        ->get();

        $userCount = $users->count();
        $from_user_id = Auth::id();

        return view('home', compact('users', 'userCount', 'from_user_id'));
    }
}