<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Config;
use Exception;

class Common extends Model
{
    public $folder_content = "content";

    // Image Functions
    public function saveImage($org_name, $folder, $prefix = "", $storage_type = 1)
    {
        try {
            $img_ext = $org_name->getClientOriginalExtension();
            $filename = $prefix . date('Y_m_d_') . uniqid() . '.' . $img_ext;

            if ($storage_type == 1) {
                $org_name->move(base_path('storage/app/public/' . $folder), $filename);
            } else if ($storage_type == 2) {
                $org_name->storeAs($folder, $filename, 's3');
            } else if ($storage_type == 3) {
                $org_name->storeAs($folder, $filename, 'wasabi');
            }
            return $filename;
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function URLSaveInImage($url, $folder, $prefix = "", $storage_type = 1)
    {
        try {
            // Get extension safely
            $ext = pathinfo(parse_url($url, PHP_URL_PATH), PATHINFO_EXTENSION);
            if (!$ext) {
                $ext = 'jpg'; // fallback
            }

            // Generate unique filename
            $image_name = $prefix . date('Y_m_d_') . uniqid() . '.' . $ext;

            if ($storage_type == 1) {
                // Save locally (default)
                $path = storage_path('app/public/' . $folder . '/');

                if (!file_exists($path)) {
                    mkdir($path, 0777, true); // ensure folder exists
                }

                file_put_contents($path . $image_name, file_get_contents($url));

                return $image_name;
            } else if ($storage_type == 2) {
                // Save directly to S3
                $path = $folder . '/' . $image_name;
                Storage::disk('s3')->put($path, file_get_contents($url), 'public');

                return $image_name;
            } else if ($storage_type == 3) {

                // Save directly to wasabi
                $path = $folder . '/' . $image_name;
                Storage::disk('wasabi')->put($path, file_get_contents($url), 'public');

                return $image_name;
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function getImage($folder = "", $name = "", $type = "", $storage_type = 1)
    {
        try {

            if ($storage_type == 1) {

                $appName = Config::get('app.image_url');

                if ($folder && $name && Storage::disk('public')->exists($folder . '/' . $name)) {
                    return $appName . $folder . '/' . $name;
                }
                return $this->byDefaultImg($type);
            } else if ($storage_type == 2) {

                if ($name != "" && $folder != "") {

                    $storage = Storage_Setting();
                    $appName = $storage['s3_endpoint'];
                    $bucket_name = $storage['s3_bucket_name'];

                    if (Storage::disk('s3')->exists($folder . '/' . $name)) {
                        return 'https://' . $bucket_name . '.' . $appName . '/' . $folder . '/' . $name;
                    }
                }
                return $this->byDefaultImg($type);
            } else if ($storage_type == 3) {

                if ($name != "" && $folder != "") {

                    if (Storage::disk('wasabi')->exists($folder . '/' . $name)) {
                        $storage = Storage_Setting();
                        $appName = $storage['wasabi_endpoint'];
                        $bucket_name = $storage['wasabi_bucket_name'];

                        return $appName . '/' . $bucket_name . '/' . $folder . '/' . $name;
                    }
                }
                return $this->byDefaultImg($type);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function imageNameToUrl($array, $column, $folder, $type = "")
    {
        try {
            foreach ($array as $key => $value) {

                if ($value['storage_type'] == 1) {

                    $appName = Config::get('app.image_url');

                    if (!empty($value[$column]) && Storage::disk('public')->exists($folder . '/' . $value[$column])) {
                        $value[$column] = $appName . $folder . '/' . $value[$column];
                    } else {
                        $value[$column] = $this->byDefaultImg($type);
                    }
                } else if ($value['storage_type'] == 2) {

                    if (isset($value[$column]) && $value[$column] != "") {

                        $storage = Storage_Setting();
                        $appName = $storage['s3_endpoint'];
                        $bucket_name = $storage['s3_bucket_name'];

                        $url =   'https://' . $bucket_name . '.' . $appName . '/' . $folder . '/' . $value[$column];
                        if (Storage::disk('s3')->exists($folder . '/' . $value[$column])) {
                            $value[$column] = $url;
                        } else {
                            $value[$column] = $this->byDefaultImg($type);
                        }
                    } else {
                        $value[$column] = $this->byDefaultImg($type);
                    }
                } else if ($value['storage_type'] == 3) {

                    if (isset($value[$column]) && $value[$column] != "") {

                        $storage = Storage_Setting();
                        $appName = $storage['wasabi_endpoint'];
                        $bucket_name = $storage['wasabi_bucket_name'];

                        $url =   $appName . '/' . $bucket_name . '/' . $folder . '/' . $value[$column];
                        if (Storage::disk('wasabi')->exists($folder . '/' . $value[$column])) {
                            $value[$column] = $url;
                        } else {
                            $value[$column] = $this->byDefaultImg($type);
                        }
                    } else {
                        $value[$column] = $this->byDefaultImg($type);
                    }
                }
            }
            return $array;
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function byDefaultImg($type = "") // profile, landscape, portrait, normal
    {
        $panel_data = Setting_Data();
        $appName = Config::get('app.image_url');
        $folder = "app";

        if ($type == "profile") {

            $name = $panel_data['panel_profile_no_img'];
            if ($folder && $name && Storage::disk('public')->exists($folder . '/' . $name)) {
                return $appName . $folder . '/' . $name;
            }
            return asset('assets/imgs/profile.png');
        } else if ($type == "landscape") {

            $name = $panel_data['panel_landscape_no_img'];
            if ($folder && $name && Storage::disk('public')->exists($folder . '/' . $name)) {
                return $appName . $folder . '/' . $name;
            }
            return $this->normalImg();
        } else if ($type == "portrait") {

            $name = $panel_data['panel_portrait_no_img'];
            if ($folder && $name && Storage::disk('public')->exists($folder . '/' . $name)) {
                return $appName . $folder . '/' . $name;
            }
            return $this->normalImg();
        }
        return $this->normalImg();
    }
    public function normalImg() // Normal Image
    {
        $panel_data = Setting_Data();
        $appName = Config::get('app.image_url');
        $folder = "app";

        $name = $panel_data['panel_normal_no_img'];

        if ($name != "") {

            if (Storage::disk('public')->exists($folder . '/' . $name)) {
                return $appName . $folder . '/' . $name;
            } else {
                return asset('assets/imgs/no_img.png');
            }
        } else {
            return asset('assets/imgs/no_img.png');
        }
    }
    public function getVideo($folder = "", $name = "", $storage_type = 1)
    {
        try {

            if ($storage_type == 1) {

                $appName = Config::get('app.image_url');

                if ($folder != "" && $name != "") {
                    if (Storage::disk('public')->exists($folder . '/' . $name)) {
                        $name = $appName . $folder . '/' . $name;
                    } else {
                        $name = "";
                    }
                } else {
                    $name = "";
                }
            } else if ($storage_type == 2) {

                if ($name != "" && $folder != "") {

                    $storage = Storage_Setting();
                    $appName = $storage['s3_endpoint'];
                    $bucket_name = $storage['s3_bucket_name'];

                    if (Storage::disk('s3')->exists($folder . '/' . $name)) {
                        $name =  'https://' . $bucket_name . '.' . $appName . '/' . $folder . '/' . $name;
                    } else {
                        $name = "";
                    }
                } else {
                    $name = "";
                }
            } else if ($storage_type == 3) {

                if ($name != "" && $folder != "") {

                    $storage = Storage_Setting();
                    $appName = $storage['wasabi_endpoint'];
                    $bucket_name = $storage['wasabi_bucket_name'];

                    if (Storage::disk('wasabi')->exists($folder . '/' . $name)) {
                        $name =  $appName . '/' . $bucket_name . '/' . $folder . '/' . $name;
                    } else {
                        $name = "";
                    }
                } else {
                    $name = "";
                }
            }
            return $name;
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function videoNameToUrl($array, $column, $folder)
    {
        try {

            foreach ($array as $key => $value) {

                if ($value['video_storage_type'] == 1) {
                    $appName = Config::get('app.image_url');

                    if (isset($value[$column]) && $value[$column] != "") {

                        if (Storage::disk('public')->exists($folder . '/' . $value[$column])) {
                            $value[$column] = $appName . $folder . '/' . $value[$column];
                        } else {
                            $value[$column] = "";
                        }
                    } else {

                        $value[$column] = "";
                    }
                } else if ($value['video_storage_type'] == 2) {

                    if (isset($value[$column]) && $value[$column] != "") {

                        $storage = Storage_Setting();
                        $appName = $storage['s3_endpoint'];
                        $bucket_name = $storage['s3_bucket_name'];

                        $url =   'https://' . $bucket_name . '.' . $appName . '/' . $folder . '/' . $value[$column];
                        if (Storage::disk('s3')->exists($folder . '/' . $value[$column])) {
                            $value[$column] = $url;
                        } else {
                            $value[$column] = "";
                        }
                    } else {
                        $value[$column] = "";
                    }
                } else if ($value['video_storage_type'] == 3) {

                    if (isset($value[$column]) && $value[$column] != "") {

                        $storage = Storage_Setting();
                        $appName = $storage['wasabi_endpoint'];
                        $bucket_name = $storage['wasabi_bucket_name'];

                        $url =   $appName . '/' . $bucket_name . '/' . $folder . '/' . $value[$column];
                        if (Storage::disk('wasabi')->exists($folder . '/' . $value[$column])) {
                            $value[$column] = $url;
                        } else {
                            $value[$column] = "";
                        }
                    } else {
                        $value[$column] = "";
                    }
                }
            }
            return $array;
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function TrailerNameToUrl($array, $column, $folder)
    {
        try {

            foreach ($array as $key => $value) {

                if ($value['trailer_storage_type'] == 1) {

                    $appName = Config::get('app.image_url');

                    if (isset($value[$column]) && $value[$column] != "") {

                        if (Storage::disk('public')->exists($folder . '/' . $value[$column])) {
                            $value[$column] = $appName . $folder . '/' . $value[$column];
                        } else {
                            $value[$column] = "";
                        }
                    } else {

                        $value[$column] = "";
                    }
                } else if ($value['trailer_storage_type'] == 2) {

                    if (isset($value[$column]) && $value[$column] != "") {

                        $storage = Storage_Setting();
                        $appName = $storage['s3_endpoint'];
                        $bucket_name = $storage['s3_bucket_name'];

                        $url =   'https://' . $bucket_name . '.' . $appName . '/' . $folder . '/' . $value[$column];
                        if (Storage::disk('s3')->exists($folder . '/' . $value[$column])) {
                            $value[$column] = $url;
                        } else {
                            $value[$column] = "";
                        }
                    } else {
                        $value[$column] = "";
                    }
                } else if ($value['trailer_storage_type'] == 3) {

                    if (isset($value[$column]) && $value[$column] != "") {

                        $storage = Storage_Setting();
                        $appName = $storage['wasabi_endpoint'];
                        $bucket_name = $storage['wasabi_bucket_name'];

                        $url =   $appName . '/' . $bucket_name . '/' . $folder . '/' . $value[$column];
                        if (Storage::disk('wasabi')->exists($folder . '/' . $value[$column])) {
                            $value[$column] = $url;
                        } else {
                            $value[$column] = "";
                        }
                    } else {
                        $value[$column] = "";
                    }
                }
            }
            return $array;
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function deleteImageToFolder($folder, $name, $storage_type)
    {
        try {

            if ($storage_type == 1) {
                Storage::disk('public')->delete($folder . '/' . $name);
            } else if ($storage_type == 2) {
                Storage::disk('s3')->delete($folder . '/' . $name);
            } else if ($storage_type == 3) {
                Storage::disk('wasabi')->delete($folder . '/' . $name);
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function getAvatarImage($avatar_id, $folder)
    {
        try {

            $avatar = Avatar::where('id', $avatar_id)->first();
            if (isset($avatar) && $avatar != null) {
                return $this->getImage($folder, $avatar['image'], 'profile', $avatar['storage_type']);
            } else {
                return $this->byDefaultImg('profile');
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }

    // API's Functions
    public function API_Response($status_code, $message, $array = [], $pagination = '')
    {
        try {
            $data['status'] = $status_code;
            $data['message'] = $message;

            if ($status_code == 200) {
                $data['result'] = $array;
            }

            if ($pagination) {
                $data['total_rows'] = $pagination['total_rows'];
                $data['total_page'] = $pagination['total_page'];
                $data['current_page'] = $pagination['current_page'];
                $data['more_page'] = $pagination['more_page'];
            }
            return $data;
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function more_page($current_page, $page_size)
    {
        try {
            $more_page = false;
            if ($current_page < $page_size) {
                $more_page = true;
            }
            return $more_page;
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function pagination_array($total_rows, $page_size, $current_page, $more_page)
    {
        try {
            $array['total_rows'] = $total_rows;
            $array['total_page'] = $page_size;
            $array['current_page'] = (int) $current_page;
            $array['more_page'] = $more_page;

            return $array;
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }

    // Common Functions
    public function userName($string)
    {
        $rand_number = rand(0, 1000);
        $user_name = '@user_' . $string . $rand_number;

        $check = User::where('user_name', $user_name)->first();
        if ($check) {
            $this->userName($string);
        }
        return $user_name;
    }
    public function SetSmtpConfig()
    {
        $smtp = Smtp_Setting::latest()->first();
        if (isset($smtp) && $smtp != null && $smtp['status'] == 1) {

            if ($smtp) {
                $data = [
                    'driver' => 'smtp',
                    'host' => $smtp->host,
                    'port' => $smtp->port,
                    'encryption' => 'tls',
                    'username' => $smtp->user,
                    'password' => $smtp->pass,
                    'from' => [
                        'address' => $smtp->from_email,
                        'name' => $smtp->from_name
                    ]
                ];
                Config::set('mail', $data);
            }
        }
        return true;
    }
    public function NotificationConfiguration($type)
    {
        $data = Notification_Configuration::where('type', $type)->first();
        return $data;
    }
    public function sendNotification($array)
    {
        $notification = Setting_Data();
        $ONESIGNAL_APP_ID = $notification['onesignal_app_id'];
        $ONESIGNAL_REST_KEY = $notification['onesignal_rest_key'];

        $fields = array(
            'app_id' => $ONESIGNAL_APP_ID,
            'included_segments' => array('All'),
            'data' => $array,
            'headings' => array("en" => $array['name']),
            'contents' => array("en" => $array['description']),
            'big_picture' => $array['image'],
        );

        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Basic ' . $ONESIGNAL_REST_KEY,
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        curl_close($ch);
    }
    public function save_notification($title = "", $user_id = 0)
    {
        try {

            $user = User::find($user_id);
            $device_token_list = Device_Sync::where('user_id', $user_id)->latest()->get();
            if ($user && count($device_token_list) > 0) {

                // Separate tokens by device type
                $android_tokens = [];
                $ios_tokens = [];
                foreach ($device_token_list as $device) {
                    if ($device->device_type == 1 && !empty($device->device_token)) {
                        $android_tokens[] = $device->device_token;
                    } elseif ($device->device_type == 2 && !empty($device->device_token)) {
                        $ios_tokens[] = $device->device_token;
                    }
                }

                $setting = Setting_Data();
                $ONESIGNAL_APP_ID = $setting['onesignal_app_id'];
                $ONESIGNAL_REST_KEY = $setting['onesignal_rest_key'];

                // Common payload
                $fields = [
                    'app_id' => $ONESIGNAL_APP_ID,
                    'headings' => ['en' => App_Name()],
                    'contents' => ['en' => $title],
                    'channel_for_external_user_ids' => 'push'
                ];
                // Add Android tokens
                if (!empty($android_tokens)) {
                    $fields['include_android_reg_ids'] = $android_tokens;
                    $fields['isAndroid'] = true;
                }
                // Add iOS tokens
                if (!empty($ios_tokens)) {
                    $fields['include_player_ids'] = $ios_tokens;
                    $fields['isIos'] = true;
                }
                $fields = json_encode($fields);

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json; charset=utf-8',
                    'Authorization: Basic ' . $ONESIGNAL_REST_KEY
                ]);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                curl_setopt($ch, CURLOPT_HEADER, FALSE);
                curl_setopt($ch, CURLOPT_POST, TRUE);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                $response = curl_exec($ch);
                curl_close($ch);

                return true;
            }

            return true;
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function package_expiry()
    {
        $all_data = Transaction::where('status', 1)->get();

        foreach ($all_data as $transaction) {
            if ($transaction->expiry_date <= now()) {
                $transaction->status = 0;
                $transaction->save();
            }
        }
        return true;
    }
    public function coupon_expiry()
    {
        $all_data = Coupon::where('status', 1)->get();
        $today = date('Y-m-d');

        foreach ($all_data as $coupon) {

            if ($coupon->end_date < $today) {
                $coupon->status = 0;
                $coupon->save();
            }
        }
        return true;
    }
    public function is_any_package_buy($user_id)
    {
        $this->package_expiry();

        $is_buy = Transaction::where('user_id', $user_id)->where('status', 1)->where('transaction_status', 2)->orderBy('id', 'asc')->first();
        if (!empty($is_buy)) {
            return 1;
        } else {
            return 0;
        }
    }
    public function rent_expiry()
    {
        $all_data = Rent_Transaction::where('status', 1)->get();
        foreach ($all_data as $transaction) {
            if ($transaction->expiry_date <= now()) {
                $transaction->status = 0;
                $transaction->save();
            }
        }
        return true;
    }
    public function is_rent_buy($user_id, $video_type, $sub_video_type, $video_id)
    {
        $this->rent_expiry();

        $is_buy = Rent_Transaction::where('user_id', $user_id)->where('video_type', $video_type)->where('sub_video_type', $sub_video_type)->where('video_id', $video_id)->where('status', 1)->where('transaction_status', 2)->first();
        if (!empty($is_buy)) {
            return 1;
        } else {
            return 0;
        }
    }
    public function rent_expiry_date($user_id, $video_type, $sub_video_type, $video_id)
    {
        $this->rent_expiry();

        $is_buy = Rent_Transaction::where('user_id', $user_id)->where('video_type', $video_type)->where('sub_video_type', $sub_video_type)->where('video_id', $video_id)->where('status', 1)->where('transaction_status', 2)->first();
        return $is_buy['expiry_date'] ?? "";
    }
    public function Send_Mail($type, $email, $content_title = "")
    {
        try {

            $this->SetSmtpConfig();

            $smtp = Smtp_Setting::latest()->first();
            if (isset($smtp) && $smtp != false && $smtp['status'] == 1) {

                if ($type == 1) { // Login
                    $title = App_Name() . " - Login";
                    $body = "Welcome to " . App_Name() . " App & Enjoy this app.";
                } else if ($type == 2) { // package_buy
                    $title = App_Name() . " -  Subscription Confirmed!";
                    $body = "Welcome to " . App_Name() . "! Your transaction was successful. Enjoy access to exclusive content and a premium experience!";
                } else if ($type == 3) { // rent_buy
                    $title = App_Name() . " -  Rental Confirmed!";
                    $body = "Thank you for renting *" . $content_title . "* on " . App_Name() . "! Your transaction was successful. Enjoy your content!";
                } else {
                    return true;
                }

                $details = [
                    'title' => $title,
                    'body' => $body
                ];

                // Send Mail
                Mail::to($email)->send(new \App\Mail\mail($details));
                return true;
            } else {
                return true;
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function tv_login_code()
    {
        $code = rand(1000, 9999);
        $check = TV_Login::where('unique_code', $code)->where('status', 1)->where('user_id', '!=', 0)->first();
        if (isset($check)) {

            $this->tv_login_code();
        } else {
            return  (string) $code;
        }
    }
    public function add_url_to_array($type, $array) // type = 1- Video, 2- TVShow, 3- Episode (TVShow Video), 4- Shorts
    {
        try {

            for ($i = 0; $i < count($array); $i++) {

                $array[$i]['thumbnail'] = $this->getImage($this->folder_content, $array[$i]['thumbnail'], 'portrait', $array[$i]['storage_type']);
                if ($type != 4) {
                    $array[$i]['landscape'] = $this->getImage($this->folder_content, $array[$i]['landscape'], 'landscape', $array[$i]['storage_type']);
                }

                if ($type == 1 || $type == 3) {

                    if ($array[$i]['video_upload_type'] == "server_video") {
                        $array[$i]['video_320'] = $this->getVideo($this->folder_content, $array[$i]['video_320'], $array[$i]['video_storage_type']);
                        $array[$i]['video_480'] = $this->getVideo($this->folder_content, $array[$i]['video_480'], $array[$i]['video_storage_type']);
                        $array[$i]['video_720'] = $this->getVideo($this->folder_content, $array[$i]['video_720'], $array[$i]['video_storage_type']);
                        $array[$i]['video_1080'] = $this->getVideo($this->folder_content, $array[$i]['video_1080'], $array[$i]['video_storage_type']);
                    }
                    if ($array[$i]['subtitle_type'] == "server_video") {
                        $array[$i]['subtitle_1'] = $this->getVideo($this->folder_content, $array[$i]['subtitle_1'], $array[$i]['subtitle_storage_type']);
                        $array[$i]['subtitle_2'] = $this->getVideo($this->folder_content, $array[$i]['subtitle_2'], $array[$i]['subtitle_storage_type']);
                        $array[$i]['subtitle_3'] = $this->getVideo($this->folder_content, $array[$i]['subtitle_3'], $array[$i]['subtitle_storage_type']);
                    }
                }
                if ($type == 1 || $type == 2 || $type == 4) {

                    if ($array[$i]['trailer_type'] == "server_video") {
                        $array[$i]['trailer_url'] = $this->getVideo($this->folder_content, $array[$i]['trailer_url'], $array[$i]['trailer_storage_type']);
                    }
                }
            }
            return $array;
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function home_section_query($user_id, $user_parent_control_status, $section_type, $video_type, $sub_video_type, $type_id, $content_ids, $category_id, $language_id, $channel_id, $order_by_upload, $order_by_view, $premium_video, $no_of_content)
    {
        try {

            if ($section_type == 1) {

                $contents_ids = explode(',', $content_ids);
                if ($video_type == 1 || (in_array($video_type, [5, 6, 7]) && $sub_video_type == 1)) {

                    $content = Video::whereIn('id', $contents_ids)->where('video_type', $video_type)->where('status', 1);
                } else if ($video_type == 2 || (in_array($video_type, [5, 6, 7]) && $sub_video_type == 2)) {

                    $content = TVShow::whereIn('id', $contents_ids)->where('video_type', $video_type)->where('status', 1);
                }
            } else {

                if ($video_type == 1 || (in_array($video_type, [5, 6, 7]) && $sub_video_type == 1)) {

                    $content = Video::where('video_type', $video_type)->where('status', 1);
                } else if ($video_type == 2 || (in_array($video_type, [5, 6, 7]) && $sub_video_type == 2)) {

                    $content = TVShow::where('video_type', $video_type)->where('status', 1);
                }

                if ($type_id != 0) {
                    $content->where('type_id', $type_id);
                }
                if ($category_id != 0) {
                    $content->whereRaw("FIND_IN_SET('$category_id', category_id)");
                }
                if ($language_id != 0) {
                    $content->whereRaw("FIND_IN_SET('$language_id', language_id)");
                }
                if ($channel_id != 0) {
                    $content->where('channel_id', $channel_id);
                }
                if ($order_by_upload == 2) {
                    $content->orderBy('id', 'desc');
                }
                if ($order_by_view == 2) {
                    $content->orderBy('total_view', 'desc');
                }
                if ($video_type == 1 || (in_array($video_type, [5, 6, 7]) && $sub_video_type == 1)) {

                    if ($premium_video == 1) {
                        $content->where('is_premium', 1);
                    } else if ($premium_video == 0) {
                        $content->where('is_premium', 0);
                    }
                }
                $content->take($no_of_content);
            }
            $query = $content->get();

            if ($video_type == 1) {

                $this->add_url_to_array(1, $query);
                $this->rent_price_list($query);
                for ($i = 0; $i < count($query); $i++) {

                    $query[$i]['is_buy'] = $this->is_any_package_buy($user_id);
                    $query[$i]['rent_buy'] = $this->is_rent_buy($user_id, $query[$i]['video_type'], 0, $query[$i]['id']);
                    $query[$i]['rent_expiry_date'] = $this->rent_expiry_date($user_id, $query[$i]['video_type'], 0, $query[$i]['id']);
                    $query[$i]['is_bookmark'] = $this->is_bookmark($user_id, $query[$i]['video_type'], 0, $query[$i]['id'], $user_parent_control_status);
                    $query[$i]['sub_video_type'] = 0;
                    $query[$i]['stop_time'] = $this->get_stop_time($user_id, $query[$i]['video_type'], 0, $query[$i]['id'], 0);
                    $query[$i]['category_name'] = $this->get_category_name_by_ids($query[$i]['category_id']);
                }
            } else if ($video_type == 2) {

                $this->add_url_to_array(2, $query);
                $this->rent_price_list($query);
                for ($i = 0; $i < count($query); $i++) {

                    $query[$i]['is_buy'] = $this->is_any_package_buy($user_id);
                    $query[$i]['rent_buy'] = $this->is_rent_buy($user_id, $query[$i]['video_type'], 0, $query[$i]['id']);
                    $query[$i]['rent_expiry_date'] = $this->rent_expiry_date($user_id, $query[$i]['video_type'], 0, $query[$i]['id']);
                    $query[$i]['is_bookmark'] = $this->is_bookmark($user_id, $query[$i]['video_type'], 0, $query[$i]['id'], $user_parent_control_status);
                    $query[$i]['sub_video_type'] = 0;
                    $query[$i]['stop_time'] = $this->get_stop_time($user_id, $query[$i]['video_type'], 0, $query[$i]['id'], 0);
                    $query[$i]['category_name'] = $this->get_category_name_by_ids($query[$i]['category_id']);
                }
            } else if (in_array($video_type, [5, 6, 7])) {

                if ($sub_video_type == 1) {

                    $this->add_url_to_array(1, $query);
                    $this->rent_price_list($query);
                    for ($i = 0; $i < count($query); $i++) {

                        $query[$i]['is_buy'] = $this->is_any_package_buy($user_id);
                        $query[$i]['rent_buy'] = $this->is_rent_buy($user_id, $query[$i]['video_type'], 1, $query[$i]['id']);
                        $query[$i]['rent_expiry_date'] = $this->rent_expiry_date($user_id, $query[$i]['video_type'], 1, $query[$i]['id']);
                        $query[$i]['is_bookmark'] = $this->is_bookmark($user_id, $query[$i]['video_type'], 1, $query[$i]['id'], $user_parent_control_status);
                        $query[$i]['sub_video_type'] = 1;
                        $query[$i]['stop_time'] = $this->get_stop_time($user_id, $query[$i]['video_type'], 1, $query[$i]['id'], 0);
                        $query[$i]['category_name'] = $this->get_category_name_by_ids($query[$i]['category_id']);
                    }
                } else if ($sub_video_type == 2) {

                    $this->add_url_to_array(2, $query);
                    $this->rent_price_list($query);
                    for ($i = 0; $i < count($query); $i++) {

                        $query[$i]['is_buy'] = $this->is_any_package_buy($user_id);
                        $query[$i]['rent_buy'] = $this->is_rent_buy($user_id, $query[$i]['video_type'], 2, $query[$i]['id']);
                        $query[$i]['rent_expiry_date'] = $this->rent_expiry_date($user_id, $query[$i]['video_type'], 2, $query[$i]['id']);
                        $query[$i]['is_bookmark'] = $this->is_bookmark($user_id, $query[$i]['video_type'], 2, $query[$i]['id'], $user_parent_control_status);
                        $query[$i]['sub_video_type'] = 2;
                        $query[$i]['stop_time'] = $this->get_stop_time($user_id, $query[$i]['video_type'], 2, $query[$i]['id'], 0);
                        $query[$i]['category_name'] = $this->get_category_name_by_ids($query[$i]['category_id']);
                    }
                }
            }
            return $query;
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function home_section_query_detail($video_type, $section_type, $sub_video_type, $type_id, $content_ids, $category_id, $language_id, $channel_id, $order_by_upload, $order_by_view, $premium_video)
    {
        try {

            if ($section_type == 1) {

                $contents_ids = explode(',', $content_ids);
                if ($video_type == 1 || (in_array($video_type, [5, 6, 7]) && $sub_video_type == 1)) {

                    $content = Video::whereIn('id', $contents_ids)->where('video_type', $video_type)->where('status', 1);
                } else if ($video_type == 2 || (in_array($video_type, [5, 6, 7]) && $sub_video_type == 2)) {

                    $content = TVShow::whereIn('id', $contents_ids)->where('video_type', $video_type)->where('status', 1);
                }
            } else {

                if ($video_type == 1 || (in_array($video_type, [5, 6, 7]) && $sub_video_type == 1)) {

                    $content = Video::where('video_type', $video_type)->where('status', 1);
                } else if ($video_type == 2 || (in_array($video_type, [5, 6, 7]) && $sub_video_type == 2)) {

                    $content = TVShow::where('video_type', $video_type)->where('status', 1);
                }

                if ($type_id != 0) {
                    $content->where('type_id', $type_id);
                }
                if ($category_id != 0) {
                    $content->whereRaw("FIND_IN_SET('$category_id', category_id)");
                }
                if ($language_id != 0) {
                    $content->whereRaw("FIND_IN_SET('$language_id', language_id)");
                }
                if ($channel_id != 0) {
                    $content->where('channel_id', $channel_id);
                }
                if ($order_by_upload == 2) {
                    $content->orderBy('id', 'desc');
                }
                if ($order_by_view == 2) {
                    $content->orderBy('total_view', 'desc');
                }
                if ($video_type == 1 || (in_array($video_type, [5, 6, 7]) && $sub_video_type == 1)) {

                    if ($premium_video == 1) {
                        $content->where('is_premium', 1);
                    } else if ($premium_video == 0) {
                        $content->where('is_premium', 0);
                    }
                }
            }

            $query = $content;
            return $query;
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function check_user_parent_control_status($user_id, $device_id = "")
    {
        $return = 0;

        $admin_status = Admin_Parent_Control_Status();
        $data = Device_Sync::where('user_id', $user_id)->where('device_id', $device_id)->first();
        if ($admin_status == 1 && isset($data) && $data != null) {
            $return = $data['kids_mode'];
        }
        return $return;
    }
    public function is_bookmark($user_id, $video_type, $sub_video_type, $video_id, $is_kids_profile)
    {
        $is_bookmark = Bookmark::where('user_id', $user_id)->where('is_kids_profile', $is_kids_profile)->where('video_type', $video_type)->where('sub_video_type', $sub_video_type)->where('video_id', $video_id)->where('status', 1)->first();
        if (!empty($is_bookmark)) {
            return 1;
        } else {
            return 0;
        }
    }
    public function is_like($user_id, $video_type, $sub_video_type, $video_id)
    {
        $is_like = Like::where('user_id', $user_id)->where('video_type', $video_type)->where('sub_video_type', $sub_video_type)->where('video_id', $video_id)->first();
        return $is_like != null ? 1 : 0;
    }
    public function get_total_language($language_ids)
    {
        $total_language = 0;
        if ($language_ids != "" && isset($language_ids) && $language_ids != null) {

            $Ids = explode(',', $language_ids);
            $total_language = count($Ids);
        }
        return $total_language;
    }
    public function get_category_name_by_ids($category_ids)
    {
        $Ids = explode(',', $category_ids);

        $data = Category::select('id', 'name')->whereIn('id', $Ids)->latest()->get();
        if (count($data) > 0) {

            foreach ($data as $key => $value) {
                $final_data[] = $value['name'];
            }

            $IDs = implode(", ", $final_data);
            return $IDs;
        } else {
            return "";
        }
    }
    public function get_language_name_by_ids($language_id)
    {
        $Ids = explode(',', $language_id);

        $data = Language::select('id', 'name')->whereIn('id', $Ids)->latest()->get();
        if (count($data) > 0) {

            foreach ($data as $key => $value) {
                $final_data[] = $value['name'];
            }

            $IDs = implode(", ", $final_data);
            return $IDs;
        } else {
            return "";
        }
    }
    public function get_stop_time($user_id, $video_type, $sub_video_type, $video_id, $episode_id)
    {
        $stop_time = Video_Watch::where('user_id', $user_id)->where('video_type', $video_type)->where('sub_video_type', $sub_video_type)->where('video_id', $video_id)->where('episode_id', $episode_id)->where('status', 1)->first();
        if (!empty($stop_time)) {
            return (int) $stop_time['stop_time'];
        } else {
            return 0;
        }
    }
    public function get_active_package_name($user_id)
    {
        $this->package_expiry();

        $package_name = "";
        $is_buy = Transaction::where('user_id', $user_id)->where('status', 1)->where('transaction_status', 2)->with('package')->orderBy('id', 'asc')->first();
        if (isset($is_buy) && $is_buy != null && isset($is_buy['package']) && $is_buy['package'] != null) {

            $package_name = $is_buy['package']['name'];
        }
        return $package_name;
    }
    public function get_active_package_expiry_date($user_id)
    {
        $this->package_expiry();

        $expiry_date = "";
        $is_buy = Transaction::where('user_id', $user_id)->where('status', 1)->where('transaction_status', 2)->orderBy('id', 'asc')->first();
        if (isset($is_buy) && $is_buy != null) {
            $expiry_date = $is_buy['expiry_date'];
        }
        return $expiry_date;
    }
    public function upcoming_packages($user_id)
    {

        $package_list = [];
        $transction = Transaction::where('user_id', $user_id)->where('status', 1)->where('transaction_status', 2)->orderBy('id', 'asc')->with('package')->get();
        if ($transction->count() > 1) {
            foreach ($transction->skip(1) as $value) {
                if ($value->package) {
                    $package_list[] = $value->package;
                }
            }
        }
        return $package_list;
    }
    public function rent_price_list($array)
    {
        try {

            for ($i = 0; $i < count($array); $i++) {

                $price_list = Rent_Price_List::where('id', $array[$i]['price'])->latest()->first();
                if (isset($price_list) && $price_list != null) {

                    $array[$i]['rent_price_id'] = $price_list['id'];
                    $array[$i]['price'] = $price_list['price'];
                    $array[$i]['android_product_package'] = $price_list['android_product_package'];
                    $array[$i]['ios_product_package'] = $price_list['ios_product_package'];
                    $array[$i]['web_price_id'] = $price_list['web_price_id'];
                } else {

                    $array[$i]['rent_price_id'] = 0;
                    $array[$i]['price'] = 0;
                    $array[$i]['android_product_package'] = "";
                    $array[$i]['ios_product_package'] = "";
                    $array[$i]['web_price_id'] = "";
                }
            }
            return $array;
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function getSortOrder($show_id)
    {
        $previous_ep = TVShow_Video::where('show_id', $show_id)->orderBy('sort_order', 'desc')->first();
        return $previous_ep != null ? $previous_ep['sort_order'] + 1 : 0;
    }
    public function total_comment($video_type, $sub_video_type, $video_id)
    {
        return Comment::where('comment_id', 0)->where('video_type', $video_type)->where('sub_video_type', $sub_video_type)->where('video_id', $video_id)->where('status', 1)->count();
    }
    public function generateReferenceCode(int $user_id)
    {
        do {
            $code = 'DT' . $user_id . strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6));
        } while (User::where('reference_code', $code)->exists());
        return $code;
    }
    public function applyReferral(int $child_user_id, string $reference_code)
    {
        try {
            if (Setting_Data('refer_and_earn_status') != 1) return;

            // Only apply once per child user
            if (Refer_Earn::where('child_user_id', $child_user_id)->exists()) return;

            $parent = User::where('reference_code', $reference_code)->first();
            if (!$parent) return;

            // Prevent self-referral
            if ($parent->id == $child_user_id) return;

            $parent_earn = (int) Setting_Data('parent_user_earn');
            $child_earn  = (int) Setting_Data('child_user_earn');

            User::where('id', $parent->id)->increment('wallet_amount', $parent_earn);
            User::where('id', $child_user_id)->increment('wallet_amount', $child_earn);

            Refer_Earn::create([
                'reference_code'  => $reference_code,
                'parent_user_id'  => $parent->id,
                'child_user_id'   => $child_user_id,
                'parent_earn'     => $parent_earn,
                'child_earn'      => $child_earn,
                'status'          => 1,
            ]);
        } catch (Exception $e) {
            // Silent — referral failure must never break login
        }
    }
    public function getAiCategoryIds(int $user_id, int $limit = 3)
    {
        if ($user_id <= 0) {
            return [];
        }
        return UserInterest::where('user_id', $user_id)->orderBy('watch_count', 'desc')->take($limit)->pluck('category_id')->toArray();
    }
}
