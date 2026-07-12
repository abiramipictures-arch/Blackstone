<?php

namespace App\Http\Controllers\Producer;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Rent_Transaction;
use Illuminate\Http\Request;
use Exception;

// 1- Video, 2- Show, 3- Category, 4-Language, 5- Upcoming, 6- Channel, 7- Kids
class RentTransactionController extends Controller
{
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {

            // Rent Expiry
            $this->common->rent_expiry();
            $producer = Producer_Data();

            $params['data'] = [];
            // Year
            $params['year_sum'] = Rent_Transaction::where('producer_id', $producer['id'])->where('transaction_status', 2)->whereYear('created_at', date('Y'))
                ->selectRaw('SUM(commission) as total_commission, SUM(producer_earning) as total_producer_earning')->first();
            // Month
            $params['month_sum'] = Rent_Transaction::where('producer_id', $producer['id'])->where('transaction_status', 2)->whereMonth('created_at', date('m'))
                ->whereYear('created_at', date('Y'))->selectRaw('SUM(commission) as total_commission, SUM(producer_earning) as total_producer_earning')->first();
            // Today
            $params['today_sum'] = Rent_Transaction::where('producer_id', $producer['id'])->where('transaction_status', 2)->whereDate('created_at', date('Y-m-d'))
                ->selectRaw('SUM(commission) as total_commission, SUM(producer_earning) as total_producer_earning')->first();

            if ($request->ajax()) {

                $input_type = $request['input_type'];
                $input_search = $request['input_search'];

                $query = Rent_Transaction::where('producer_id', $producer['id']);
                if (!empty($input_search)) {
                    $query->where(function ($q) use ($input_search) {
                        $q->where('transaction_id', 'LIKE', "%{$input_search}%")
                            ->orWhereHas('user', fn($v) => $v->where('full_name', 'LIKE', "%{$input_search}%")->orWhere('email', 'LIKE', "%{$input_search}%")->orWhere('mobile_number', 'LIKE', "%{$input_search}%"))
                            ->orWhereHas('video', fn($v) => $v->where('name', 'LIKE', "%{$input_search}%"))
                            ->orWhereHas('tvshow', fn($v) => $v->where('name', 'LIKE', "%{$input_search}%"));
                    });
                }
                if ($input_type == "today") {
                    $query->whereDay('created_at', date('d'))->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'));
                } elseif ($input_type == "month") {
                    $query->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'));
                } elseif ($input_type == "year") {
                    $query->whereYear('created_at', date('Y'));
                }
                $data = $query->with('user')->latest();

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('expiry_status', function ($row) {
                        $expiry = $row->expiry_date ? date('d M Y', strtotime($row->expiry_date)) : '-';
                        $badge  = $row->status == 1
                            ? "<span class='badge badge-success p-2'>" . __('label.active') . "</span>"
                            : "<span class='badge badge-danger p-2'>" . __('label.expiry') . "</span>";
                        return '<div style="font-size:13px;">' . $expiry . '</div><div class="mt-1">' . $badge . '</div>';
                    })
                    ->addColumn('payment_transaction_status', function ($row) {
                        $payment = $row->payment_type == 1
                            ? "<span style='font-size:13px;font-weight:600;' class='primary-color'>" . __('label.wallet') . "</span>"
                            : "<span style='font-size:13px;font-weight:600;' class='primary-color'>" . __('label.online') . "</span>";
                        if ($row->transaction_status == 1)      $status = "<span class='badge badge-primary p-2'>" . __('label.processing') . "</span>";
                        elseif ($row->transaction_status == 2)  $status = "<span class='badge badge-success p-2'>" . __('label.success') . "</span>";
                        elseif ($row->transaction_status == 3)  $status = "<span class='badge badge-danger p-2'>" . __('label.failed') . "</span>";
                        else                                    $status = '-';
                        return '<div>' . $payment . '</div><div class="mt-1">' . $status . '</div>';
                    })
                    ->addColumn('price_details', function ($row) {
                        $currency = Currency_Code();
                        return '<div style="font-size:13px; white-space:nowrap;">' .
                            '<div>' . __('label.price') . ': <b>' . $currency . $row->price . '</b></div>' .
                            '<div>' . __('label.commission') . ': <b>' . $currency . $row->commission . '</b></div>' .
                            '<div>' . __('label.producer_earnings') . ': <b>' . $currency . $row->producer_earning . '</b></div>' .
                            '</div>';
                    })
                    ->addColumn('video_name', fn($row) => Rent_Transaction::getVideoName($row->video_id, $row->video_type, $row->sub_video_type))
                    ->addColumn('date', fn($row) => date('d M Y', strtotime($row->created_at)))
                    ->rawColumns(['expiry_status', 'payment_transaction_status', 'price_details'])
                    ->make(true);
            }
            return view('producer.rent_transaction.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
