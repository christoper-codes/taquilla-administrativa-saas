<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\TicketController;
use Illuminate\Support\Facades\Route;

Route::controller(EventController::class)->group(function () {
    Route::get('/events', 'index')->name('api.events.index');
    Route::get('/events-visibility-types', 'visibilityTypes')->name('api.events.visibility.types');
});

Route::controller(AuthController::class)->group(function () {
    Route::post('/login', 'login')->name('api.auth.login');
    Route::post('/logout', 'logout')->name('api.auth.logout')->middleware('auth:sanctum');
    Route::post('/register', 'register')->name('api.auth.register');
    Route::get('/user-genders', 'userGenders')->name('api.auth.user.gernders');
    Route::get('/profile', 'profile')->name('api.auth.profile')->middleware('auth:sanctum');
});

Route::middleware('auth:sanctum')->controller(SaleController::class)->group(function () {
    Route::get('/tickets-purchased', 'ticketsPurchased')->name('api.sale.tickets.purchased');
});

Route::middleware('auth:sanctum')->controller(TicketController::class)->group(function () {
    Route::post('/check-ticket', 'checkTicket')->name('api.ticket.check');
});
