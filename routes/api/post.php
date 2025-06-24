<?php

use App\Http\Controllers\Api\PlatformSettingController;
use Illuminate\Support\Facades\Route;


Route::post('/configuraciones', [PlatformSettingController::class, 'save'])->name('config.save');
