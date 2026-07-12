<?php

namespace App\Http\Controllers\Producer;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Producer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class ProfileController extends Controller
{
    private $folder = "producer";
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {
            $producer = Producer_Data();
            $params['data'] = Producer::where('id', $producer['id'])->first();
            if ($params['data']) {

                $params['data']['image'] = $this->common->getImage($this->folder, $params['data']['image'], 'profile', $params['data']['storage_type']);

                return view('producer.profile.index', $params);
            }
            return redirect()->route('producer.logout');
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_name' => 'required|unique:tbl_producer,user_name,' . $request->id,
                'full_name' => 'required|min:2',
                'email' => 'required|email|unique:tbl_producer,email,' . $request->id,
                'mobile_number' => 'required|numeric|unique:tbl_producer,mobile_number,' . $request->id,
                'image' => 'image|mimes:jpeg,png,jpg,webp',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()->all()]);
            }

            $requestData = $request->all();
            if (isset($request['image'])) {
                $requestData['storage_type'] = Storage_Type();
                $requestData['image'] = $this->common->saveImage($request['image'], $this->folder, 'produ_', $requestData['storage_type']);

                $this->common->deleteImageToFolder($this->folder, basename($requestData['old_image']), $requestData['old_storage_type']);
            }
            unset($requestData['old_image'], $requestData['old_storage_type']);

            $user_data = Producer::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($user_data->id)) {
                return response()->json(['status' => 200, 'success' => __('label.success_edit_profile')]);
            }
            return response()->json(['status' => 400, 'errors' => __('label.error_edit_profile')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
