<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Coupon;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {
            $this->common->coupon_expiry();

            $params['packages'] = Package::select('id', 'name')->where('status', 1)->get();

            if ($request->ajax()) {
                $query = Coupon::query();

                $input_search = $request['input_search'];
                if ($input_search != null) {
                    $query->where(function ($q) use ($input_search) {
                        $q->where('title', 'LIKE', "%{$input_search}%")->orWhere('code', 'LIKE', "%{$input_search}%");
                    });
                }
                $data = $query->orderby('status', 'desc')->latest();

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $btn  = '<div class="d-flex justify-content-around">';
                        $btn .= '<a class="edit-delete-btn mr-2 edit_coupon" data-toggle="modal" href="#EditModel"'
                            . ' data-id="' . $row->id . '"'
                            . ' data-title="' . e($row->title) . '"'
                            . ' data-code="' . e($row->code) . '"'
                            . ' data-description="' . e($row->description ?? '') . '"'
                            . ' data-start_date="' . ($row->start_date ? $row->start_date->format('Y-m-d') : '') . '"'
                            . ' data-end_date="' . ($row->end_date ? $row->end_date->format('Y-m-d') : '') . '"'
                            . ' data-discount_type="' . ($row->discount_type ?? 1) . '"'
                            . ' data-discount_value="' . ($row->discount_value ?? '') . '"'
                            . ' data-applicable_for="' . ($row->applicable_for ?? 0) . '"'
                            . ' data-package_id="' . ($row->package_id ?? '') . '"'
                            . ' data-usage_limit="' . ($row->usage_limit ?? 0) . '"'
                            . ' data-usage_per_user="' . ($row->usage_per_user ?? 0) . '"'
                            . ' data-is_single_use="' . ($row->is_single_use ?? 0) . '"'
                            . ' data-used_count="' . ($row->used_count ?? 0) . '">'
                            . '<i class="fa-solid fa-pen-to-square fa-xl"></i></a>';
                        $btn .= '<form class="delete-form" method="POST" action="' . route('admin.coupon.destroy', [$row->id]) . '">'
                            . '<input type="hidden" name="_token" value="' . csrf_token() . '">'
                            . '<input type="hidden" name="_method" value="DELETE">'
                            . '<button type="submit" class="edit-delete-btn"><i class="fa-solid fa-trash-can fa-xl"></i></button>'
                            . '</form>';
                        $btn .= '</div>';
                        return $btn;
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
                    ->addColumn('start_date', function ($row) {
                        return date("d-m-Y", strtotime($row->start_date));
                    })
                    ->addColumn('end_date', function ($row) {
                        return date("d-m-Y", strtotime($row->end_date));
                    })
                    ->rawColumns(['action', 'status'])
                    ->make(true);
            }
            return view('admin.coupon.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function store(Request $request)
    {
        try {
            $rules = [
                'title'          => 'required|min:2',
                'start_date'     => 'required',
                'end_date'       => 'required|date|after_or_equal:start_date',
                'applicable_for' => 'required|in:0,1,2',
            ];
            if ($request['discount_type'] == 1) {
                $rules['discount_value'] = 'required|numeric|min:0';
            } elseif ($request['discount_type'] == 2) {
                $rules['discount_value'] = 'required|numeric|min:0|max:100';
            }
            if ($request['is_use_limit'] == 1) {
                $rules['usage_limit'] = 'required|numeric|min:1';
            }

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()->all()]);
            }

            $requestData = $request->all();
            $requestData['code']           = !empty($requestData['code']) ? strtoupper($requestData['code']) : strtoupper(Str::random(8));
            $requestData['usage_limit']    = $request['is_use_limit'] == 1 ? (int)$request['usage_limit'] : 0;
            $requestData['usage_per_user'] = (int)($request['usage_per_user'] ?? 0);
            $requestData['applicable_for'] = (int)($request['applicable_for'] ?? 0);
            $requestData['is_single_use']  = (int)($request['is_single_use'] ?? 0);
            $requestData['package_id']     = $request['applicable_for'] == 1 ? ($request['package_id'] ?? 0) : 0;
            $requestData['description']    = $request['description'] ?? "";
            unset($requestData['is_use_limit']);

            $coupon_data = Coupon::updateOrCreate(['id' => $requestData['id'] ?: null], $requestData);
            if (isset($coupon_data->id)) {
                return response()->json(['status' => 200, 'success' => __('label.success_add_coupon')]);
            }
            return response()->json(['status' => 400, 'errors' => __('label.error_add_coupon')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function update($id, Request $request)
    {
        try {
            $rules = [
                'title'          => 'required|min:2',
                'start_date'     => 'required',
                'end_date'       => 'required|date|after_or_equal:start_date',
                'applicable_for' => 'required|in:0,1,2',
            ];
            if ($request['discount_type'] == 1) {
                $rules['discount_value'] = 'required|numeric|min:0';
            } elseif ($request['discount_type'] == 2) {
                $rules['discount_value'] = 'required|numeric|min:0|max:100';
            }
            if ($request['edit_is_use_limit'] == 1) {
                $rules['usage_limit'] = 'required|numeric|min:1';
            }

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()->all()]);
            }

            $requestData = $request->all();
            $requestData['usage_limit']    = $request['edit_is_use_limit'] == 1 ? (int)$request['usage_limit'] : 0;
            $requestData['usage_per_user'] = (int)($request['usage_per_user'] ?? 0);
            $requestData['applicable_for'] = (int)($request['applicable_for'] ?? 0);
            $requestData['is_single_use']  = (int)($request['is_single_use'] ?? 0);
            $requestData['package_id']     = $request['applicable_for'] == 1 ? ($request['package_id'] ?? 0) : 0;
            $requestData['description']    = $request['description'] ?? "";            
            unset($requestData['edit_is_use_limit']);

            $coupon_data = Coupon::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($coupon_data->id)) {
                return response()->json(['status' => 200, 'success' => __('label.success_edit_coupon')]);
            }
            return response()->json(['status' => 400, 'errors' => __('label.error_edit_coupon')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function show($id)
    {
        try {
            $data = Coupon::where('id', $id)->first();
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
    public function destroy($id)
    {
        try {
            Coupon::where('id', $id)->delete();
            return redirect()->route('admin.coupon.index')->with('success', __('label.coupon_delete'));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
