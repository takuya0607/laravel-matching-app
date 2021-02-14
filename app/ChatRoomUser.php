<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ChatRoomUser extends Model
{
    // ここから追加
    protected $fillable = ['chat_room_id', 'user_id'];

    public function chatRoom()
    {
        return $this->belongsTo('App\ChatRoom');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
    // ここまで追加
}