<?php

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

use App\Http\Controllers\Api\HomeController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'apipurchasecode'], function () {
    Route::group(['middleware' => 'apiauthtoken'], function () {

        // ---------------- UsersController ----------------
        Route::post('register', [UserController::class, 'register']);
        Route::post('login', [UserController::class, 'login']);
        Route::post('get_profile', [UserController::class, 'get_profile']);
        Route::post('update_profile', [UserController::class, 'update_profile']);
        Route::post('get_tv_login_code', [UserController::class, 'get_tv_login_code']);
        Route::post('tv_login', [UserController::class, 'tv_login']);
        Route::post('check_tv_login', [UserController::class, 'check_tv_login']);
        Route::post('parent_control_check_password', [UserController::class, 'parent_control_check_password']);
        Route::post('get_device_sync_list', [UserController::class, 'get_device_sync_list']);
        Route::post('logout_device_sync', [UserController::class, 'logout_device_sync']);
        Route::post('add_remove_device_watching', [UserController::class, 'add_remove_device_watching']);

        // ---------------- HomeController ----------------
        Route::post('general_setting', [HomeController::class, 'general_setting']);
        Route::post('get_payment_option', [HomeController::class, 'get_payment_option']);
        Route::post('get_social_link', [HomeController::class, 'get_social_link']);
        Route::post('get_onboarding_screen', [HomeController::class, 'get_onboarding_screen']);
        Route::post('get_package', [HomeController::class, 'get_package']);
        Route::post('get_avatar', [HomeController::class, 'get_avatar']);
        Route::post('get_category', [HomeController::class, 'get_category']);
        Route::post('get_language', [HomeController::class, 'get_language']);
        Route::post('get_channel', [HomeController::class, 'get_channel']);
        Route::post('get_type', [HomeController::class, 'get_type']);
        Route::post('get_pages', [HomeController::class, 'get_pages']);
        Route::post('add_transaction', [HomeController::class, 'add_transaction']);
        Route::post('update_transaction_status', [HomeController::class, 'update_transaction_status']);
        Route::post('get_transaction_list', [HomeController::class, 'get_transaction_list']);
        Route::post('add_rent_transaction', [HomeController::class, 'add_rent_transaction']);
        Route::post('get_coupon_list', [HomeController::class, 'get_coupon_list']);
        Route::post('apply_coupon', [HomeController::class, 'apply_coupon']);
        Route::post('user_rent_content_list', [HomeController::class, 'user_rent_content_list']);
        Route::post('rent_content_list', [HomeController::class, 'rent_content_list']);
        Route::post('get_banner', [HomeController::class, 'get_banner']);
        Route::post('section_list', [HomeController::class, 'section_list']);
        Route::post('section_detail', [HomeController::class, 'section_detail']);
        Route::post('content_detail', [HomeController::class, 'content_detail']);
        Route::post('get_releted_content', [HomeController::class, 'get_releted_content']);
        Route::post('cast_detail', [HomeController::class, 'cast_detail']);
        Route::post('content_by_category', [HomeController::class, 'content_by_category']);
        Route::post('content_by_language', [HomeController::class, 'content_by_language']);
        Route::post('content_by_cast', [HomeController::class, 'content_by_cast']);
        Route::post('content_by_channel', [HomeController::class, 'content_by_channel']);
        Route::post('add_continue_watching', [HomeController::class, 'add_continue_watching']);
        Route::post('remove_continue_watching', [HomeController::class, 'remove_continue_watching']);
        Route::post('add_remove_like', [HomeController::class, 'add_remove_like']);
        Route::post('add_remove_bookmark', [HomeController::class, 'add_remove_bookmark']);
        Route::post('add_video_view', [HomeController::class, 'add_video_view']);
        Route::post('get_bookmark_video', [HomeController::class, 'get_bookmark_video']);
        Route::post('add_comment', [HomeController::class, 'add_comment']);
        Route::post('edit_comment', [HomeController::class, 'edit_comment']);
        Route::post('delete_comment', [HomeController::class, 'delete_comment']);
        Route::post('get_comment', [HomeController::class, 'get_comment']);
        Route::post('get_replay_comment', [HomeController::class, 'get_replay_comment']);
        Route::post('get_video_by_season_id', [HomeController::class, 'get_video_by_season_id']);
        Route::post('search_content', [HomeController::class, 'search_content']);
        Route::post('get_continue_watching', [HomeController::class, 'get_continue_watching']);
        Route::post('add_remove_kids_mode', [HomeController::class, 'add_remove_kids_mode']);
        Route::post('create_razorpay_order', [HomeController::class, 'create_razorpay_order']);
        Route::post('get_shorts_list', [HomeController::class, 'get_shorts_list']);
        Route::post('get_shorts_episode', [HomeController::class, 'get_shorts_episode']);
        Route::post('get_notification', [HomeController::class, 'get_notification']);
        Route::post('read_notification', [HomeController::class, 'read_notification']);
        Route::post('get_refer_earn_history', [HomeController::class, 'getReferEarnHistory']);
        Route::post('add_wallet_amount', [HomeController::class, 'add_wallet_amount']);
        Route::post('get_wallet_transaction', [HomeController::class, 'get_wallet_transaction']);
        Route::post('get_vdocipher_otp', [HomeController::class, 'get_vdocipher_otp']);
        Route::post('add_review',  [HomeController::class, 'add_review']);
        Route::post('get_reviews', [HomeController::class, 'get_reviews']);

        // Coupon System — v2.1
        Route::post('validate_coupon', [HomeController::class, 'validate_coupon']);
    });
});
