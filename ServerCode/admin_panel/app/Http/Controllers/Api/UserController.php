<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TV_Login;
use App\Models\Common;
use App\Models\Device_Sync;
use App\Models\Device_Watching;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Exception;

// Login Type : 1- OTP, 2- Goggle, 3- Apple, 4- Normal
class UserController extends Controller
{
    private $folder_user = "user";
    private $folder_avatar = "avatar";
    public $common;
    public $page_limit;
    public function __construct()
    {
        $this->common = new Common();
        $this->page_limit = env('PAGE_LIMIT');
    }

    public function register(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'full_name' => 'required|min:2',
                'email' => 'required|unique:tbl_user|email',
                'password' => 'required|min:4',
                'mobile_number' => 'required|numeric|unique:tbl_user,mobile_number',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $full_name = $request->full_name;
            $email = $request->email;
            $password = Hash::make($request->password);
            $mobile_number = $request->mobile_number;
            $device_type = (int)$request['device_type'] ?? 0;
            $device_token = $request['device_token'] ?? '';
            $device_name = $request['device_name'] ?? '';
            $device_id = $request['device_id'] ?? '';
            $storage_type = Storage_Type();

            $user_name = explode('@', $email);
            $insert = array(
                'user_name' => $this->common->userName($user_name[0]),
                'full_name' => $full_name,
                'email' => $email,
                'password' => $password,
                'mobile_number' => $mobile_number,
                'storage_type' => $storage_type,
                'image_type' => 1,
                'image' => "",
                'type' => 4,
                'parent_control_status' => 0,
                'parent_control_password' => "",
                'reference_code' => "",
                'wallet_amount' => 0,
                'status' => 1,
            );
            $user_id = User::insertGetId($insert);

            if (isset($user_id)) {

                // Generate unique referral code for new user
                User::where('id', $user_id)->update(['reference_code' => $this->common->generateReferenceCode($user_id)]);

                $user = User::where('id', $user_id)->first();
                if (isset($user)) {

                    // Device Sync
                    $insert_sync = array(
                        'user_id' => $user_id,
                        'device_name' => $device_name,
                        'device_id' => $device_id,
                        'device_type' => $device_type,
                        'device_token' => $device_token,
                        'kids_mode' => 0,
                        'status' => 1,
                    );
                    Device_Sync::insertGetId($insert_sync);

                    if ($user['image_type'] == 1) {
                        $user['image'] = $this->common->getImage($this->folder_user, $user['image'], 'profile', $user['storage_type']);
                    } else if ($user['image_type'] == 2) {
                        $user['image'] = $this->common->getAvatarImage($user['image'], $this->folder_user);
                    }

                    $user['is_buy'] = $this->common->is_any_package_buy($user['id']);
                    $user['device_id'] = $device_id;
                    $user['device_type'] = $device_type;
                    $user['device_token'] = $device_token;
                    return $this->common->API_Response(200, __('api_msg.login_successfully'), array($user));
                } else {
                    return $this->common->API_Response(400, __('api_msg.data_not_found'));
                }
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_save'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function login(Request $request)
    {
        try {
            if ($request->type == 1) {
                $validation = Validator::make($request->all(), [
                    'mobile_number' => 'required|numeric',
                ]);
            } elseif ($request->type == 2 || $request->type == 3) {
                $validation = Validator::make($request->all(), [
                    'email' => 'required',
                ]);
            } elseif ($request->type == 4) {
                $validation = Validator::make($request->all(), [
                    'email' => 'required|email',
                    'password' => 'required|min:4',
                ]);
            } else {
                $validation = Validator::make($request->all(), [
                    'type' => 'required|numeric',
                ]);
            }
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $type = $request->type;
            $full_name = $request['full_name'] ?? '';
            $email = $request['email'] ?? '';
            $password = isset($request->password) ? Hash::make($request->password) : '';
            $mobile_number = $request['mobile_number'] ?? '';
            $device_type = (int)$request['device_type'] ?? 0;
            $device_token = $request['device_token'] ?? '';
            $device_name = $request['device_name'] ?? '';
            $device_id = $request['device_id'] ?? '';
            $image_type = $request['image_type'] ?? 1;
            $storage_type = Storage_Type();
            $reference_code = $request['reference_code'] ?? '';
            $image = '';
            if ($image_type == 1 && isset($request['image']) && $request['image'] != null) {

                $file = $request->file('image');
                $image = $this->common->saveImage($file, $this->folder_user, 'user_', $storage_type);
            }

            // OTP
            if ($type == 1) {

                $user = User::where('mobile_number', $mobile_number)->where('type', $type)->latest()->first();
                if (isset($user) && $user != null) {

                    $package_buy = $this->common->is_any_package_buy($user['id']);
                    if ($package_buy == 1) {

                        $package = Transaction::where('user_id', $user['id'])->where('status', 1)->where('transaction_status', 2)->with('package')->orderBy('id', 'asc')->first();
                        if ($package['package'] != null) {

                            $no_device_sync = $package['package']['no_of_device_sync'];
                            $device_synce_list = Device_Sync::where('user_id', $user['id'])->latest()->get();

                            if ($no_device_sync > count($device_synce_list)) {

                                $device_exists = false;
                                for ($i = 0; $i < count($device_synce_list); $i++) {
                                    if ($device_id == $device_synce_list[$i]['device_id']) {
                                        $device_exists = true;
                                        break;
                                    }
                                }

                                // If the device_id does not exist, add it to the sync list
                                if (!$device_exists) {
                                    $add = new Device_Sync();
                                    $add['user_id'] = $user['id'];
                                    $add['device_name'] = $device_name;
                                    $add['device_id'] = $device_id;
                                    $add['device_type'] = $device_type;
                                    $add['device_token'] = $device_token;
                                    $add['kids_mode'] = 0;
                                    $add['status'] = 1;
                                    $add->save();
                                }

                                if ($user['image_type'] == 1) {
                                    $user['image'] = $this->common->getImage($this->folder_user, $user['image'], 'profile', $user['storage_type']);
                                } else if ($user['image_type'] == 2) {
                                    $user['image'] = $this->common->getAvatarImage($user['image'], $this->folder_avatar);
                                }
                                $user['is_buy'] = $this->common->is_any_package_buy($user['id']);
                                $user['device_id'] = $device_id;
                                $user['device_type'] = $device_type;
                                $user['device_token'] = $device_token;

                                if (!empty($reference_code)) {
                                    $this->common->applyReferral($user['id'], $reference_code);
                                }
                                return $this->common->API_Response(200, __('api_msg.login_successfully'), array($user));
                            } else {
                                return $this->common->API_Response(400, __('api_msg.your_device_sync_limit_is_over'));
                            }
                        } else {
                            return $this->common->API_Response(400, __('api_msg.something_is_wrong'));
                        }
                    } else {

                        $device_synce_check = Device_Sync::where('user_id', $user['id'])->where('device_id', $device_id)->first();
                        if (!$device_synce_check) {

                            // Device Sync if not found
                            $add = new Device_Sync();
                            $add['user_id'] = $user['id'];
                            $add['device_name'] = $device_name;
                            $add['device_id'] = $device_id;
                            $add['device_type'] = $device_type;
                            $add['device_token'] = $device_token;
                            $add['kids_mode'] = 0;
                            $add['status'] = 1;
                            $add->save();

                            $device_synce_check = $add;
                        }

                        if ($user['image_type'] == 1) {
                            $user['image'] = $this->common->getImage($this->folder_user, $user['image'], 'profile', $user['storage_type']);
                        } else if ($user['image_type'] == 2) {
                            $user['image'] = $this->common->getAvatarImage($user['image'], $this->folder_avatar);
                        }
                        $user['is_buy'] = $this->common->is_any_package_buy($user['id']);
                        $user['device_id'] = $device_synce_check['device_id'];
                        $user['device_type'] = (int)$device_synce_check['device_type'];
                        $user['device_token'] = $device_synce_check['device_token'];

                        if (!empty($reference_code)) {
                            $this->common->applyReferral($user['id'], $reference_code);
                        }
                        return $this->common->API_Response(200, __('api_msg.login_successfully'), array($user));
                    }
                } else {

                    $insert = array(
                        'user_name' => $this->common->userName($mobile_number),
                        'full_name' => $full_name,
                        'email' => $email,
                        'password' => $password,
                        'mobile_number' => $mobile_number,
                        'storage_type' => $storage_type,
                        'image_type' => $image_type,
                        'image' => $image,
                        'type' => $type,
                        'parent_control_status' => 0,
                        'parent_control_password' => "",
                        'reference_code' => "",
                        'wallet_amount' => 0,
                        'status' => 1,
                    );
                    $user_id = User::insertGetId($insert);

                    if (isset($user_id)) {

                        // Generate unique referral code for new user
                        User::where('id', $user_id)->update(['reference_code' => $this->common->generateReferenceCode($user_id)]);

                        $user = User::where('id', $user_id)->first();
                        if (isset($user)) {

                            if ($user['image_type'] == 1) {
                                $user['image'] = $this->common->getImage($this->folder_user, $user['image'], 'profile', $user['storage_type']);
                            } else if ($user['image_type'] == 2) {
                                $user['image'] = $this->common->getAvatarImage($user['image'], $this->folder_avatar);
                            }
                            $user['is_buy'] = $this->common->is_any_package_buy($user['id']);

                            // Device Sync
                            $add = new Device_Sync();
                            $add['user_id'] = $user['id'];
                            $add['device_name'] = $device_name;
                            $add['device_id'] = $device_id;
                            $add['device_type'] = $device_type;
                            $add['device_token'] = $device_token;
                            $add['kids_mode'] = 0;
                            $add['status'] = 1;
                            $add->save();

                            $user['device_id'] = $device_id;
                            $user['device_type'] = $device_type;
                            $user['device_token'] = $device_token;

                            if (!empty($reference_code)) {
                                $this->common->applyReferral($user['id'], $reference_code);
                            }
                            return $this->common->API_Response(200, __('api_msg.login_successfully'), array($user));
                        } else {
                            return $this->common->API_Response(400, __('api_msg.data_not_found'));
                        }
                    } else {
                        return $this->common->API_Response(400, __('api_msg.data_not_save'));
                    }
                }
            }

            // Google || Apple
            if ($type == 2 || $type == 3) {

                $user = User::where('email', $email)->where('type', $type)->latest()->first();
                if (isset($user) && $user != null) {

                    $package_buy = $this->common->is_any_package_buy($user['id']);
                    if ($package_buy == 1) {

                        $package = Transaction::where('user_id', $user['id'])->where('status', 1)->where('transaction_status', 2)->with('package')->orderBy('id', 'asc')->first();
                        if ($package['package'] != null) {

                            $no_device_sync = $package['package']['no_of_device_sync'];
                            $device_synce_list = Device_Sync::where('user_id', $user['id'])->latest()->get();

                            if ($no_device_sync > count($device_synce_list)) {

                                $device_exists = false;
                                for ($i = 0; $i < count($device_synce_list); $i++) {
                                    if ($device_id == $device_synce_list[$i]['device_id']) {
                                        $device_exists = true;
                                        break;
                                    }
                                }

                                // If the device_id does not exist, add it to the sync list
                                if (!$device_exists) {
                                    // Device Sync
                                    $add = new Device_Sync();
                                    $add['user_id'] = $user['id'];
                                    $add['device_name'] = $device_name;
                                    $add['device_id'] = $device_id;
                                    $add['device_type'] = $device_type;
                                    $add['device_token'] = $device_token;
                                    $add['kids_mode'] = 0;
                                    $add['status'] = 1;
                                    $add->save();
                                }

                                if ($user['image_type'] == 1) {
                                    $user['image'] = $this->common->getImage($this->folder_user, $user['image'], 'profile', $user['storage_type']);
                                } else if ($user['image_type'] == 2) {
                                    $user['image'] = $this->common->getAvatarImage($user['image'], $this->folder_avatar);
                                }
                                $user['is_buy'] = $this->common->is_any_package_buy($user['id']);
                                $user['device_id'] = $device_id;
                                $user['device_type'] = $device_type;
                                $user['device_token'] = $device_token;

                                if (!empty($reference_code)) {
                                    $this->common->applyReferral($user['id'], $reference_code);
                                }
                                return $this->common->API_Response(200, __('api_msg.login_successfully'), array($user));
                            } else {
                                return $this->common->API_Response(400, __('api_msg.your_device_sync_limit_is_over'));
                            }
                        } else {
                            return $this->common->API_Response(400, __('api_msg.something_is_wrong'));
                        }
                    } else {

                        $device_synce_check = Device_Sync::where('user_id', $user['id'])->where('device_id', $device_id)->first();
                        if (!$device_synce_check) {

                            // Device Sync if not found
                            $add = new Device_Sync();
                            $add['user_id'] = $user['id'];
                            $add['device_name'] = $device_name;
                            $add['device_id'] = $device_id;
                            $add['device_type'] = $device_type;
                            $add['device_token'] = $device_token;
                            $add['kids_mode'] = 0;
                            $add['status'] = 1;
                            $add->save();

                            $device_synce_check = $add;
                        }

                        if ($user['image_type'] == 1) {
                            $user['image'] = $this->common->getImage($this->folder_user, $user['image'], 'profile', $user['storage_type']);
                        } else if ($user['image_type'] == 2) {
                            $user['image'] = $this->common->getAvatarImage($user['image'], $this->folder_avatar);
                        }
                        $user['is_buy'] = $this->common->is_any_package_buy($user['id']);
                        $user['device_id'] = $device_synce_check['device_id'];
                        $user['device_type'] = (int)$device_synce_check['device_type'];
                        $user['device_token'] = $device_synce_check['device_token'];

                        if (!empty($reference_code)) {
                            $this->common->applyReferral($user['id'], $reference_code);
                        }
                        return $this->common->API_Response(200, __('api_msg.login_successfully'), array($user));
                    }
                } else {

                    $user_name = explode('@', $email);
                    $insert = array(
                        'user_name' => $this->common->userName($user_name[0]),
                        'full_name' => $full_name,
                        'email' => $email,
                        'password' => $password,
                        'mobile_number' => $mobile_number,
                        'storage_type' => $storage_type,
                        'image_type' => $image_type,
                        'image' => $image,
                        'type' => $type,
                        'parent_control_status' => 0,
                        'parent_control_password' => "",
                        'reference_code' => "",
                        'wallet_amount' => 0,
                        'status' => 1,
                    );
                    $user_id = User::insertGetId($insert);

                    if (isset($user_id)) {

                        // Generate unique referral code for new user
                        User::where('id', $user_id)->update(['reference_code' => $this->common->generateReferenceCode($user_id)]);

                        $user = User::where('id', $user_id)->first();
                        if (isset($user)) {

                            if ($user['image_type'] == 1) {
                                $user['image'] = $this->common->getImage($this->folder_user, $user['image'], 'profile', $user['storage_type']);
                            } else if ($user['image_type'] == 2) {
                                $user['image'] = $this->common->getAvatarImage($user['image'], $this->folder_avatar);
                            }
                            $user['is_buy'] = $this->common->is_any_package_buy($user['id']);

                            // Send Mail
                            $check = $this->common->NotificationConfiguration('login');
                            if (isset($check) && $check['status'] == 1 && $check['send_mail'] == 1) {
                                if ($type == 2) {
                                    $this->common->Send_Mail(1, $user->email, "");
                                }
                            }

                            // Device Sync
                            $add = new Device_Sync();
                            $add['user_id'] = $user['id'];
                            $add['device_name'] = $device_name;
                            $add['device_id'] = $device_id;
                            $add['device_type'] = $device_type;
                            $add['device_token'] = $device_token;
                            $add['kids_mode'] = 0;
                            $add['status'] = 1;
                            $add->save();

                            $user['device_id'] = $device_id;
                            $user['device_type'] = $device_type;
                            $user['device_token'] = $device_token;

                            if (!empty($reference_code)) {
                                $this->common->applyReferral($user['id'], $reference_code);
                            }
                            return $this->common->API_Response(200, __('api_msg.login_successfully'), array($user));
                        } else {
                            return $this->common->API_Response(400, __('api_msg.data_not_found'));
                        }
                    } else {
                        return $this->common->API_Response(400, __('api_msg.data_not_save'));
                    }
                }
            }

            // Normal
            if ($type == 4) {

                $user = User::where('email', $email)->where('type', $type)->latest()->first();
                if (isset($user)) {

                    if (Hash::check($request->password, $user->password)) {

                        $package_buy = $this->common->is_any_package_buy($user['id']);
                        if ($package_buy == 1) {

                            $package = Transaction::where('user_id', $user['id'])->where('status', 1)->where('transaction_status', 2)->with('package')->orderBy('id', 'asc')->first();
                            if ($package['package'] != null) {

                                $no_device_sync = $package['package']['no_of_device_sync'];
                                $device_synce_list = Device_Sync::where('user_id', $user['id'])->latest()->get();

                                if ($no_device_sync > count($device_synce_list)) {

                                    $device_exists = false;
                                    for ($i = 0; $i < count($device_synce_list); $i++) {
                                        if ($device_id == $device_synce_list[$i]['device_id']) {
                                            $device_exists = true;
                                            break;
                                        }
                                    }

                                    // If the device_id does not exist, add it to the sync list
                                    if (!$device_exists) {
                                        // Device Sync
                                        $add = new Device_Sync();
                                        $add['user_id'] = $user['id'];
                                        $add['device_name'] = $device_name;
                                        $add['device_id'] = $device_id;
                                        $add['device_type'] = $device_type;
                                        $add['device_token'] = $device_token;
                                        $add['kids_mode'] = 0;
                                        $add['status'] = 1;
                                        $add->save();
                                    }

                                    if ($user['image_type'] == 1) {
                                        $user['image'] = $this->common->getImage($this->folder_user, $user['image'], 'profile', $user['storage_type']);
                                    } else if ($user['image_type'] == 2) {
                                        $user['image'] = $this->common->getAvatarImage($user['image'], $this->folder_avatar);
                                    }
                                    $user['is_buy'] = $this->common->is_any_package_buy($user['id']);
                                    $user['device_id'] = $device_id;
                                    $user['device_type'] = $device_type;
                                    $user['device_token'] = $device_token;

                                    if (!empty($reference_code)) {
                                        $this->common->applyReferral($user['id'], $reference_code);
                                    }
                                    return $this->common->API_Response(200, __('api_msg.login_successfully'), array($user));
                                } else {
                                    return $this->common->API_Response(400, __('api_msg.your_device_sync_limit_is_over'));
                                }
                            } else {
                                return $this->common->API_Response(400, __('api_msg.something_is_wrong'));
                            }
                        } else {

                            $device_synce_check = Device_Sync::where('user_id', $user['id'])->where('device_id', $device_id)->first();
                            if (!$device_synce_check) {
                                // Device Sync if not found
                                $add = new Device_Sync();
                                $add['user_id'] = $user['id'];
                                $add['device_name'] = $device_name;
                                $add['device_id'] = $device_id;
                                $add['device_type'] = $device_type;
                                $add['device_token'] = $device_token;
                                $add['kids_mode'] = 0;
                                $add['status'] = 1;
                                $add->save();

                                $device_synce_check = $add;
                            }

                            if ($user['image_type'] == 1) {
                                $user['image'] = $this->common->getImage($this->folder_user, $user['image'], 'profile', $user['storage_type']);
                            } else if ($user['image_type'] == 2) {
                                $user['image'] = $this->common->getAvatarImage($user['image'], $this->folder_avatar);
                            }
                            $user['is_buy'] = $this->common->is_any_package_buy($user['id']);
                            $user['device_id'] = $device_synce_check['device_id'];
                            $user['device_type'] = (int)$device_synce_check['device_type'];
                            $user['device_token'] = $device_synce_check['device_token'];

                            if (!empty($reference_code)) {
                                $this->common->applyReferral($user['id'], $reference_code);
                            }
                            return $this->common->API_Response(200, __('api_msg.login_successfully'), array($user));
                        }
                    } else {
                        return $this->common->API_Response(400, __('api_msg.email_pass_worng'));
                    }
                } else {
                    return $this->common->API_Response(400, __('api_msg.email_pass_worng'));
                }
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function get_profile(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'user_id' => 'required|numeric',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $user_id = $request['user_id'];

            $user_data = User::where('id', $user_id)->first();
            if (isset($user_data) && $user_data != null) {

                if ($user_data['image_type'] == 1) {
                    $user_data['image'] = $this->common->getImage($this->folder_user, $user_data['image'], 'profile', $user_data['storage_type']);
                } else if ($user_data['image_type'] == 2) {
                    $user_data['image'] = $this->common->getAvatarImage($user_data['image'], $this->folder_avatar);
                }
                $user_data['is_buy'] = $this->common->is_any_package_buy($user_data['id']);
                $user_data['package_name'] = $this->common->get_active_package_name($user_data['id']);
                $user_data['expiry_date'] = $this->common->get_active_package_expiry_date($user_data['id']);
                $user_data['upcoming_package'] = $this->common->upcoming_packages($user_data['id']);

                return $this->common->API_Response(200, __('api_msg.data_retrieved'), array($user_data));
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function update_profile(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'user_id' => 'required|numeric',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $user_id = $request['user_id'];
            $data = User::where('id', $user_id)->first();

            $array = array();
            if (isset($data) && $data != null) {

                if (isset($request['user_name']) && $request['user_name'] != '') {

                    $check = User::where('user_name', $request['user_name'])->first();
                    if (isset($check) && $check != null) {
                        if ($check->id == $data->id) {
                            $array['user_name'] = $request['user_name'];
                        } else {
                            return $this->common->API_Response(400, __('api_msg.user_name_exists'));
                        }
                    } else {
                        $array['user_name'] = $request['user_name'];
                    }
                }
                if (isset($request['full_name']) && $request['full_name'] != '') {
                    $array['full_name'] = $request->full_name;
                }
                if (isset($request['email']) && $request['email'] != '') {

                    $check = User::where('email', $request['email'])->first();
                    if (isset($check) && $check != null) {
                        if ($check->id == $data->id) {
                            $array['email'] = $request['email'];
                        } else {
                            return $this->common->API_Response(400, __('api_msg.email_exists'));
                        }
                    } else {
                        $array['email'] = $request['email'];
                    }
                }
                if (isset($request['mobile_number']) && $request['mobile_number'] != '') {

                    $check = User::where('mobile_number', $request['mobile_number'])->first();
                    if (isset($check) && $check != null) {
                        if ($check->id == $data->id) {
                            $array['mobile_number'] = $request['mobile_number'];
                        } else {
                            return $this->common->API_Response(400, __('api_msg.mobile_number_exists'));
                        }
                    } else {
                        $array['mobile_number'] = $request['mobile_number'];
                    }
                }
                if (isset($request['image_type']) && $request['image_type'] != '' && $request['image_type'] != 0) {

                    $array['image_type'] = $request['image_type'];
                    if ($request['image_type'] == 1 && isset($request['image']) && $request->file('image') != '') {

                        $array['storage_type'] = Storage_Type();
                        $image = $request->file('image');
                        $array['image'] = $this->common->saveImage($image, $this->folder_user, 'user_', $array['storage_type']);
                    } else if ($request['image_type'] == 2 && isset($request['image'])) {

                        $array['image'] = $request['image'];
                    }

                    $old_image = $data['image'];
                    $this->common->deleteImageToFolder($this->folder_user, $old_image, $data['storage_type']);
                }
                if (isset($request['parent_control_status']) && $request['parent_control_status'] != '') {
                    $array['parent_control_status'] = $request['parent_control_status'];
                }
                if (isset($request['parent_control_password']) && $request['parent_control_password'] != '') {
                    $array['parent_control_password'] = $request['parent_control_password'];
                }

                User::where('id', $user_id)->update($array);

                $user = User::where('id', $user_id)->first();
                if ($user['image_type'] == 1) {
                    $user['image'] = $this->common->getImage($this->folder_user, $user['image'], 'profile', $user['storage_type']);
                } else if ($user['image_type'] == 2) {
                    $user['image'] = $this->common->getAvatarImage($user['image'], $this->folder_avatar);
                }
                $user['is_buy'] = $this->common->is_any_package_buy($user['id']);

                return $this->common->API_Response(200, __('api_msg.profile_update_successfully'), array($user));
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }

    // TV Login
    public function get_tv_login_code(Request $request)
    {
        try {

            $insert = new TV_Login();
            $insert['unique_code'] = $this->common->tv_login_code();
            $insert['user_id'] = 0;
            $insert['status'] = 0;

            if ($insert->save()) {
                return $this->common->API_Response(200, __('api_msg.data_retrieved'), array($insert));
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_save'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function tv_login(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'user_id' => 'required|numeric',
                'unique_code' => 'required',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $check = TV_Login::where('unique_code', $request->unique_code)->where('status', 0)->where('user_id', 0)->first();
            if (isset($check)) {

                $check->status = 1;
                $check->user_id = $request->user_id;
                if ($check->update()) {

                    $data = User::where('id', $check->user_id)->first();
                    if (isset($data)) {

                        $this->common->imageNameToUrl(array($data), 'image', $this->folder_user, 'profile');
                        return $this->common->API_Response(200, __('api_msg.data_retrieved'), array($data));
                    } else {
                        return $this->common->API_Response(400, __('api_msg.user_id_worng'));
                    }
                } else {
                    return $this->common->API_Response(400, __('api_msg.data_not_save'));
                }
            } else {
                return $this->common->API_Response(400, __('api_msg.code_is_wrong'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function check_tv_login(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'unique_code' => 'required',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $data = TV_Login::where('unique_code', $request->unique_code)->where('status', '!=', 0)->where('user_id', '!=', 0)->first();
            if (isset($data)) {
                return $this->common->API_Response(200, __('api_msg.data_retrieved'), array($data));
            } else {
                return $this->common->API_Response(400, __('api_msg.code_is_wrong'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }

    // Parent Control
    public function parent_control_check_password(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'user_id' => 'required',
                'password' => 'required',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $user_id = $request['user_id'];
            $password = $request['password'];

            $check = User::where('id', $user_id)->where('parent_control_password', $password)->first();
            if (isset($check) && $check != null) {
                return $this->common->API_Response(200, __('api_msg.password_is_correct'));
            } else {
                return $this->common->API_Response(400, __('api_msg.password_worng'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }

    // Device Sync
    public function get_device_sync_list(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'user_id' => 'required',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $user_id = $request['user_id'];

            $check = Device_Sync::where('user_id', $user_id)->orderBy('id', 'desc')->get();
            if (count($check) > 0) {
                return $this->common->API_Response(200, __('api_msg.data_retrieved'), $check);
            } else {
                return $this->common->API_Response(400, __('api_msg.data_not_found'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function logout_device_sync(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'device_sync_id' => 'required',
                'device_id' => 'required',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $device_sync_id = $request['device_sync_id'];
            $device_id = $request['device_id'];

            Device_Sync::where('id', $device_sync_id)->where('device_id', $device_id)->where('status', 1)->delete();
            return $this->common->API_Response(200, __('api_msg.delete_success'));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function add_remove_device_watching(Request $request) // Type = 1- Add, 2- Remove
    {
        try {
            $validation = Validator::make($request->all(), [
                'user_id' => 'required',
                'device_id' => 'required',
                'type' => 'required',
            ]);
            if ($validation->fails()) {
                return $this->common->API_Response(400, $validation->errors()->first());
            }

            $user_id = $request['user_id'];
            $device_id = $request['device_id'];
            $type = $request['type'];

            if ($type == 1) {

                $package_buy = $this->common->is_any_package_buy($user_id);
                if ($package_buy == 1) {

                    $package = Transaction::where('user_id', $user_id)->where('status', 1)->where('transaction_status', 2)->with('package')->orderBy('id', 'asc')->first();
                    if ($package && $package['package'] != null) {

                        $no_device_sync = $package['package']['no_of_device_sync'];
                        $device_watching_list = Device_Watching::where('user_id', $user_id)->with('device_sync')->latest()->get();
                        $data = Device_Watching::where('user_id', $user_id)->where('device_id', $device_id)->first();

                        if ($data || $no_device_sync > count($device_watching_list)) {

                            if (!$data) {
                                $add = new Device_Watching();
                                $add['user_id'] = $user_id;
                                $add['device_id'] = $device_id;
                                $add['status'] = 1;
                                $add->save();
                            }
                            return $this->common->API_Response(200, __('api_msg.device_add_successfully'));
                        } else {

                            $return_data = [];
                            for ($i = 0; $i < count($device_watching_list); $i++) {

                                if ($device_watching_list[$i]['device_sync'] != null) {
                                    $return_data[] = $device_watching_list[$i]['device_sync'];
                                }
                            }

                            $return['status'] = 400;
                            $return['message'] = __('api_msg.streaming_limit_reached');
                            $return['result'] = $return_data;
                            return $return;
                        }
                    } else {
                        return $this->common->API_Response(400, __('api_msg.please_get_subscription'));
                    }
                } else {
                    return $this->common->API_Response(400, __('api_msg.please_get_subscription'));
                }
            } elseif ($type == 2) {

                $data = Device_Watching::where('user_id', $user_id)->where('device_id', $device_id)->delete();
                return $this->common->API_Response(200, __('api_msg.device_delete_successfully'));
            } else {
                return $this->common->API_Response(400, __('api_msg.type_is_wrong'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
