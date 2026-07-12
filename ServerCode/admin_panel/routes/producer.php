<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use App\Http\Controllers\Producer\WithdrawalController;
use App\Http\Controllers\Producer\ChangePasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Producer\LoginController;
use App\Http\Controllers\Producer\DashboardController;
use App\Http\Controllers\Producer\ProfileController;
use App\Http\Controllers\Producer\ChannelController;
use App\Http\Controllers\Producer\VideoController;
use App\Http\Controllers\Producer\TVShowController;
use App\Http\Controllers\Producer\RentTransactionController;
use App\Http\Controllers\Producer\ShortsController;

Route::group(['middleware' => 'installation'], function () {

    // Login-Logout
    Route::get('login', [LoginController::class, 'login'])->name('producer.login');
    Route::post('login', [LoginController::class, 'save_login'])->name('producer.save.login');
    Route::get('logout', [LoginController::class, 'logout'])->name('producer.logout');

    Route::group(['middleware' => 'authproducer', 'as' => 'producer.'], function () {

        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        // Profile
        Route::resource('profile', ProfileController::class)->only(['index', 'store']);
        // Change Password
        Route::resource('change-password', ChangePasswordController::class)->only(['index', 'store']);
        // Channel
        Route::resource('channel', ChannelController::class)->only(['index']);
        // Video
        Route::get('video/{type_id}', [VideoController::class, 'index'])->name('video.index');
        Route::get('video/add/{type_id}', [VideoController::class, 'create'])->name('video.add');
        Route::post('video/save', [VideoController::class, 'store'])->name('video.store');
        Route::get('video/edit/{video_id}/{type_id}', [VideoController::class, 'edit'])->name('video.edit');
        Route::post('video/update/{video_id}', [VideoController::class, 'update'])->name('video.update');
        Route::get('video/details/{id}', [VideoController::class, 'videoDetails'])->name('video.details');
        Route::post('video/serachname/{txtVal}', [VideoController::class, 'SerachName'])->name('video.serach.name');
        Route::post('video/getdata/{id}', [VideoController::class, 'GetData'])->name('video.getdata');
        Route::get('video-status', [VideoController::class, 'changeStatus'])->name('video.status');
        Route::post('video/releases', [VideoController::class, 'videoReleases'])->name('video.releases');
        // TVShow
        Route::get('tvshow/{type_id}', [TVShowController::class, 'index'])->name('tvshow.index');
        Route::get('tvshow/add/{type_id}', [TVShowController::class, 'create'])->name('tvshow.add');
        Route::post('tvshow/save', [TVShowController::class, 'store'])->name('tvshow.store');
        Route::get('tvshow/edit/{tvshow_id}/{type_id}', [TVShowController::class, 'edit'])->name('tvshow.edit');
        Route::post('tvshow/update/{tvshow_id}', [TVShowController::class, 'update'])->name('tvshow.update');
        Route::get('tvshow-status', [TVShowController::class, 'changeStatus'])->name('tvshow.status');
        Route::post('tvshow/releases', [TVShowController::class, 'showReleases'])->name('tvshow.releases');
        Route::get('tvshow-episode/{tvshow_id}/{type_id}', [TVShowController::class, 'TVShowIndex'])->name('tvshow.episode.index');
        Route::get('tvshow-episode/add/{tvshow_id}/{type_id}', [TVShowController::class, 'TVShowAdd'])->name('tvshow.episode.add');
        Route::post('tvshow-episode/save', [TVShowController::class, 'TVShowSave'])->name('tvshow.episode.save');
        Route::get('tvshow-episode/edit/{tvshow_id}/{type_id}', [TVShowController::class, 'TVShowEdit'])->name('tvshow.episode.edit');
        Route::post('tvshow-episode/update/{id}', [TVShowController::class, 'TVShowUpdate'])->name('tvshow.episode.update');
        Route::post('tvshow-episode/sortable', [TVShowController::class, 'TVShowSortable'])->name('tvshow.episode.sortable');
        Route::get('tvshow-episode/status', [TVShowController::class, 'TVShowEpisodeStatus'])->name('tvshow.episode.status');
        Route::post('tvshow/serachname/{txtVal}', [TVShowController::class, 'SerachName'])->name('tvshow.serach.name');
        Route::post('tvshow/getdata/{id}', [TVShowController::class, 'GetData'])->name('tvshow.getdata');
        // Shorts
        Route::get('shorts/{type_id}', [ShortsController::class, 'index'])->name('shorts.index');
        Route::get('shorts/add/{type_id}', [ShortsController::class, 'create'])->name('shorts.add');
        Route::post('shorts/save', [ShortsController::class, 'store'])->name('shorts.store');
        Route::get('shorts/edit/{shorts_id}/{type_id}', [ShortsController::class, 'edit'])->name('shorts.edit');
        Route::post('shorts/update/{shorts_id}', [ShortsController::class, 'update'])->name('shorts.update');
        Route::get('shorts/status', [ShortsController::class, 'changeStatus'])->name('shorts.status');
        Route::get('shorts-episode/{id}/{type_id}', [ShortsController::class, 'ShortsIndex'])->name('shorts.episode.index');
        Route::get('shorts-episode/add/{id}/{type_id}', [ShortsController::class, 'ShortsAdd'])->name('shorts.episode.add');
        Route::post('shorts-episode/save', [ShortsController::class, 'ShortsSave'])->name('shorts.episode.save');
        Route::get('shorts-episode/edit/{id}/{type_id}', [ShortsController::class, 'ShortsEdit'])->name('shorts.episode.edit');
        Route::post('shorts-episode/update/{id}', [ShortsController::class, 'ShortsUpdate'])->name('shorts.episode.update');
        Route::post('shorts-episode/sortable', [ShortsController::class, 'ShortsSortable'])->name('shorts.episode.sortable');
        Route::get('shorts-episode/status', [ShortsController::class, 'ShortsEpisodeStatus'])->name('shorts.episode.status');
        // Rent Transaction
        Route::resource('rent-transaction', RentTransactionController::class)->only(['index']);
        // Withdrawal
        Route::resource('withdrawal', WithdrawalController::class)->only(['index', 'store']);

        Route::group(['middleware' => 'checkadmin'], function () {

            // Video
            Route::get('video/delete/{video_id}/{type_id}', [VideoController::class, 'show'])->name('video.show');
            // TVShow
            Route::get('tvshow/show/{tvshow_id}/{type_id}', [TVShowController::class, 'show'])->name('tvshow.show');
            Route::get('tvshow-episode/delete/{tvshow_id}/{id}/{type_id}', [TVShowController::class, 'TVShowDelete'])->name('tvshow.episode.delete');
            // Shorts
            Route::get('shorts/show/{shorts_id}/{type_id}', [ShortsController::class, 'show'])->name('shorts.show');
            Route::get('shorts-episode/delete/{shorts_id}/{id}/{type_id}', [ShortsController::class, 'ShortsDelete'])->name('shorts.episode.delete');
        });
    });
});
