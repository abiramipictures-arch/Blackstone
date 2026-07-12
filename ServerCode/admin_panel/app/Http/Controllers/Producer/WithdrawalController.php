<?php

namespace App\Http\Controllers\Producer;

use App\Http\Controllers\Controller;
use App\Models\Withdrawal_Request;
use App\Models\Common;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;

class WithdrawalController extends Controller
{
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {
            $params['producer'] = Producer_Data();
            $params['setting'] = Setting_Data();

            if ($request->ajax()) {

                $query = Withdrawal_Request::where('producer_id', $params['producer']['id']);

                $input_status = $request['input_status'];
                if ($input_status != "all") {
                    $query->where('status', $input_status);
                }
                $data = $query->orderBy('status', 'asc')->latest();

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('date', function ($row) {
                        $date = date("Y-m-d", strtotime($row->created_at));
                        return $date;
                    })
                    ->addColumn('status', function ($row) {
                        if ($row->status == 1) {
                            return "<button type='button' id='$row->id' class='show-btn'>" . __('label.completed') . "</button>";
                        } else {
                            return "<button type='button' id='$row->id' class='hide-btn'>" . __('label.pending') . "</button>";
                        }
                    })
                    ->rawColumns(['status'])
                    ->make(true);
            }
            return view('producer.withdrawal.index', $params);
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

            $producer = Producer_Data();
            $setting = Setting_Data();
            if ($request['price'] < $setting['min_withdrawal_amount']) {
                return response()->json(['status' => 400, 'errors' => __('label.min_withdrawal_limit', ['amount' => $setting['min_withdrawal_amount'] ?? 1])]);
            }

            if ($producer['wallet'] < $request['price']) {
                return response()->json(['status' => 400, 'errors' => __('label.withdrawal_amount_exceeds_your_available_wallet_balance')]);
            }

            $requestData = $request->all();
            $requestData['producer_id'] = $producer['id'];
            $requestData['status'] = 0;

            $Request_data = Withdrawal_Request::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($Request_data->id)) {

                $producer->decrement('wallet', $Request_data['price']);
                return response()->json(['status' => 200, 'success' => __('label.success_add_withdrawal_request')]);
            }
            return response()->json(['status' => 400, 'errors' => __('label.error_add_withdrawal_request')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
