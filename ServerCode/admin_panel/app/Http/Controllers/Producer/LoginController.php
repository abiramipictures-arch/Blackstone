<?php

namespace App\Http\Controllers\Producer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class LoginController extends Controller
{
    protected $redirectTo = 'producer/login';
    public function __construct()
    {
        try {
            $this->middleware('guest:producer', ['except' => 'logout']);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }

    public function login(Request $request)
    {
        try {
            return view('producer.login.login');
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function save_login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|min:4',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()->all()]);
            }

            $requestData = $request->all();
            if (Auth()->guard('producer')->attempt(['email' => $requestData['email'], 'password' => $requestData['password']])) {
                return response()->json(['status' => 200, 'success' => __('label.success_login')]);
            }
            return response()->json(['status' => 400, 'errors' => __('label.error_login')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function logout()
    {
        try {
            Auth()->guard('producer')->logout();
            return redirect()->route('producer.login')->with('success', __('label.logout_successfully'));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
