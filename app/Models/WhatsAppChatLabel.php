<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsAppChatLabel extends Model
{
    protected $table = 'whatsapp_chat_labels';

    protected $fillable = [
        'chat_id',
        'label_id',
    ];
}
