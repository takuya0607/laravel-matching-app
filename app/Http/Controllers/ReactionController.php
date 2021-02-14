<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// ここから追加
use App\User;
use App\Reaction;
use App\Constants\Status;

use Log;
// ここまで追加

class ReactionController extends Controller
{
    // ここから追加
    public function create(Request $request)
    {
        Log::debug($request);

        $to_user_id = $request->to_user_id;
        $like_status = $request->reaction;
        $from_user_id = $request->from_user_id;

        if ($like_status === 'like'){
            $status = Status::LIKE;
        }else{
            $status = Status::DISLIKE;
        }

        $checkReaction = Reaction::where([
        ['to_user_id', $to_user_id],
        ['from_user_id', $from_user_id]
        ])->get();

        if($checkReaction->isEmpty()){

            $reaction = new Reaction();

            $reaction->to_user_id = $to_user_id;
            $reaction->from_user_id = $from_user_id;
            $reaction->status = $status;

            $reaction->save();
        }

    }
    // ここまで追加
}
