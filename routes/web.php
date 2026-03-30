<?php

use App\Http\Controllers\Dashboard\WhatsAppController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



Route::group(['prefix' => 'whatsapp'], function () {
    Route::get('/', [WhatsAppController::class, 'index'])->name('whatsapp');
    Route::get('/chats', [WhatsAppController::class, 'fetchChats'])->name('whatsapp.chats');
    Route::post('/messages', [WhatsAppController::class, 'fetchMessages'])->name('whatsapp.messages');
    Route::post('/send', [WhatsAppController::class, 'sendMessage'])->name('whatsapp.send');
    Route::get('/status', [WhatsAppController::class, 'status'])->name('whatsapp.status');
    Route::get('/profile-picture', [WhatsAppController::class, 'profilePicture'])->name('whatsapp.profile-picture');
    Route::get('/contacts', [WhatsAppController::class, 'fetchContacts'])->name('whatsapp.contacts');
    Route::get('/users', [WhatsAppController::class, 'getUsers'])->name('whatsapp.users');
    Route::post('/assign', [WhatsAppController::class, 'assignChat'])->name('whatsapp.assign');
    Route::get('/assignments', [WhatsAppController::class, 'getAssignments'])->name('whatsapp.assignments');
    Route::post('/media', [WhatsAppController::class, 'fetchMedia'])->name('whatsapp.media');
});
