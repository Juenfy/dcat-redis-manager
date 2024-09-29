<?php

use Juenfy\DcatRedisManager\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::get('redis', Controllers\DcatRedisManagerController::class . '@index')->name('redis-index');
Route::delete('redis/key', Controllers\DcatRedisManagerController::class . '@destroy')->name('redis-key-delete');
Route::get('redis/fetch', Controllers\DcatRedisManagerController::class . '@fetch')->name('redis-fetch-key');
Route::get('redis/create', Controllers\DcatRedisManagerController::class . '@create')->name('redis-create-key');
Route::post('redis/store', Controllers\DcatRedisManagerController::class . '@store')->name('redis-store-key');
Route::get('redis/edit', Controllers\DcatRedisManagerController::class . '@edit')->name('redis-edit-key');
Route::put('redis/key', Controllers\DcatRedisManagerController::class . '@update')->name('redis-update-key');
Route::delete('redis/item', Controllers\DcatRedisManagerController::class . '@remove')->name('redis-remove-item');

Route::get('redis/console', Controllers\DcatRedisManagerController::class . '@console')->name('redis-console');
Route::post('redis/console', Controllers\DcatRedisManagerController::class . '@execute')->name('redis-execute');
