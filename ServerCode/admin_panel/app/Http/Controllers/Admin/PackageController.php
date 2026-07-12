<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Package;
use App\Models\Package_Detail;
use Illuminate\Support\Facades\Validator;
use Exception;

class PackageController extends Controller
{
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

                $query = Package::query();

                $input_search = $request['input_search'];
                if ($input_search != null) {
                    $query->where('name', 'LIKE', "%{$input_search}%");
                }
                $data = $query->orderby('status', 'desc')->orderby('price', 'asc')->latest();

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        return '<div class="d-flex justify-content-around">
                            <a href="' . route('admin.package.edit', $row->id) . '" class="edit-delete-btn mr-2">
                                <i class="fa-solid fa-pen-to-square fa-xl"></i>
                            </a>

                            <form class="delete-form" method="POST" action="' . route('admin.package.destroy', $row->id) . '">
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
                                <small id='text_$row->id' class='font-weight-bold text-success'>" . __('label.show') . "</small>
                            </div>";
                        }
                        return "<div class='d-flex flex-column align-items-center' style='gap:4px;'>
                            <label id='$row->id' class='status-toggle status-off' onclick='change_status($row->id)'><span class='status-toggle-track'><span class='status-toggle-thumb'></span></span></label>
                            <small id='text_$row->id' class='font-weight-bold text-danger'>" . __('label.hide') . "</small>
                        </div>";
                    })
                    ->rawColumns(['action', 'status'])
                    ->make(true);
            }
            return view('admin.package.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function create()
    {
        try {
            $params['data'] = [];
            return view('admin.package.add', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function store(Request $request)
    {
        try {
            $rules = [
                'name' => 'required|min:2',
                'package_type' => 'required',
                'type' => 'required',
                'time' => 'required',
                'watch_on_laptop_tv' => 'required',
                'ads_free_content' => 'required',
                'no_of_device_sync' => 'required|numeric|min:1',
            ];
            if ($request['package_type'] == 1) {
                $rules['price'] = 'required|numeric|min:1';
            }
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()->all()]);
            }

            $requestData = $request->all();
            $requestData['price'] = $request['package_type'] == 1 ? $request['price'] : 0;
            $requestData['android_product_package'] = $request['android_product_package'] ?? "";
            $requestData['ios_product_package'] = $request['ios_product_package'] ?? "";
            $requestData['web_product_package'] = $request['web_product_package'] ?? "";

            $package_data = Package::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($package_data->id)) {

                $this->package_detail($package_data->id);
                return response()->json(['status' => 200, 'success' => __('label.success_add_package')]);
            }
            return response()->json(['status' => 400, 'errors' => __('label.error_add_package')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function edit($id)
    {
        try {
            $params['data'] = Package::where('id', $id)->first();
            if ($params['data']) {
                return view('admin.package.edit', $params);
            }
            return redirect()->back()->with('error', __('label.data_not_found'));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function update(Request $request)
    {
        try {
            $rules = [
                'package_type' => 'required',
                'name' => 'required|min:2',
                'type' => 'required',
                'time' => 'required',
                'watch_on_laptop_tv' => 'required',
                'ads_free_content' => 'required',
                'no_of_device_sync' => 'required|numeric|min:1',
            ];
            if ($request['package_type'] == 1) {
                $rules['price'] = 'required|numeric|min:1';
            }
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()->all()]);
            }

            $requestData = $request->all();
            $requestData['price'] = $request['package_type'] == 1 ? $request['price'] : 0;
            $requestData['android_product_package'] = $request['android_product_package'] ?? "";
            $requestData['ios_product_package'] = $request['ios_product_package'] ?? "";
            $requestData['web_product_package'] = $request['web_product_package'] ?? "";

            $package_data = Package::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($package_data->id)) {

                $this->package_detail($package_data->id);
                return response()->json(['status' => 200, 'success' => __('label.success_edit_package')]);
            }
            return response()->json(['status' => 400, 'errors' => __('label.error_edit_package')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function destroy($id)
    {
        try {

            $data = Package::where('id', $id)->first();
            if (isset($data)) {
                $data->delete();
                Package_Detail::where('package_id', $data->id)->delete();
            }
            return redirect()->route('admin.package.index')->with('success', __('label.package_delete'));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function show($id)
    {
        try {

            $data = Package::where('id', $id)->first();
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
    public function package_detail($Pid)
    {
        Package_Detail::where('package_id', $Pid)->delete();

        $Pdata = Package::where('id', $Pid)->first();

        $watch = "Use only on Mobile";
        $ads = "Ads On All Content";
        $devic_sync = "Watch on " . $Pdata['no_of_device_sync'] . " device";
        if ($Pdata['watch_on_laptop_tv'] == 1) {
            $watch = "Watch on Mobile & TV";
        }
        if ($Pdata['ads_free_content'] == 1) {
            $ads = "Ads Free All Content";
        }

        Package_Detail::insert([
            ['package_id' => $Pdata['id'], 'package_key' => $devic_sync, 'package_value' => $Pdata['no_of_device_sync']],
            ['package_id' => $Pdata['id'], 'package_key' => $watch, 'package_value' => $Pdata['watch_on_laptop_tv']],
            ['package_id' => $Pdata['id'], 'package_key' => $ads, 'package_value' => $Pdata['ads_free_content']],
        ]);
        return true;
    }
}
