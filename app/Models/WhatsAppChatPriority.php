<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsAppChatPriority extends Model
{
    protected $table = 'whatsapp_chat_priorities';

    protected $fillable = [
        'chat_id',
        'priority',
        'updated_by',
    ];
}
