<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsAppChatAssignment extends Model
{
    protected $fillable = ['chat_id', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
