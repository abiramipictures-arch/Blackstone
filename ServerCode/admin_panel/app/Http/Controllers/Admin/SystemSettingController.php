<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Avatar;
use App\Models\Banner;
use App\Models\Bookmark;
use App\Models\Cast;
use App\Models\Category;
use App\Models\Channel;
use App\Models\Comment;
use App\Models\Common;
use App\Models\Coupon;
use App\Models\Device_Sync;
use App\Models\Device_Watching;
use App\Models\Home_Section;
use App\Models\Language;
use App\Models\Like;
use App\Models\Notification;
use App\Models\Onboarding_Screen;
use App\Models\Package;
use App\Models\Package_Detail;
use App\Models\Page;
use App\Models\Producer;
use App\Models\Read_Notification;
use App\Models\Refer_Earn;
use App\Models\Rent_Price_List;
use App\Models\Rent_Transaction;
use App\Models\Review;
use App\Models\Season;
use App\Models\Shorts;
use App\Models\Shorts_Episode;
use App\Models\Social_Link;
use App\Models\Transaction;
use App\Models\TV_Login;
use App\Models\TVShow;
use App\Models\TVShow_Video;
use App\Models\Type;
use App\Models\User;
use App\Models\UserInterest;
use App\Models\Video;
use App\Models\Video_Watch;
use App\Models\View;
use App\Models\WalletTransaction;
use App\Models\Withdrawal_Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Exception;

