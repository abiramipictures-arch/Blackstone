<?php

namespace App\Http\Controllers\Producer;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Producer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {
            $Producer = Producer_Data();

            $params['producer_id'] = $Producer['id'];

            return view('producer.change_password.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'current_password' => 'required',
                'new_password' => 'required|min:4',
                'confirm_password' => 'required|min:4|same:new_password',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()->all()]);
            }

            $producer = Producer::where('id', $request->id)->first();
            if (isset($producer) && $producer != null) {

                if (Hash::check($request['current_password'], $producer['password'])) {

                    $producer->password = Hash::make($request['new_password']);
                    if ($producer->save()) {
                        return response()->json(['status' => 200, 'success' => __('label.password_change_successfully')]);
                    }
                }
                return response()->json(['status' => 400, 'errors' => __('label.please_enter_right_current_password')]);
            }
            return redirect()->route('producer.logout');
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
