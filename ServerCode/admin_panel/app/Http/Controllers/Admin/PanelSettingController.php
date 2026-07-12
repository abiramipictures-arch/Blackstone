<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\General_Setting;
use Illuminate\Http\Request;
use Exception;

class PanelSettingController extends Controller
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

            $result = Setting_Data();
            if (isset($result)) {

                $result['panel_login_page_img'] = $this->common->getImage($this->folder, $result['panel_login_page_img'], "", 1);
                $result['panel_profile_no_img'] = $this->common->getImage($this->folder, $result['panel_profile_no_img'], "", 1);
                $result['panel_normal_no_img'] = $this->common->getImage($this->folder, $result['panel_normal_no_img'], "", 1);
                $result['panel_portrait_no_img'] = $this->common->getImage($this->folder, $result['panel_portrait_no_img'], "", 1);
                $result['panel_landscape_no_img'] = $this->common->getImage($this->folder, $result['panel_landscape_no_img'], "", 1);
                $result['powered_by_image'] = $this->common->getImage($this->folder, $result['powered_by_image'], "", 1);
                $params['result'] = $result;

                return view('admin.panel_setting.index', $params);
            }
            return redirect()->back()->with('error', __('label.page_not_found'));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function save(Request $request)
    {
        try {
            $data = $request->all();

            if (isset($data['panel_login_page_img'])) {

                $data['panel_login_page_img'] = $this->common->saveImage($data['panel_login_page_img'], $this->folder, 'panel_set_', 1);
                $this->common->deleteImageToFolder($this->folder, basename($data['old_panel_login_page_img']), 1);
            }
            if (isset($data['panel_profile_no_img'])) {

                $data['panel_profile_no_img'] = $this->common->saveImage($data['panel_profile_no_img'], $this->folder, 'panel_set_', 1);
                $this->common->deleteImageToFolder($this->folder, basename($data['old_panel_profile_no_img']), 1);
            }
            if (isset($data['panel_normal_no_img'])) {

                $data['panel_normal_no_img'] = $this->common->saveImage($data['panel_normal_no_img'], $this->folder, 'panel_set_', 1);
                $this->common->deleteImageToFolder($this->folder, basename($data['old_panel_normal_no_img']), 1);
            }
            if (isset($data['panel_portrait_no_img'])) {

                $data['panel_portrait_no_img'] = $this->common->saveImage($data['panel_portrait_no_img'], $this->folder, 'panel_set_', 1);
                $this->common->deleteImageToFolder($this->folder, basename($data['old_panel_portrait_no_img']), 1);
            }
            if (isset($data['panel_landscape_no_img'])) {

                $data['panel_landscape_no_img'] = $this->common->saveImage($data['panel_landscape_no_img'], $this->folder, 'panel_set_', 1);
                $this->common->deleteImageToFolder($this->folder, basename($data['old_panel_landscape_no_img']), 1);
            }
            if (isset($data['powered_by_image'])) {

                $data['powered_by_image'] = $this->common->saveImage($data['powered_by_image'], $this->folder, 'panel_set_', 1);
                $this->common->deleteImageToFolder($this->folder, basename($data['old_powered_by_image']), 1);
            }

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
