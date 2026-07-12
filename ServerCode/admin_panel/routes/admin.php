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

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\LoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\TypeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AvatarController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\SeasonController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CastController;
use App\Http\Controllers\Admin\ChannelController;
use App\Http\Controllers\Admin\PackageController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\VideoController;
use App\Http\Controllers\Admin\NotificationController;
use App\Http\Controllers\Admin\RentTransactionController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\SystemSettingController;
use App\Http\Controllers\Admin\TVShowController;
use App\Http\Controllers\Admin\AdmobSettingController;
use App\Http\Controllers\Admin\AppSettingController;
use App\Http\Controllers\Admin\NotificationConfigurationController;
use App\Http\Controllers\Admin\PanelSettingController;
use App\Http\Controllers\Admin\ProducerController;
use App\Http\Controllers\Admin\ReferEarnController;
use App\Http\Controllers\Admin\RentPriceListController;
use App\Http\Controllers\Admin\ShortsController;
use App\Http\Controllers\Admin\WithdrawalController;
use App\Http\Controllers\Admin\WalletTransactionController;
use App\Http\Controllers\Admin\ReviewController;

Route::group(['middleware' => 'installation'], function () {

    // Login-Logout
    Route::get('login', [LoginController::class, 'login'])->name('admin.login');
    Route::post('login', [LoginController::class, 'save_login'])->name('admin.save.login');
    Route::get('logout', [LoginController::class, 'logout'])->name('admin.logout');
    // Chunk
    Route::post('video/saveChunk', [VideoController::class, 'saveChunk']);

    Route::group(['middleware' => 'authadmin', 'as' => 'admin.'], function () {

        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        // Profile
        Route::resource('profile', ProfileController::class)->only(['index', 'store']);
        Route::post('profile/changepassword', [ProfileController::class, 'ChangePassword'])->name('profile.changepassword');
        // Type
        Route::resource('type', TypeController::class)->only(['index', 'store', 'update', 'show']);
        Route::post('type/sortable/save', [TypeController::class, 'TypeSortableSave'])->name('type.sortable.save');
        // Category
        Route::resource('category', CategoryController::class)->only(['index', 'store', 'update', 'show']);
        Route::post('category/sortable/save', [CategoryController::class, 'CategorySortableSave'])->name('category.sortable.save');
        // Language
        Route::resource('language', LanguageController::class)->only(['index', 'store', 'update', 'show']);
        Route::post('language/sortable/save', [LanguageController::class, 'LanguageSortableSave'])->name('language.sortable.save');
        // Season
        Route::resource('season', SeasonController::class)->only(['index', 'store', 'update']);
        Route::post('season/sortable/save', [SeasonController::class, 'SeasonSortableSave'])->name('season.sortable.save');
        // Avatar
        Route::resource('avatar', AvatarController::class)->only(['index', 'store', 'update', 'show']);
        Route::post('avatar/sortable/save', [AvatarController::class, 'AvatarSortableSave'])->name('avatar.sortable.save');
        // Channel
        Route::resource('channel', ChannelController::class)->only(['index', 'store', 'update', 'show']);
        // User
        Route::resource('user', UserController::class)->only(['index', 'create', 'edit', 'store', 'update', 'show']);
        Route::get('user/dashboard/{id}', [UserController::class, 'dashboard'])->name('user.dashboard');
        // Producer
        Route::resource('producer', ProducerController::class)->only(['index', 'create', 'store', 'edit', 'update', 'show']);
        Route::get('producer/content/{producer_id}/{content_type}', [ProducerController::class, 'content_index'])->name('producer.content');
        Route::post('producer/content_status', [ProducerController::class, 'changeStatus'])->name('producer.content_status');
        // Cast
        Route::resource('cast', CastController::class)->only(['index', 'store', 'update', 'show']);
        // Banner
        Route::resource('banner', BannerController::class)->only(['index', 'store', 'destroy']);
        Route::post('banner/typebydata', [BannerController::class, 'TypeByData'])->name('banner.data');
        Route::post('banner/list', [BannerController::class, 'BannerList'])->name('banner.list');
        Route::post('banner/sortable/save', [BannerController::class, 'BannerSortableSave'])->name('banner.sortable.save');
        // Section
        Route::resource('section', SectionController::class)->only(['index', 'store', 'update']);
        Route::post('section/data', [SectionController::class, 'GetSectionData'])->name('section.content.data');
        Route::post('section/edit', [SectionController::class, 'SectionDataEdit'])->name('section.content.edit');
        Route::post('section/sortable', [SectionController::class, 'SectionSortable'])->name('section.content.sortable');
        Route::post('section/sortable/save', [SectionController::class, 'SectionSortableSave'])->name('section.content.sortable.save');
        Route::get('sectionstatus', [SectionController::class, 'changeStatus'])->name('section.status');
        Route::post('section/content', [SectionController::class, 'get_content'])->name('section.content');
        // Video
        Route::get('video/{type_id}', [VideoController::class, 'index'])->name('video.index');
        Route::get('video/add/{type_id}', [VideoController::class, 'create'])->name('video.add');
        Route::post('video/save', [VideoController::class, 'store'])->name('video.store');
        Route::get('video/edit/{video_id}/{type_id}', [VideoController::class, 'edit'])->name('video.edit');
        Route::post('video/update/{video_id}', [VideoController::class, 'update'])->name('video.update');
        Route::get('video/details/{type_id}/{id}', [VideoController::class, 'videoDetails'])->name('video.details');
        Route::post('video/serachname/{txtVal}', [VideoController::class, 'SerachName'])->name('video.serach.name');
        Route::post('video/getdata/{id}', [VideoController::class, 'GetData'])->name('video.getdata');
        Route::get('videostatus', [VideoController::class, 'changeStatus'])->name('video.status');
        Route::post('video/releases', [VideoController::class, 'videoReleases'])->name('video.releases');
        // TVShow
        Route::get('tvshow/{type_id}', [TVShowController::class, 'index'])->name('tvshow.index');
        Route::get('tvshow/add/{type_id}', [TVShowController::class, 'create'])->name('tvshow.add');
        Route::post('tvshow/save', [TVShowController::class, 'store'])->name('tvshow.store');
        Route::get('tvshow/edit/{tvshow_id}/{type_id}', [TVShowController::class, 'edit'])->name('tvshow.edit');
        Route::post('tvshow/update/{tvshow_id}', [TVShowController::class, 'update'])->name('tvshow.update');
        Route::get('tvshowstatus', [TVShowController::class, 'changeStatus'])->name('tvshow.status');
        Route::post('tvshow/releases', [TVShowController::class, 'showReleases'])->name('tvshow.releases');
        Route::get('tvshow-episode/{id}/{type_id}', [TVShowController::class, 'TVShowIndex'])->name('tvshow.episode.index');
        Route::get('tvshow-episode/add/{id}/{type_id}', [TVShowController::class, 'TVShowAdd'])->name('tvshow.episode.add');
        Route::post('tvshow-episode/save', [TVShowController::class, 'TVShowSave'])->name('tvshow.episode.save');
        Route::get('tvshow-episode/edit/{id}/{type_id}', [TVShowController::class, 'TVShowEdit'])->name('tvshow.episode.edit');
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
        Route::get('shortsstatus', [ShortsController::class, 'changeStatus'])->name('shorts.status');
        Route::get('shorts-episode/{id}/{type_id}', [ShortsController::class, 'ShortsIndex'])->name('shorts.episode.index');
        Route::get('shorts-episode/add/{id}/{type_id}', [ShortsController::class, 'ShortsAdd'])->name('shorts.episode.add');
        Route::post('shorts-episode/save', [ShortsController::class, 'ShortsSave'])->name('shorts.episode.save');
        Route::get('shorts-episode/edit/{id}/{type_id}', [ShortsController::class, 'ShortsEdit'])->name('shorts.episode.edit');
        Route::post('shorts-episode/update/{id}', [ShortsController::class, 'ShortsUpdate'])->name('shorts.episode.update');
        Route::post('shorts-episode/sortable', [ShortsController::class, 'ShortsSortable'])->name('shorts.episode.sortable');
        Route::get('shorts-episode/status', [ShortsController::class, 'ShortsEpisodeStatus'])->name('shorts.episode.status');
        // Notification
        Route::resource('notification', NotificationController::class)->only(['index', 'store']);
        // Reviews & Ratings
        Route::get('reviews', [ReviewController::class, 'index'])->name('reviews.index');
        // Referral & Earn
        Route::resource('refer-earn', ReferEarnController::class)->only(['index']);
        // Coupon
        Route::resource('coupon', CouponController::class)->only(['index', 'store', 'update', 'show']);
        // Rent Price List
        Route::resource('rent-price-list', RentPriceListController::class)->only(['index', 'store', 'update', 'show']);
        // Rent Transaction
        Route::resource('rent-transaction', RentTransactionController::class)->only(['index', 'create', 'store']);
        Route::any('rentsearchuser', [RentTransactionController::class, 'searchUser'])->name('rentSearchUser');
        // Package
        Route::resource('package', PackageController::class)->only(['index', 'create', 'edit', 'store', 'update', 'show']);
        // Transaction
        Route::resource('transaction', TransactionController::class)->only(['index', 'create', 'store']);
        Route::any('search_user', [TransactionController::class, 'searchUser'])->name('searchUser');
        // Payment
        Route::resource('payment', PaymentController::class)->only(['index']);
        // Wallet Transaction
        Route::resource('wallet-transaction', WalletTransactionController::class)->only(['index']);
        // Withdrawal
        Route::resource('withdrawal', WithdrawalController::class)->only(['index', 'show']);
        // Admob
        Route::resource('admob', AdmobSettingController::class)->only(['index']);
        Route::post('admob/status', [AdmobSettingController::class, 'admobStatus'])->name('admob.status');
        Route::post('admob/android', [AdmobSettingController::class, 'admobAndroid'])->name('admob.android');
        Route::post('admob/ios', [AdmobSettingController::class, 'admobIos'])->name('admob.ios');
        // App Setting
        Route::get('app-setting', [AppSettingController::class, 'index'])->name('appsetting');
        Route::post('app-setting/app', [AppSettingController::class, 'app'])->name('appsetting.app');
        Route::post('app-setting/apitoken', [AppSettingController::class, 'apitoken'])->name('appsetting.apitoken');
        Route::post('app-setting/tmdbkey', [AppSettingController::class, 'saveTmdbKey'])->name('appsetting.tmdbkey');
        Route::post('app-setting/currency', [AppSettingController::class, 'currency'])->name('appsetting.currency');
        Route::post('app-setting/appdownload', [AppSettingController::class, 'appdownload'])->name('appsetting.appdownload');
        Route::post('app-setting/basicconfigrations', [AppSettingController::class, 'saveBasicConfigrations'])->name('appsetting.basicconfigrations');
        Route::post('app-setting/smtp', [AppSettingController::class, 'smtpSave'])->name('appsetting.smtp');
        Route::post('app-setting/storagesetting', [AppSettingController::class, 'StorageSetting'])->name('appsetting.storage.save');
        Route::post('app-setting/sociallink', [AppSettingController::class, 'saveSocialLink'])->name('appsetting.sociallink');
        Route::post('app-setting/onboardingscreen', [AppSettingController::class, 'saveOnBoardingScreen'])->name('appsetting.onboardingscreen');
        Route::post('app-setting/vapidkey', [AppSettingController::class, 'vapIdKey'])->name('appsetting.vapidkey');
        Route::post('app-setting/commission', [AppSettingController::class, 'setting_commission'])->name('appsetting.commission');
        Route::post('app-setting/withdrawal-amount', [AppSettingController::class, 'withdrawal_amount'])->name('appsetting.withdrawal');
        Route::post('app-setting/vdocipher', [AppSettingController::class, 'vdocipher'])->name('appsetting.vdocipher');
        Route::post('app-setting/webclientid', [AppSettingController::class, 'webclientid'])->name('appsetting.webclientid');
        Route::post('app-setting/refer-and-earn', [AppSettingController::class, 'saveReferAndEarn'])->name('appsetting.referandearn');
        // Panel Setting
        Route::get('panel-setting', [PanelSettingController::class, 'index'])->name('panel.setting.index');
        Route::post('panel-setting/save', [PanelSettingController::class, 'save'])->name('panel.setting.save');
        // System Setting
        Route::get('system-setting', [SystemSettingController::class, 'index'])->name('system.setting.index');
        Route::post('system-setting/cleardata', [SystemSettingController::class, 'ClearData'])->name('system.setting.cleardata');
        Route::post('system-setting/cleandatabase', [SystemSettingController::class, 'CleanDatabase'])->name('system.setting.cleandatabase');
        // Notification Configuration
        Route::resource('notificationconfiguration', NotificationConfigurationController::class)->only(['index', 'store']);
        // Page
        Route::resource('page', PageController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update']);
        Route::post('page/pagesetting', [PageController::class, 'PageSettingSave'])->name('page.setting.save');

        Route::group(['middleware' => 'checkadmin'], function () {

            // Type
            Route::resource('type', TypeController::class)->only(['destroy']);
            // Category
            Route::resource('category', CategoryController::class)->only(['destroy']);
            // Language
            Route::resource('language', LanguageController::class)->only(['destroy']);
            // Season
            Route::resource('season', SeasonController::class)->only(['destroy']);
            // Avatar
            Route::resource('avatar', AvatarController::class)->only(['destroy']);
            // Channel
            Route::resource('channel', ChannelController::class)->only(['destroy']);
            // User
            Route::resource('user', UserController::class)->only(['destroy']);
            // Producer
            Route::resource('producer', ProducerController::class)->only(['destroy']);
            // Cast
            Route::resource('cast', CastController::class)->only(['destroy']);
            // Section
            Route::resource('section', SectionController::class)->only(['show']);
            // Video
            Route::get('video/delete/{video_id}/{type_id}', [VideoController::class, 'show'])->name('video.show');
            // TVShow
            Route::get('tvshow/show/{tvshow_id}/{type_id}', [TVShowController::class, 'show'])->name('tvshow.show');
            Route::get('tvshow-episode/delete/{tvshow_id}/{id}/{type_id}', [TVShowController::class, 'TVShowDelete'])->name('tvshow.episode.delete');
            // Shorts
            Route::get('shorts/show/{shorts_id}/{type_id}', [ShortsController::class, 'show'])->name('shorts.show');
            Route::get('shorts-episode/delete/{shorts_id}/{id}/{type_id}', [ShortsController::class, 'ShortsDelete'])->name('shorts.episode.delete');
            // Notification
            Route::resource('notification', NotificationController::class)->only(['destroy']);
            Route::get('notifications/setting', [NotificationController::class, 'setting'])->name('notification.setting');
            Route::post('notifications/setting', [NotificationController::class, 'settingsave'])->name('notification.settingsave');
            // Reviews & Ratings
            Route::post('reviews/approve/{id}', [ReviewController::class, 'approve'])->name('reviews.approve');
            Route::post('reviews/reject/{id}', [ReviewController::class, 'reject'])->name('reviews.reject');
            Route::get('reviews/destroy/{id}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
            // Coupon
            Route::resource('coupon', CouponController::class)->only(['destroy']);
            // Rent Price List
            Route::resource('rent-price-list', RentPriceListController::class)->only(['destroy']);
            // Rent Transaction
            Route::resource('rent-transaction', RentTransactionController::class)->only(['destroy']);
            // Package
            Route::resource('package', PackageController::class)->only(['destroy']);
            // Transaction
            Route::resource('transaction', TransactionController::class)->only(['destroy']);
            // Payment
            Route::resource('payment', PaymentController::class)->only(['edit', 'update']);
            // System Setting
            Route::get('systemsetting/downloadsqlfile', [SystemSettingController::class, 'DownloadSqlFile'])->name('system.setting.downloadsqlfile');
            // Page
            Route::resource('page', PageController::class)->only(['destroy']);
        });
    });
});
