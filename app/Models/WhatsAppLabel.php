<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsAppLabel extends Model
{
    protected $table = 'whatsapp_labels';

    protected $fillable = [
        'name',
        'color',
        'created_by',
    ];
}
