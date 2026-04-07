<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_chat_labels', function (Blueprint $table) {
            $table->id();
            $table->string('chat_id');
            $table->foreignId('label_id')->constrained('whatsapp_labels')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['chat_id', 'label_id']);
            $table->index('chat_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_chat_labels');
    }
};
