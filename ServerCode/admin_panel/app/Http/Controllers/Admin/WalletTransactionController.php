<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Exception;

class WalletTransactionController extends Controller
{
    public function index(Request $request)
    {
        try {
            $params['today_sum'] = WalletTransaction::whereDate('created_at', date('Y-m-d'))->selectRaw('SUM(amount) as total_amount')->first();
            $params['month_sum'] = WalletTransaction::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->selectRaw('SUM(amount) as total_amount')->first();
            $params['year_sum']  = WalletTransaction::whereYear('created_at', date('Y'))->selectRaw('SUM(amount) as total_amount')->first();

            if ($request->ajax()) {

                $input_search = $request['input_search'];
                $input_type   = $request['input_type'];

                $query = WalletTransaction::with('user');

                // Filter by user name / email / mobile
                if (!empty($input_search)) {
                    $query->where(function ($q) use ($input_search) {
                        $q->where('amount', 'LIKE', "%{$input_search}%")
                            ->orWhere('transaction_id', 'LIKE', "%{$input_search}%")
                            ->orWhereHas('user', fn($v) => $v->where('full_name', 'LIKE', "%{$input_search}%")
                                ->orWhere('email', 'LIKE', "%{$input_search}%")
                                ->orWhere('mobile_number', 'LIKE', "%{$input_search}%"));
                    });
                }
                // Filter by date
                if ($input_type == 'today') {
                    $query->whereDay('created_at', date('d'))->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'));
                } elseif ($input_type == 'month') {
                    $query->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'));
                } elseif ($input_type == 'year') {
                    $query->whereYear('created_at', date('Y'));
                }
                $data = $query->latest();

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('date', fn($row) => date('d M Y', strtotime($row->created_at)))
                    ->make(true);
            }

            return view('admin.wallet_transaction.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
