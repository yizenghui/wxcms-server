<?php

use Encore\Admin\Registration\Http\Controllers\LoginController;
use Encore\Admin\Registration\Http\Controllers\RegisterController;
use Encore\Admin\Registration\Http\Controllers\ForgotPasswordController;
use Encore\Admin\Registration\Http\Controllers\ResetPasswordController;
use Encore\Admin\Registration\Http\Controllers\VerificationController;

// Authentication Routes...
Route::get('auth/login', LoginController::class.'@getLogin')->name('admin.login');
Route::post('auth/login', LoginController::class.'@postLogin');

// Registration Routes...
Route::get('auth/register', RegisterController::class.'@showRegistrationForm')->name('admin.register');
Route::post('auth/register', RegisterController::class.'@register');

// Password Reset Routes...
Route::get('auth/password/reset', ForgotPasswordController::class.'@showLinkRequestForm')->name('admin.password.request');
Route::post('auth/password/email', ForgotPasswordController::class.'@sendResetLinkEmail')->name('admin.password.email');
Route::get('auth/password/reset/{token}', ResetPasswordController::class.'@showResetForm')->name('admin.password.reset');
Route::post('auth/password/reset', ResetPasswordController::class.'@reset');

// Email verification Routes...
Route::get('auth/email/verify', VerificationController::class.'@show')->name('admin.verification.notice');
Route::get('auth/email/verify/{id}', VerificationController::class.'@verify')->name('admin.verification.verify');
Route::get('auth/email/resend', VerificationController::class.'@resend')->name('admin.verification.resend');