class SystemSettingController extends Controller
{
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index()
    {
        try {
            return view('admin.system_setting.index');
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function ClearData()
    {
        try {

            // Database
            $database_name = [];
            $database_file = Storage::allFiles('public/database');
            foreach ($database_file as $database_file) {
                array_push($database_name, pathinfo($database_file)['basename']);
            }
            foreach ($database_name as $key => $value) {
                $this->common->deleteImageToFolder('database', $value, 1);
            }

            $s3_avatar_file = [];
            $wasabi_avatar_file = [];
            $s3_type_file = [];
            $wasabi_type_file = [];
            $s3_category_file = [];
            $wasabi_category_file = [];
            $s3_language_file = [];
            $wasabi_language_file = [];
            $s3_cast_file = [];
            $wasabi_cast_file = [];
            $s3_producer_file = [];
            $wasabi_producer_file = [];
            $s3_channel_file = [];
            $wasabi_channel_file = [];
            $s3_user_file = [];
            $wasabi_user_file = [];
            $s3_notification_file = [];
            $wasabi_notification_file = [];
            $s3_content_file = [];
            $wasabi_content_file = [];
            $s3_app_file = [];
            $wasabi_app_file = [];
            try {
                $s3_avatar_file = Storage::disk('s3')->allFiles('avatar');
                $wasabi_avatar_file = Storage::disk('wasabi')->allFiles('avatar');
                $s3_type_file = Storage::disk('s3')->allFiles('type');
                $wasabi_type_file = Storage::disk('wasabi')->allFiles('type');
                $s3_category_file = Storage::disk('s3')->allFiles('category');
                $wasabi_category_file = Storage::disk('wasabi')->allFiles('category');
                $s3_language_file = Storage::disk('s3')->allFiles('language');
                $wasabi_language_file = Storage::disk('wasabi')->allFiles('language');
                $s3_cast_file = Storage::disk('s3')->allFiles('cast');
                $wasabi_cast_file = Storage::disk('wasabi')->allFiles('cast');
                $s3_producer_file = Storage::disk('s3')->allFiles('producer');
                $wasabi_producer_file = Storage::disk('wasabi')->allFiles('producer');
                $s3_channel_file = Storage::disk('s3')->allFiles('channel');
                $wasabi_channel_file = Storage::disk('wasabi')->allFiles('channel');
                $s3_user_file = Storage::disk('s3')->allFiles('user');
                $wasabi_user_file = Storage::disk('wasabi')->allFiles('user');
                $s3_notification_file = Storage::disk('s3')->allFiles('notification');
                $wasabi_notification_file = Storage::disk('wasabi')->allFiles('notification');
                $s3_content_file = Storage::disk('s3')->allFiles('content');
                $wasabi_content_file = Storage::disk('wasabi')->allFiles('content');
                $s3_app_file = Storage::disk('s3')->allFiles('app');
                $wasabi_app_file = Storage::disk('wasabi')->allFiles('app');
            } catch (Exception $e) {
            }

            // Avatar
            $local_avatar_file = Storage::allFiles('public/avatar');
            $avatar_files = array_merge($local_avatar_file, $s3_avatar_file, $wasabi_avatar_file);
            $avatar_name = array_map(fn($file) => pathinfo($file, PATHINFO_BASENAME), $avatar_files);
            $used_avatar_files = Avatar::pluck('image')->filter()->toArray();
            foreach ($avatar_name as $value) {
                if (!in_array($value, $used_avatar_files)) {
                    $this->common->deleteImageToFolder('avatar', $value, 1);
                    $this->common->deleteImageToFolder('avatar', $value, 2);
                    $this->common->deleteImageToFolder('avatar', $value, 3);
                }
            }

            // Type
            $local_type_file = Storage::allFiles('public/type');
            $type_files = array_merge($local_type_file, $s3_type_file, $wasabi_type_file);
            $type_name = array_map(fn($file) => pathinfo($file, PATHINFO_BASENAME), $type_files);
            $used_type_files = Type::pluck('icon')->filter()->toArray();
            foreach ($type_name as $value) {
                if (!in_array($value, $used_type_files)) {
                    $this->common->deleteImageToFolder('type', $value, 1);
                    $this->common->deleteImageToFolder('type', $value, 2);
                    $this->common->deleteImageToFolder('type', $value, 3);
                }
            }

            // Category
            $local_category_file = Storage::allFiles('public/category');
            $category_files = array_merge($local_category_file, $s3_category_file, $wasabi_category_file);
            $category_name = array_map(fn($file) => pathinfo($file, PATHINFO_BASENAME), $category_files);
            $used_category_files = Category::pluck('image')->filter()->toArray();
            foreach ($category_name as $value) {
                if (!in_array($value, $used_category_files)) {
                    $this->common->deleteImageToFolder('category', $value, 1);
                    $this->common->deleteImageToFolder('category', $value, 2);
                    $this->common->deleteImageToFolder('category', $value, 3);
                }
            }

            // Language
            $local_language_file = Storage::allFiles('public/language');
            $language_files = array_merge($local_language_file, $s3_language_file, $wasabi_language_file);
            $language_name = array_map(fn($file) => pathinfo($file, PATHINFO_BASENAME), $language_files);
            $used_language_files = Language::pluck('image')->filter()->toArray();
            foreach ($language_name as $value) {
                if (!in_array($value, $used_language_files)) {
                    $this->common->deleteImageToFolder('language', $value, 1);
                    $this->common->deleteImageToFolder('language', $value, 2);
                    $this->common->deleteImageToFolder('language', $value, 3);
                }
            }

            // Cast
            $local_cast_file = Storage::allFiles('public/cast');
            $cast_files = array_merge($local_cast_file, $s3_cast_file, $wasabi_cast_file);
            $cast_name = array_map(fn($file) => pathinfo($file, PATHINFO_BASENAME), $cast_files);
            $used_cast_files = Cast::pluck('image')->filter()->toArray();
            foreach ($cast_name as $value) {
                if (!in_array($value, $used_cast_files)) {
                    $this->common->deleteImageToFolder('cast', $value, 1);
                    $this->common->deleteImageToFolder('cast', $value, 2);
                    $this->common->deleteImageToFolder('cast', $value, 3);
                }
            }

            // Producer
            $local_producer_file = Storage::allFiles('public/producer');
            $producer_files = array_merge($local_producer_file, $s3_producer_file, $wasabi_producer_file);
            $producer_name = array_map(fn($file) => pathinfo($file, PATHINFO_BASENAME), $producer_files);
            $used_producer_files = Producer::pluck('image')->filter()->toArray();
            foreach ($producer_name as $value) {
                if (!in_array($value, $used_producer_files)) {
                    $this->common->deleteImageToFolder('producer', $value, 1);
                    $this->common->deleteImageToFolder('producer', $value, 2);
                    $this->common->deleteImageToFolder('producer', $value, 3);
                }
            }

            // Channel
            $local_channel_file = Storage::allFiles('public/channel');
            $channel_files = array_merge($local_channel_file, $s3_channel_file, $wasabi_channel_file);
            $channel_name = array_map(fn($file) => pathinfo($file, PATHINFO_BASENAME), $channel_files);
            $used_channel_files = Channel::pluck('portrait_img')->merge(Channel::pluck('landscape_img'))->filter()->toArray();
            foreach ($channel_name as $value) {
                if (!in_array($value, $used_channel_files)) {
                    $this->common->deleteImageToFolder('channel', $value, 1);
                    $this->common->deleteImageToFolder('channel', $value, 2);
                    $this->common->deleteImageToFolder('channel', $value, 3);
                }
            }

            // User
            $local_user_file = Storage::allFiles('public/user');
            $user_files = array_merge($local_user_file, $s3_user_file, $wasabi_user_file);
            $user_name = array_map(fn($file) => pathinfo($file, PATHINFO_BASENAME), $user_files);
            $used_user_files = User::where('image_type', 1)->pluck('image')->filter()->toArray();
            foreach ($user_name as $value) {
                if (!in_array($value, $used_user_files)) {
                    $this->common->deleteImageToFolder('user', $value, 1);
                    $this->common->deleteImageToFolder('user', $value, 2);
                    $this->common->deleteImageToFolder('user', $value, 3);
                }
            }

            // Notification
            $local_notification_file = Storage::allFiles('public/notification');
            $notification_files = array_merge($local_notification_file, $s3_notification_file, $wasabi_notification_file);
            $notification_name = array_map(fn($file) => pathinfo($file, PATHINFO_BASENAME), $notification_files);
            $used_notification_files = Notification::pluck('image')->filter()->toArray();
            foreach ($notification_name as $value) {
                if (!in_array($value, $used_notification_files)) {
                    $this->common->deleteImageToFolder('notification', $value, 1);
                    $this->common->deleteImageToFolder('notification', $value, 2);
                    $this->common->deleteImageToFolder('notification', $value, 3);
                }
            }

            // Content
            $local_content_file = Storage::allFiles('public/content');
            $content_files = array_merge($local_content_file, $s3_content_file, $wasabi_content_file);
            $content_name = array_map(fn($file) => pathinfo($file, PATHINFO_BASENAME), $content_files);
            $video_used_files = Video::pluck('thumbnail')->merge(Video::pluck('landscape'))
                ->merge(Video::pluck('video_320'))->merge(Video::pluck('video_480'))->merge(Video::pluck('video_720'))->merge(Video::pluck('video_1080'))
                ->merge(Video::pluck('trailer_url'))->merge(Video::pluck('subtitle_1'))->merge(Video::pluck('subtitle_2'))->merge(Video::pluck('subtitle_3'))->filter()->toArray();
            $tvshow_used_files = TVShow::pluck('thumbnail')->merge(TVShow::pluck('landscape'))->merge(TVShow::pluck('trailer_url'))->filter()->toArray();
            $tvshow_video_used_files = TVShow_Video::pluck('thumbnail')->merge(TVShow_Video::pluck('landscape'))
                ->merge(TVShow_Video::pluck('video_320'))->merge(TVShow_Video::pluck('video_480'))->merge(TVShow_Video::pluck('video_720'))->merge(TVShow_Video::pluck('video_1080'))
                ->merge(TVShow_Video::pluck('subtitle_1'))->merge(TVShow_Video::pluck('subtitle_2'))->merge(TVShow_Video::pluck('subtitle_3'))->filter()->toArray();
            $shorts_used_files = Shorts::pluck('thumbnail')->merge(Shorts::pluck('trailer_url'))->filter()->toArray();
            $shorts_episode_used_files = Shorts_Episode::pluck('thumbnail')->merge(Shorts_Episode::pluck('video_320'))->filter()->toArray();
            $used_content_files = array_unique(array_merge($video_used_files, $tvshow_used_files, $tvshow_video_used_files, $shorts_used_files, $shorts_episode_used_files));
            foreach ($content_name as $value) {
                if (!in_array($value, $used_content_files)) {
                    $this->common->deleteImageToFolder('content', $value, 1);
                    $this->common->deleteImageToFolder('content', $value, 2);
                    $this->common->deleteImageToFolder('content', $value, 3);
                }
            }

            // App
            $local_app_file = Storage::allFiles('public/app');
            $app_files = array_merge($local_app_file, $s3_app_file, $wasabi_app_file);
            $app_name = array_map(fn($file) => pathinfo($file, PATHINFO_BASENAME), $app_files);
            $page_used_files = Page::pluck('icon')->filter()->toArray();
            $social_link_used_files = Social_Link::pluck('image')->filter()->toArray();
            $onboarding_screen_used_files = Onboarding_Screen::pluck('image')->filter()->toArray();
            $setting_used_files = Setting_Data();
            $used_app_files = array_unique(array_merge($page_used_files, $social_link_used_files, $onboarding_screen_used_files, $setting_used_files));
            foreach ($app_name as $value) {

                if (!in_array($value, $used_app_files)) {

                    $check = 'no';
                    if (
                        $setting_used_files['app_logo'] != $value && $setting_used_files['panel_login_page_img'] != $value && $setting_used_files['panel_profile_no_img'] != $value &&
                        $setting_used_files['panel_normal_no_img'] != $value && $setting_used_files['panel_portrait_no_img'] != $value && $setting_used_files['panel_landscape_no_img'] != $value &&
                        $setting_used_files['powered_by_image'] != $value
                    ) {
                        $check = 'yes';
                    }

                    if ($check == 'yes') {
                        $this->common->deleteImageToFolder('app', $value, 1);
                        $this->common->deleteImageToFolder('app', $value, 2);
                        $this->common->deleteImageToFolder('app', $value, 3);
                    }
                }
            }

            return response()->json(['status' => 200, 'success' => __('label.data_clear_successfully')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function DownloadSqlFile()
    {
        try {

            Artisan::call('config:clear');

            $storageAt = storage_path() . "/app/public/database";
            if (!file_exists($storageAt)) {
                File::makeDirectory($storageAt, 0755, true, true);
            }

            $mysqlHostName = env('DB_HOST');
            $mysqlUserName = env('DB_USERNAME');
            $mysqlPassword = env('DB_PASSWORD');
            $DbName = env('DB_DATABASE');

            // get all table name
            $result = DB::select("SHOW TABLES");
            $prep = "Tables_in_$DbName";

            foreach ($result as $res) {
                $tables[] =  $res->$prep;
            }

            $connect = new \PDO("mysql:host=$mysqlHostName;dbname=$DbName;charset=utf8", "$mysqlUserName", "$mysqlPassword", array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
            $statement = $connect->prepare("SHOW TABLES");
            $statement->execute();
            $result = $statement->fetchAll();

            $output = '';
            foreach ($tables as $table) {

                $show_table_query = "SHOW CREATE TABLE " . $table . "";
                $statement = $connect->prepare($show_table_query);
                $statement->execute();
                $show_table_result = $statement->fetchAll();

                foreach ($show_table_result as $show_table_row) {
                    $output .= "\n\n" . $show_table_row["Create Table"] . ";\n\n";
                }
                $select_query = "SELECT * FROM " . $table . "";
                $statement = $connect->prepare($select_query);
                $statement->execute();
                $total_row = $statement->rowCount();

                for ($count = 0; $count < $total_row; $count++) {
                    $single_result = $statement->fetch(\PDO::FETCH_ASSOC);
                    $table_column_array = array_keys($single_result);
                    $table_value_array = array_values($single_result);
                    $output .= "\nINSERT INTO $table (";
                    $output .= "`" . implode("`, `", $table_column_array) . "`) VALUES (";
                    $output .= "'" . implode("', '", $table_value_array) . "');\n";
                }
            }

            $file_name = App_Name() . '_db_' . date('d_m_Y') . '.sql';
            $file_handle = fopen(storage_path() . '/app/public/database/' . $file_name, 'w+');
            fwrite($file_handle, $output);
            fclose($file_handle);
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename=' . basename($file_name));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize(storage_path() . '/app/public/database/' . $file_name));
            ob_clean();
            flush();
            readfile(storage_path() . '/app/public/database/' . $file_name);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function CleanDatabase()
    {
        try {

            Avatar::query()->truncate();
            Banner::query()->truncate();
            Bookmark::query()->truncate();
            Cast::query()->truncate();
            Category::query()->truncate();
            Channel::query()->truncate();
            Comment::query()->truncate();
            Coupon::query()->truncate();
            Device_Sync::query()->truncate();
            Device_Watching::query()->truncate();
            Home_Section::query()->truncate();
            Language::query()->truncate();
            Like::query()->truncate();
            Notification::query()->truncate();
            Onboarding_Screen::query()->truncate();
            Package::query()->truncate();
            Package_Detail::query()->truncate();
            Producer::query()->truncate();
            Read_Notification::query()->truncate();
            Refer_Earn::query()->truncate();
            Rent_Price_List::query()->truncate();
            Rent_Transaction::query()->truncate();
            Review::query()->truncate();
            Season::query()->truncate();
            Shorts::query()->truncate();
            Shorts_Episode::query()->truncate();
            Social_Link::query()->truncate();
            Transaction::query()->truncate();
            TV_Login::query()->truncate();
            TVShow::query()->truncate();
            TVShow_Video::query()->truncate();
            Type::query()->truncate();
            User::query()->truncate();
            UserInterest::query()->truncate();
            Video::query()->truncate();
            Video_Watch::query()->truncate();
            View::query()->truncate();
            WalletTransaction::query()->truncate();
            Withdrawal_Request::query()->truncate();

            return response()->json(['status' => 200, 'success' => __('label.data_clean_successfully')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
