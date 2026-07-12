<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\General_Setting;
use App\Models\Onboarding_Screen;
use App\Models\Smtp_Setting;
use App\Models\Social_Link;
use App\Models\Storage_Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class AppSettingController extends Controller
{
    private $folder = "app";
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index()
    {
        try {

            $params['data'] = Setting_Data();
            if ($params['data']) {

                $params['data']['app_logo'] = $this->common->getImage($this->folder, $params['data']['app_logo'], 'normal', $params['data']['app_logo_storage_type']);

                $params['social_link'] = Social_Link::get();
                $this->common->imageNameToUrl($params['social_link'], 'image', $this->folder, 'normal');

                $params['onboarding_screen'] = Onboarding_Screen::get();
                $this->common->imageNameToUrl($params['onboarding_screen'], 'image', $this->folder, 'normal');

                $params['smtp'] = Smtp_Setting::latest()->first();
                $params['storage'] = Storage_Setting();

                if (Demo_Mode() == 0) {
                    $params['data']['vapid_key'] = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";
                    $params['data']['vdocipher_api_secret_key'] = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";
                    $params['data']['web_client_id'] = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx";

                    $params['smtp']['protocol'] = "xxxxxx";
                    $params['smtp']['host'] = "xxxx.xxxx.xxxx";
                    $params['smtp']['port'] = "xxx";
                    $params['smtp']['user'] = "xxxxxx.xxxx@xxx.xxx";
                    $params['smtp']['pass'] = "xxxxxxxx";
                    $params['smtp']['from_name'] = "xxxxxx";
                    $params['smtp']['from_email'] = "xxxxxx.xxxx@xxx.xxx";

                    $params['storage']['s3_access_key'] = "xxxxxxxxxxxxxx";
                    $params['storage']['s3_secret_key'] = "xxxxxxxxxxxxxxxxxxxxxxxxxxx";
                    $params['storage']['s3_region'] = "xx-xxxxx-x";
                    $params['storage']['s3_bucket_name'] = "xxxxxxx";
                    $params['storage']['s3_endpoint'] = "s3.xxxxxxxxx.amazonaws.com";

                    $params['storage']['wasabi_access_key'] = "xxxxxxxxxxxxxx";
                    $params['storage']['wasabi_secret_key'] = "xxxxxxxxxxxxxxxxxxxxxxxxxxx";
                    $params['storage']['wasabi_region'] = "xx-xxxxxxxxx-x";
                    $params['storage']['wasabi_bucket_name'] = "xxxxxxx";
                    $params['storage']['wasabi_endpoint'] = "https://s3.xx-xxxxxxxxxx-x.wasabisys.com";
                }

                return view('admin.app_setting.index', $params);
            }
            return redirect()->back()->with('error', __('label.data_not_found'));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function app(Request $request)
    {
        try {

            $data = $request->all();
            $data["app_name"] = isset($data['app_name']) ? $data['app_name'] : '';
            $data["app_version"] = isset($data['app_version']) ? $data['app_version'] : '';
            $data["author"] = isset($data['author']) ? $data['author'] : '';
            $data["email"] = isset($data['email']) ? $data['email'] : '';
            $data["contact"] = isset($data['contact']) ? $data['contact'] : '';
            $data["website"] = isset($data['website']) ? $data['website'] : '';
            $data["app_desripation"] = isset($data['app_desripation']) ? $data['app_desripation'] : '';

            if (isset($data['app_logo'])) {
                $data['app_logo_storage_type'] = Storage_Type();
                $data['app_logo'] = $this->common->saveImage($data['app_logo'], $this->folder, 'app_', $data['app_logo_storage_type']);
                $this->common->deleteImageToFolder($this->folder, basename($data['old_app_logo']), $data['old_storage_type']);
            }
            unset($data['old_storage_type']);

            foreach ($data as $key => $value) {
                $setting = General_Setting::where('key', $key)->first();
                if (isset($setting->id)) {
                    $setting->value = $value;
                    $setting->save();
                }
            }
            return response()->json(['status' => 200, 'success' => __('label.setting_save')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function apitoken(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'api_token' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()->all()]);
            }

            $path = base_path('.env');
            if (file_exists($path)) {

                $content = file_get_contents($path);

                $key = 'API_TOKEN=' . env('API_TOKEN');
                $value = 'API_TOKEN=' . $request['api_token'];

                file_put_contents($path, str_replace($key, $value, $content));
                Artisan::call('config:clear');

                return response()->json(['status' => 200, 'success' => __('label.token_update')]);
            }
            return response()->json(['status' => 400, 'errors' => __('label.token_not_update')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function saveTmdbKey(Request $request)
    {
        try {
            if ($request['tmdb_status'] == 1) {
                $validator = Validator::make($request->all(), [
                    'tmdb_api_key' => 'required',
                ]);
                if ($validator->fails()) {
                    return response()->json(['status' => 400, 'errors' => $validator->errors()->all()]);
                }
            }

            $data = $request->all();
            $data["tmdb_status"] = isset($data['tmdb_status']) ? $data['tmdb_status'] : 0;
            $data["tmdb_api_key"] = isset($data['tmdb_api_key']) ? $data['tmdb_api_key'] : '';

            foreach ($data as $key => $value) {
                $setting = General_Setting::where('key', $key)->first();
                if (isset($setting->id)) {
                    $setting->value = $value;
                    $setting->save();
                }
            }
            return response()->json(['status' => 200, 'success' => __('label.setting_save')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function currency(Request $request)
    {
        try {

            $data = $request->all();
            $data["currency"] = isset($data['currency']) ? strtoupper($data['currency']) : '';
            $data["currency_code"] = isset($data['currency_code']) ? $data['currency_code'] : '';

            foreach ($data as $key => $value) {
                $setting = General_Setting::where('key', $key)->first();
                if (isset($setting->id)) {
                    $setting->value = $value;
                    $setting->save();
                }
            }
            return response()->json(['status' => 200, 'success' => __('label.setting_save')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function appdownload(Request $request)
    {
        try {
            $data = $request->all();
            $data['playstore_url'] = $data['playstore_url'] ?? "";
            $data['appstore_url'] = $data['appstore_url'] ?? "";

            foreach ($data as $key => $value) {
                $setting = General_Setting::where('key', $key)->first();
                if (isset($setting['id'])) {
                    $setting['value'] = $value;
                    $setting->save();
                }
            }
            return response()->json(['status' => 200, 'success' => __('label.setting_save')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function saveBasicConfigrations(Request $request)
    {
        try {
            if ($request['multiple_device_sync'] == 1) {

                $validator = Validator::make($request->all(), [
                    'no_of_device_sync' => 'required|numeric|min:1',
                ]);
                if ($validator->fails()) {
                    return response()->json(['status' => 400, 'errors' => $validator->errors()->all()]);
                }
            }

            $data = $request->all();
            $data["app_login"] = isset($data['app_login']) ? $data['app_login'] : 1;
            $data["auto_play_trailer"] = isset($data['auto_play_trailer']) ? $data['auto_play_trailer'] : 1;
            $data["active_tv_status"] = isset($data['active_tv_status']) ? $data['active_tv_status'] : 1;
            $data["parent_control_status"] = isset($data['parent_control_status']) ? $data['parent_control_status'] : 1;
            $data["watchlist_status"] = isset($data['watchlist_status']) ? $data['watchlist_status'] : 1;
            $data["download_status"] = isset($data['download_status']) ? $data['download_status'] : 1;
            $data["continue_watching_status"] = isset($data['continue_watching_status']) ? $data['continue_watching_status'] : 1;
            $data["subscription_status"] = isset($data['subscription_status']) ? $data['subscription_status'] : 1;
            $data["coupon_status"] = isset($data['coupon_status']) ? $data['coupon_status'] : 1;
            $data["rent_status"] = isset($data['rent_status']) ? $data['rent_status'] : 1;
            $data["multiple_device_sync"] = isset($data['multiple_device_sync']) ? $data['multiple_device_sync'] : 0;
            $data["no_of_device_sync"] = isset($data['no_of_device_sync']) ? $data['no_of_device_sync'] : 0;
            $data["on_boarding_screen_status"] = isset($data['on_boarding_screen_status']) ? $data['on_boarding_screen_status'] : 1;
            $data["screen_recording_status"] = isset($data['screen_recording_status']) ? $data['screen_recording_status'] : 1;
            $data["video_player_ima_ads_status"] = isset($data['video_player_ima_ads_status']) ? $data['video_player_ima_ads_status'] : 1;
            $data["auto_approve_reviews"] = isset($data['auto_approve_reviews']) ? $data['auto_approve_reviews'] : 0;

            foreach ($data as $key => $value) {
                $setting = General_Setting::where('key', $key)->first();
                if (isset($setting->id)) {
                    $setting->value = $value;
                    $setting->save();
                }
            }
            return response()->json(['status' => 200, 'success' => __('label.setting_save')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function smtpSave(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'status' => 'required',
                'host' => 'required',
                'port' => 'required',
                'protocol' => 'required',
                'user' => 'required',
                'pass' => 'required',
                'from_name' => 'required',
                'from_email' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()->all()]);
            }

            if (isset($request->id) && $request->id != null && $request->id != "") {

                $smtp = Smtp_Setting::where('id', $request->id)->first();
                if (isset($smtp->id)) {
                    $smtp->protocol = $request->protocol;
                    $smtp->host = $request->host;
                    $smtp->port = $request->port;
                    $smtp->user = $request->user;
                    $smtp->pass = $request->pass;
                    $smtp->from_name = $request->from_name;
                    $smtp->from_email = $request->from_email;
                    $smtp->status = $request->status;
                    if ($smtp->save()) {
                        return response()->json(['status' => 200, 'success' => __('label.setting_save')]);
                    }
                    return response()->json(['status' => 400, 'errors' => __('label.data_not_updated')]);
                }
            } else {

                $insert = new Smtp_Setting();
                $insert->protocol = $request->protocol;
                $insert->host = $request->host;
                $insert->port = $request->port;
                $insert->user = $request->user;
                $insert->pass = $request->pass;
                $insert->from_name = $request->from_name;
                $insert->from_email = $request->from_email;
                $insert->status = $request->status;
                if ($insert->save()) {

                    $this->common->SetSmtpConfig();
                    return response()->json(['status' => 200, 'success' => __('label.setting_save')]);
                }
                return response()->json(['status' => 400, 'errors' => __('label.data_not_updated')]);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function saveSocialLink(Request $request)
    {
        try {

            $arr_name = $request['name'];
            $arr_url = $request['url'];
            $arr_img = $request->file('image');
            $arr_old_image = $request['old_image'];
            $arr_storage_type = $request['step_storage_type'];
            $storage_type = Storage_Type();
            // Save New All Link
            $not_delete_img = array();
            $not_delete_ids = array();

            for ($i = 0; $i < count($arr_name); $i++) {

                if (!empty($arr_name[$i]) && !empty($arr_url[$i])) {

                    if (!empty($arr_img[$i])) {

                        $insert = new Social_Link();
                        $insert->name = $arr_name[$i];
                        $insert->url = $arr_url[$i];
                        $insert->storage_type = $storage_type;
                        $insert->image = $this->common->saveImage($arr_img[$i], $this->folder, 'soc_link_', $storage_type);
                        $insert->save();

                        $this->common->deleteImageToFolder($this->folder, $arr_old_image[$i], $arr_storage_type[$i]);
                    } else {
                        if (!empty($arr_old_image[$i])) {

                            $insert = new Social_Link();
                            $insert->name = $arr_name[$i];
                            $insert->url = $arr_url[$i];
                            $insert->storage_type = $arr_storage_type[$i];
                            $insert->image = $arr_old_image[$i];
                            $insert->save();
                            $not_delete_img[] = $arr_old_image[$i];
                        }
                    }
                    $not_delete_ids[] = $insert->id;
                }
            }

            // Delete Old All Link 
            $all_old_link = Social_Link::whereNotIn('id', $not_delete_ids)->get();
            for ($i = 0; $i < count($all_old_link); $i++) {

                if (!in_array($all_old_link[$i]['image'], $not_delete_img)) {

                    $this->common->deleteImageToFolder($this->folder, $all_old_link[$i]['image'], $all_old_link[$i]['storage_type']);
                }

                $all_old_link[$i]->delete();
            }

            return response()->json(['status' => 200, 'success' => __('label.setting_save')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function saveOnBoardingScreen(Request $request)
    {
        try {

            $arr_title = $request['title'];
            $arr_desc = $request['description'];
            $arr_img = $request->file('image');
            $arr_old_image = $request['old_image'];
            $arr_storage_type = $request['screen_storage_type'];
            $storage_type = Storage_Type();

            // Save New All Link
            $not_delete_img = array();
            $not_delete_ids = array();

            for ($i = 0; $i < count($arr_title); $i++) {

                if (!empty($arr_title[$i])) {

                    if (!empty($arr_img[$i])) {

                        $insert = new Onboarding_Screen();
                        $insert->title = $arr_title[$i];
                        $insert->description = $arr_desc[$i] ?? "";
                        $insert->storage_type = $storage_type;
                        $insert->image = $this->common->saveImage($arr_img[$i], $this->folder, 'on_board_', $storage_type);
                        $insert->save();

                        $not_delete_ids[] = $insert->id;
                        $this->common->deleteImageToFolder($this->folder, $arr_old_image[$i], $arr_storage_type[$i]);
                    } else {
                        if (!empty($arr_old_image[$i])) {

                            $insert = new Onboarding_Screen();
                            $insert->title = $arr_title[$i];
                            $insert->description = $arr_desc[$i] ?? "";
                            $insert->storage_type = $arr_storage_type[$i];
                            $insert->image = $arr_old_image[$i];
                            $insert->save();

                            $not_delete_img[] = $arr_old_image[$i];
                            $not_delete_ids[] = $insert->id;
                        }
                    }
                }
            }

            // Delete Old Data
            $all_old_data = Onboarding_Screen::whereNotIn('id', $not_delete_ids)->get();
            for ($i = 0; $i < count($all_old_data); $i++) {

                if (!in_array($all_old_data[$i]['image'], $not_delete_img)) {

                    $this->common->deleteImageToFolder($this->folder, $all_old_data[$i]['image'], $all_old_data[$i]['storage_type']);
                }
                $all_old_data[$i]->delete();
            }

            return response()->json(['status' => 200, 'success' => __('label.setting_save')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function vapIdKey(Request $request)
    {
        try {

            $data = $request->all();
            $data["vapid_key"] = isset($data['vapid_key']) ? $data['vapid_key'] : '';

            foreach ($data as $key => $value) {
                $setting = General_Setting::where('key', $key)->first();
                if (isset($setting->id)) {
                    $setting->value = $value;
                    $setting->save();
                }
            }
            return response()->json(['status' => 200, 'success' => __('label.setting_save')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function setting_commission(Request $request)
    {
        try {

            $data = $request->all();
            $data['commission'] = isset($data['commission']) ? $data['commission'] : 0;

            foreach ($data as $key => $value) {
                $setting = General_Setting::where('key', $key)->first();
                if (isset($setting->id)) {
                    $setting->value = $value;
                    $setting->save();
                }
            }
            return response()->json(['status' => 200, 'success' => __('label.setting_save')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function firebaseProjectId(Request $request)
    {
        try {
            $data = $request->all();
            $data['firebase_project_id'] = isset($data['firebase_project_id']) ? $data['firebase_project_id'] : '';

            foreach ($data as $key => $value) {
                $setting = General_Setting::where('key', $key)->first();
                if (isset($setting->id)) {
                    $setting->value = $value;
                    $setting->save();
                }
            }
            return response()->json(['status' => 200, 'success' => __('label.setting_save')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function StorageSetting(Request $request)
    {
        try {
            if ($request->storage_type == 2) {

                $rules = [
                    's3_access_key' => 'required',
                    's3_secret_key' => 'required',
                    's3_bucket_name' => 'required',
                    's3_region' => 'required',
                    's3_endpoint' => 'required'
                ];
            } else if ($request->storage_type == 3) {

                $rules = [
                    'wasabi_access_key' => 'required',
                    'wasabi_secret_key' => 'required',
                    'wasabi_bucket_name' => 'required',
                    'wasabi_region' => 'required',
                    'wasabi_endpoint' => 'required'
                ];
            } else {
                $rules['storage_type'] = 'required';
            }
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()->all()]);
            }

            $data['storage_type'] = $request['storage_type'];
            if ($request['storage_type'] == 2) {

                $data['s3_access_key'] = $request->s3_access_key ??  "";
                $data['s3_secret_key'] = $request->s3_secret_key ??  "";
                $data['s3_bucket_name'] = $request->s3_bucket_name ??  "";
                $data['s3_region'] = $request->s3_region ??  "";
                $data['s3_endpoint'] = $request->s3_endpoint ??  "";
            } else if ($request['storage_type'] == 3) {

                $data['wasabi_access_key'] = $request->wasabi_access_key ??  "";
                $data['wasabi_secret_key'] = $request->wasabi_secret_key ??  "";
                $data['wasabi_bucket_name'] = $request->wasabi_bucket_name ??  "";
                $data['wasabi_region'] = $request->wasabi_region ??  "";
                $data['wasabi_endpoint'] = $request->wasabi_endpoint ??  "";
            }

            if ($request['storage_type'] != 1 && !empty($request['id'])) {
                // Only attempt to restore old Wasabi credentials if ID exists
                $existingSettings = Storage_Setting::find($request['id']);
                if ($existingSettings) {

                    $data['wasabi_access_key'] = $existingSettings->wasabi_access_key ?? '';
                    $data['wasabi_secret_key'] = $existingSettings->wasabi_secret_key ?? '';
                    $data['wasabi_bucket_name'] = $existingSettings->wasabi_bucket_name ?? '';
                    $data['wasabi_region'] = $existingSettings->wasabi_region ?? '';
                    $data['wasabi_endpoint'] = $existingSettings->wasabi_endpoint ?? '';
                    $data['s3_access_key'] = $existingSettings->s3_access_key ?? '';
                    $data['s3_secret_key'] = $existingSettings->s3_secret_key ?? '';
                    $data['s3_bucket_name'] = $existingSettings->s3_bucket_name ?? '';
                    $data['s3_region'] = $existingSettings->s3_region ?? '';
                    $data['s3_endpoint'] = $existingSettings->s3_endpoint ?? '';
                }
            }

            foreach ($data as $key => $value) {
                $setting = Storage_Setting::where('key', $key)->first();
                if (isset($setting->id)) {
                    $setting->value = $value;
                    $setting->save();
                }
            }

            // Remove Cache
            Cache::forget('s3_storage_settings');

            return response()->json(['status' => 200, 'success' => __('label.setting_save')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function withdrawal_amount(Request $request)
    {
        try {

            $data = $request->all();
            $data["min_withdrawal_amount"] = isset($data['min_withdrawal_amount']) ? $data['min_withdrawal_amount'] : 1;

            foreach ($data as $key => $value) {
                $setting = General_Setting::where('key', $key)->first();
                if (isset($setting->id)) {
                    $setting->value = $value;
                    $setting->save();
                }
            }
            return response()->json(['status' => 200, 'success' => __('label.setting_save')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function vdocipher(Request $request)
    {
        try {
            $data = $request->all();
            $data['vdocipher_status'] = $data['vdocipher_status'] ?? '';
            $data['vdocipher_api_secret_key'] = $data['vdocipher_api_secret_key'] ?? '';

            foreach ($data as $key => $value) {
                $setting = General_Setting::where('key', $key)->first();
                if (isset($setting['id'])) {
                    $setting['value'] = $value;
                    $setting->save();
                }
            }
            return response()->json(['status' => 200, 'success' => __('label.setting_save')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function webclientid(Request $request)
    {
        try {
            $data = $request->all();
            $data['web_client_id'] = $data['web_client_id'] ?? '';

            foreach ($data as $key => $value) {
                $setting = General_Setting::where('key', $key)->first();
                if (isset($setting['id'])) {
                    $setting['value'] = $value;
                    $setting->save();
                }
            }
            return response()->json(['status' => 200, 'success' => __('label.setting_save')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function saveReferAndEarn(Request $request)
    {
        try {
            $data['refer_and_earn_status'] = $request->input('refer_and_earn_status', 0);
            if ($data['refer_and_earn_status'] == 1) {
                $validator = Validator::make($request->all(), [
                    'parent_user_earn' => 'required|numeric|min:0',
                    'child_user_earn'  => 'required|numeric|min:0',
                ]);
                if ($validator->fails()) {
                    return response()->json(['status' => 400, 'errors' => $validator->errors()->all()]);
                }
            }
            $data['parent_user_earn'] = $request->input('parent_user_earn', 0);
            $data['child_user_earn']  = $request->input('child_user_earn', 0);

            foreach ($data as $key => $value) {
                $setting = General_Setting::where('key', $key)->first();
                if (isset($setting->id)) {
                    $setting->value = $value;
                    $setting->save();
                }
            }
            return response()->json(['status' => 200, 'success' => __('label.setting_save')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
