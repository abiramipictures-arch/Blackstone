<?php

use App\Models\Common;
use App\Models\General_Setting;
use App\Models\Storage_Setting;
use App\Models\Type;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;

// Setting
function Setting_Data($key = "")
{
    $setting = General_Setting::get();
    $data = [];
    foreach ($setting as $value) {
        $data[$value->key] = $value->value;
    }
    if ($key != "") {
        return $data[$key] ?? $data;
    }
    return $data;
}
function App_Name()
{
    $data = Setting_Data();

    if (isset($data['app_name']) && $data['app_name'] != "") {
        return $data['app_name'];
    }
    return env('APP_NAME');
}
function Tab_Icon()
{
    $Setting_Data = Setting_Data();

    $name = $Setting_Data['app_logo'];
    $folder = "app";
    $model = new Common();

    return $model->getImage($folder, $name, 'normal', $Setting_Data['app_logo_storage_type']);
}
function TMDB_Status()
{
    $data = Setting_Data();
    $return = 0;
    if ($data['tmdb_status'] == 1) {
        $return = 1;
    } else {
        $return = 0;
    }
    return $return;
}
function TMDB_API_Key()
{
    $data = Setting_Data();
    return $data['tmdb_api_key'];
}
function Admin_Parent_Control_Status()
{
    $data = Setting_Data();
    $return = 0;
    if ($data['parent_control_status'] == 1) {
        $return = 1;
    } else {
        $return = 0;
    }
    return $return;
}
function Login_Image()
{
    $Panel_Data = Setting_Data();

    $appName = Config::get('app.image_url');
    $name = $Panel_Data['panel_login_page_img'];
    $folder = "app";

    if ($name != "" && Storage::disk('public')->exists($folder . '/' . $name)) {
        return $appName . $folder . '/' . $name;
    }
    return asset('assets/imgs/login.webp');
}
function Get_Type_List()
{
    $data = Type::where('status', 1)->orderby('sort_order', 'asc')->get();
    return $data;
}
function Storage_Setting()
{
    $setting = Storage_Setting::get();
    $data = [];
    foreach ($setting as $value) {
        $data[$value->key] = $value->value;
    }
    return $data;
}
function Storage_Type()
{
    $data = Storage_Setting();
    return $data['storage_type'];
}
function Commission()
{
    $setting = Setting_Data();

    return $setting['commission'];
}

// Basic
function Currency_Code()
{
    $setting = General_Setting::get();
    $data = [];
    foreach ($setting as $value) {
        $data[$value->key] = $value->value;
    }
    return $data['currency_code'];
}
function String_Cut($string, $len)
{
    if (strlen($string) > $len) {
        $string = mb_substr(strip_tags($string), 0, $len, 'utf-8') . '...';
        // $string = substr(strip_tags($string),0,$len).'...';
    }
    return $string;
}
function No_Format($num)
{
    if ($num > 1000) {
        $x = round($num);
        $x_number_format = number_format($x);
        $x_array = explode(',', $x_number_format);
        $x_parts = array('K', 'M', 'B', 'T');
        $x_count_parts = count($x_array) - 1;
        $x_display = $x;
        $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
        $x_display .= $x_parts[$x_count_parts - 1];
        return $x_display;
    }
    return $num;
}
function Time_To_Milliseconds($str)
{

    $time = explode(":", $str);

    $hour = (int) $time[0] * 60 * 60 * 1000;
    $minute = (int) $time[1] * 60 * 1000;
    $sec = (int) $time[2] * 1000;
    $result = $hour + $minute + $sec;
    return $result;
}

// Producer
function Producer_Data()
{
    if (Auth::guard('producer')->user()) {
        return Auth::guard('producer')->user();
    } else {
        return redirect()->route('producer.logout');
    }
}

// Access & Installation
function Demo_Mode()
{
    if (env('DEMO_MODE') == 'ON') {
        return 0;
    }
    return 1;
}
function Demo_Domain()
{
    $domain = request()->getHost();
    if ($domain == base64_decode('bG9jYWxob3N0') || $domain == base64_decode('ZGV2LmRpdmluZXRlY2hzLmNvbQ==') || $domain == base64_decode('c3RhZy5kaXZpbmV0ZWNocy5jb20=') || $domain == base64_decode('ZHRsaXZlLmRpdmluZXRlY2hzLmNvbQ==') || $domain == base64_decode('ZHRsaXZlYXBwLmRpdmluZXRlY2hzLmNvbQ==')) {
        return 1;
    } else {
        return 0;
    }
}
function Item_Code()
{
    return base64_decode('NDQ1NDgzNTg=');
}
function Set_Environment_Value($envKey, $envValue)
{
    $envFile = app()->environmentFilePath();
    $str = file_get_contents($envFile);

    $oldValue = env($envKey);

    if (strpos($str, $envKey) !== false) {
        $str = str_replace("{$envKey}={$oldValue}", "{$envKey}={$envValue}", $str);
    } else {
        $str .= "{$envKey}={$envValue}\n";
    }

    $fp = fopen($envFile, 'w');
    fwrite($fp, $str);
    fclose($fp);
    return $envValue;
}
