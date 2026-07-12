<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Rent_Price_List;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class RentPriceListController extends Controller
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

                $query = Rent_Price_List::query();

                $input_search = $request['input_search'];
                if ($input_search != null) {
                    $query->where('price', 'LIKE', "%{$input_search}%");
                }
                $data = $query->orderby('status', 'desc')->latest()->get();

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $btn  = '<div class="d-flex justify-content-around">';
                        $btn .= '<a class="edit-delete-btn mr-2 edit_price" data-toggle="modal" href="#EditModel"'
                            . ' data-id="' . $row->id . '"'
                            . ' data-price="' . $row->price . '"'
                            . ' data-android_product_package="' . e($row->android_product_package) . '"'
                            . ' data-ios_product_package="' . e($row->ios_product_package) . '"'
                            . ' data-web_price_id="' . e($row->web_price_id) . '">'
                            . '<i class="fa-solid fa-pen-to-square fa-xl"></i></a>';
                        $btn .= '<form class="delete-form" method="POST" action="' . route('admin.rent-price-list.destroy', [$row->id]) . '">'
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
            return view('admin.rent_price_list.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'price' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()->all()]);
            }

            $requestData = $request->all();
            $requestData['android_product_package'] = $request['android_product_package'] ?? "";
            $requestData['ios_product_package'] = $request['ios_product_package'] ?? "";
            $requestData['web_price_id'] = $request['web_price_id'] ?? "";
            $requestData['status'] = 1;

            $price_data = Rent_Price_List::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($price_data->id)) {
                return response()->json(['status' => 200, 'success' => __('label.success_add_price')]);
            }
            return response()->json(['status' => 400, 'errors' => __('label.error_add_price')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function update($id, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'price' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()->all()]);
            }

            $requestData = $request->all();
            $requestData['android_product_package'] = $request['android_product_package'] ?? "";
            $requestData['ios_product_package'] = $request['ios_product_package'] ?? "";
            $requestData['web_price_id'] = $request['web_price_id'] ?? "";

            $price_data = Rent_Price_List::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($price_data->id)) {
                return response()->json(['status' => 200, 'success' => __('label.success_edit_price')]);
            }
            return response()->json(['status' => 400, 'errors' => __('label.error_edit_price')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function show($id)
    {
        try {
            $data = Rent_Price_List::where('id', $id)->first();
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

            Rent_Price_List::where('id', $id)->delete();
            return redirect()->route('admin.rent-price-list.index')->with('success', __('label.price_delete'));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
