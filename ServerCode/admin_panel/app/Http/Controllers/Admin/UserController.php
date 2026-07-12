<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bookmark;
use App\Models\Common;
use App\Models\Device_Sync;
use App\Models\Refer_Earn;
use App\Models\Rent_Transaction;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserInterest;
use App\Models\Video_Watch;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Exception;

// Login Type : 1- OTP, 2- Google, 3- Apple, 4- Normal
class UserController extends Controller
{
    private $folder = "user";
    private $folder_avatar = "avatar";
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {

            $params['data'] = [];
            if ($request->ajax()) {

                $input_search = $request['input_search'];
                $input_type = $request['input_type'];
                $input_login_type = $request['input_login_type'];

                $query = User::query();
                if ($input_search) {
                    $query->where(function ($q) use ($input_search) {
                        $q->where('full_name', 'LIKE', "%{$input_search}%")
                            ->orWhere('user_name', 'LIKE', "%{$input_search}%")
                            ->orWhere('email', 'LIKE', "%{$input_search}%")
                            ->orWhere('mobile_number', 'LIKE', "%{$input_search}%");
                    });
                }
                if ($input_login_type !== 'all') {
                    $query->where('type', $input_login_type);
                }
                if ($input_type == 'today') {
                    $query->whereDay('created_at', date('d'))->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'));
                } elseif ($input_type == 'month') {
                    $query->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'));
                } elseif ($input_type == 'year') {
                    $query->whereYear('created_at', date('Y'));
                }
                $data = $query->orderBy('status', 'desc')->latest();

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('image', function ($row) {

                        if ($row['image_type'] == 2) {
                            return $this->common->getAvatarImage($row['image'], $this->folder_avatar);
                        }
                        return $this->common->getImage($this->folder, $row['image'], 'profile', $row['storage_type']);
                    })
                    ->addColumn('action', function ($row) {

                        return '
                        <div class="d-flex justify-content-around">
                            <a href="' . route('admin.user.dashboard', $row->id) . '" class="edit-delete-btn mr-2">
                                <i class="fa-solid fa-gauge fa-xl"></i>
                            </a>
                            <a href="' . route('admin.user.edit', $row->id) . '" class="edit-delete-btn mr-2">
                                <i class="fa-solid fa-pen-to-square fa-xl"></i>
                            </a>

                            <form class="delete-form" method="POST" action="' . route('admin.user.destroy', $row->id) . '">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="edit-delete-btn"><i class="fa-solid fa-trash-can fa-xl"></i></button>
                            </form>
                        </div>';
                    })
                    ->addColumn('status', function ($row) {
                        if ($row->status == 1) {
                            return "<div class='d-flex flex-column align-items-center' style='gap:4px;'>
                                <label id='$row->id' class='status-toggle status-on' onclick='change_status($row->id)'><span class='status-toggle-track'><span class='status-toggle-thumb'></span></span></label>
                                <small id='text_$row->id' class='font-weight-bold text-success'>" . __('label.active') . "</small>
                            </div>";
                        }
                        return "<div class='d-flex flex-column align-items-center' style='gap:4px;'>
                            <label id='$row->id' class='status-toggle status-off' onclick='change_status($row->id)'><span class='status-toggle-track'><span class='status-toggle-thumb'></span></span></label>
                            <small id='text_$row->id' class='font-weight-bold text-danger'>" . __('label.inactive') . "</small>
                        </div>";
                    })
                    ->addColumn('date', function ($row) {
                        return date("Y-m-d", strtotime($row->created_at));
                    })
                    ->rawColumns(['action', 'status', 'image'])
                    ->make(true);
            }
            return view('admin.user.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function create()
    {
        try {
            return view('admin.user.add');
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'full_name' => 'required|min:2',
                'mobile_number' => 'required|numeric|unique:tbl_user,mobile_number',
                'email' => 'required|unique:tbl_user,email|email',
                'password' => 'required|min:4',
                'image' => 'image|mimes:jpeg,png,jpg,webp',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()->all()]);
            }

            $requestData = $request->all();

            $emailArray = explode('@', $requestData['email']);
            $requestData['user_name'] = $this->common->userName($emailArray[0]);
            $requestData['password'] = Hash::make($requestData['password']);
            $requestData['storage_type'] = Storage_Type();
            $requestData['image_type'] = 1;
            if (isset($requestData['image'])) {
                $requestData['image'] = $this->common->saveImage($requestData['image'], $this->folder, 'user_', $requestData['storage_type']);
            } else {
                $requestData['image'] = "";
            }
            $requestData['type'] = 4;
            $requestData['parent_control_status'] = 0;
            $requestData['parent_control_password'] = "";
            $requestData['reference_code'] = "";
            $requestData['wallet_amount'] = 0;
            $requestData['status'] = 1;

            $data = User::updateOrCreate(['id' => $requestData['id']], $requestData);
            if ($data) {

                $data['reference_code'] = $this->common->generateReferenceCode($data->id);
                $data->save();

                return response()->json(['status' => 200, 'success' => __('label.success_add_user')]);
            }
            return response()->json(['status' => 400, 'errors' => __('label.error_add_user')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function edit($id)
    {
        try {

            $params['data'] = User::where('id', $id)->first();
            if ($params['data']) {

                if ($params['data']['image_type'] == 1) {
                    $params['data']['image'] = $this->common->getImage($this->folder, $params['data']['image'], 'profile', $params['data']['storage_type']);
                } else if ($params['data']['image_type'] == 2) {
                    $params['data']['image'] = $this->common->getAvatarImage($params['data']['image'], $this->folder_avatar);
                }

                // Demo Mode Masking
                $mode = Demo_Mode();
                if ($mode == 0) {

                    // Mask Email
                    if (!empty($params['data']['email'])) {
                        $emailParts = explode('@', $params['data']['email']);
                        $username = substr($emailParts[0], 0, 1) . '******';
                        $params['data']['email'] = $username . '@' . ($emailParts[1] ?? '');
                    }
                    // Mask Mobile
                    if (!empty($params['data']['mobile_number'])) {
                        $params['data']['mobile_number'] = '******' . substr($params['data']['mobile_number'], -4);
                    }
                }
                return view('admin.user.edit', $params);
            }
            return redirect()->back()->with('error', __('label.data_not_found'));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function update($id, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'full_name' => 'required|min:2',
                'email' => 'required|email|unique:tbl_user,email,' . $id,
                'mobile_number' => 'required|numeric|unique:tbl_user,mobile_number,' . $id,
                'image' => 'image|mimes:jpeg,png,jpg,webp',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()->all()]);
            }

            $requestData = $request->all();

            if ($request['password'] != null && isset($request['password'])) {
                $user['password'] = Hash::make($request['password']);
            } else {
                unset($requestData['password']);
            }
            if (isset($request['image'])) {

                $requestData['image_type'] = 1;
                $requestData['storage_type'] = Storage_Type();
                $requestData['image'] = $this->common->saveImage($request['image'], $this->folder, 'user_', $requestData['storage_type']);

                $this->common->deleteImageToFolder($this->folder, basename($requestData['old_image']), $request['old_storage_type']);
            }
            unset($requestData['old_image'], $requestData['old_storage_type']);

            $data = User::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($data->id)) {
                return response()->json(['status' => 200, 'success' => __('label.success_edit_user')]);
            }
            return response()->json(['status' => 400, 'errors' => __('label.error_edit_user')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function destroy($id)
    {
        try {

            $data = User::where('id', $id)->first();
            if (isset($data)) {
                $this->common->deleteImageToFolder($this->folder, $data['image'], $data['storage_type']);
                $data->delete();

                Device_Sync::where('user_id', $id)->delete();
                Bookmark::where('user_id', $id)->delete();
                Video_Watch::where('user_id', $id)->delete();
                UserInterest::where('user_id', $id)->delete();
            }
            return redirect()->route('admin.user.index')->with('success', __('label.user_delete'));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function show($id)
    {
        try {

            $data = User::where('id', $id)->first();
            if ($data) {

                $data['status'] = $data['status'] === 1 ? 0 : 1;
                $data->save();
                return response()->json(['status' => 200, 'success' => __('label.status_changed'), 'status_code' => $data['status']]);
            }
            return response()->json(['status' => 400, 'errors' => __('label.data_not_found')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function dashboard($id, Request $request)
    {
        try {

            $params['data'] = User::where('id', $id)->first();
            if ($params['data']) {

                if ($params['data']['image_type'] == 1) {
                    $params['data']['image'] = $this->common->getImage($this->folder, $params['data']['image'], 'profile', $params['data']['storage_type']);
                } else if ($params['data']['image_type'] == 2) {
                    $params['data']['image'] = $this->common->getAvatarImage($params['data']['image'], $this->folder_avatar);
                }

                $params['parent_user'] = Refer_Earn::where('child_user_id', $id)->with('parent_user')->latest()->first();
                $params['child_user'] = Refer_Earn::where('parent_user_id', $id)->count();
                $params['bookmarks_item'] = Bookmark::where('user_id', $id)->count();
                $params['video_watched'] = Video_Watch::where('user_id', $id)->count();
                $params['wallet_add_amount'] = WalletTransaction::where('user_id', $id)->sum('amount');

                $pkg_spent_amount = Transaction::where('user_id', $id)->where('payment_type', 1)->sum('price');
                $rent_spent_amount = Rent_Transaction::where('user_id', $id)->where('payment_type', 1)->sum('price');
                $params['wallet_spent_amount'] = $pkg_spent_amount + $rent_spent_amount;

                $params['active_pkg'] = Transaction::where('user_id', $id)->where('transaction_status', 2)->where('status', 1)->with('package')->orderBy('id', 'asc')->first();
                $params['previous_pkg'] = Transaction::where('user_id', $id)->where('transaction_status', 2)->where('status', 0)->with('package')->orderBy('id', 'desc')->get()->take(2);

                return view('admin.user.dashboard', $params);
            }
            return redirect()->back()->with('error', __('label.data_not_found'));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
