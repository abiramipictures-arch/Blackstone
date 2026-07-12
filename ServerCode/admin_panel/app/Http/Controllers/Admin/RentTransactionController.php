<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Video;
use App\Models\Common;
use App\Models\Producer;
use App\Models\Rent_Price_List;
use App\Models\Rent_Transaction;
use App\Models\TVShow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
            // Year
            $params['year_sum'] = Rent_Transaction::where('transaction_status', 2)->whereYear('created_at', date('Y'))
                ->selectRaw('SUM(commission) as total_commission, SUM(producer_earning) as total_producer_earning')->first();
            // Month
            $params['month_sum'] = Rent_Transaction::where('transaction_status', 2)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))
                ->selectRaw('SUM(commission) as total_commission, SUM(producer_earning) as total_producer_earning')->first();
            // Today
            $params['today_sum'] = Rent_Transaction::where('transaction_status', 2)->whereDate('created_at', date('Y-m-d'))
                ->selectRaw('SUM(commission) as total_commission, SUM(producer_earning) as total_producer_earning')->first();

            if ($request->ajax()) {

                $input_type = $request['input_type'];
                $input_search = $request['input_search'];

                $query = Rent_Transaction::query();
                if (!empty($input_search)) {
                    $query->where(function ($q) use ($input_search) {
                        $q->where('transaction_id', 'LIKE', "%{$input_search}%")
                            ->orWhereHas('user', fn($v) => $v->where('full_name', 'LIKE', "%{$input_search}%")->orWhere('email', 'LIKE', "%{$input_search}%")->orWhere('mobile_number', 'LIKE', "%{$input_search}%"))
                            ->orWhereHas('producer', fn($v) => $v->where('full_name', 'LIKE', "%{$input_search}%"))
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
                $data = $query->with('user', 'producer')->latest();

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {

                        $delete = '<form class="delete-form" method="POST" action="' . route('admin.rent-transaction.destroy', [$row->id]) . '">
                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="edit-delete-btn"><i class="fa-solid fa-trash-can fa-xl"></i></button></form>';

                        $btn = '<div class="d-flex justify-content-around">';
                        $btn .= $delete;
                        $btn .= '</div>';
                        return $btn;
                    })
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
                    ->rawColumns(['action', 'expiry_status', 'payment_transaction_status', 'price_details'])
                    ->make(true);
            }
            return view('admin.rent_transaction.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function create(Request $request)
    {
        try {
            $params['user'] = User::where('id', $request->user_id)->first();
            $params['rent_video'] = Video::whereIn('video_type', [1, 6, 7])->where('status', 1)->where('is_rent', 1)->with('rent_price_list')->latest()->get();
            $params['rent_tv_show'] = TVShow::whereIn('video_type', [2, 6, 7])->where('status', 1)->where('is_rent', 1)->with('rent_price_list')->latest()->get();

            return view('admin.rent_transaction.add', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function searchUser(Request $request)
    {
        try {
            $name = $request->name;
            $user = User::orWhere('full_name', 'like', '%' . $name . '%')->orWhere('mobile_number', 'like', '%' . $name . '%')->orWhere('email', 'like', '%' . $name . '%')->latest()->get()->take(25);

            $url = url('admin/rent-transaction/create?user_id');
            $text = '<div class="table-responsive"><table width="100%" class="table table-striped text-center table-bordered"><thead><tr><th>' . __("label.full_name") . '</th><th>' . __("label.mobile_number") . '</th><th>' . __("label.email") . '</th><th>' . __("label.action") . '</th></tr></thead>';
            if ($user->count() > 0) {
                foreach ($user as $row) {

                    $a = '<a class="btn-link" style="color: var(--primary-color) !important;" href="' . $url . '=' . $row->id . '">' . __("label.select") . '</a>';
                    $text .= '<tr><td>' . $row->full_name . '</td><td>' . $row->mobile_number . '</td><td>' . $row->email . '</td><td>' . $a . '</td></tr>';
                }
            } else {
                $text .= '<tr><td colspan="4">' . __("label.user_not_found") . '</td></tr>';
            }
            $text .= '</table></div>';

            return response()->json(['status' => 200, 'success' => __('label.data_get_successfully'), 'result' => $text]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()->all()]);
            }

            if ($request['rent_video_id'] != "") {

                $Video = Video::where('id', $request['rent_video_id'])->first();
                if ($Video) {

                    $rent_day = $Video['rent_day'];
                    $baseDate = now();
                    $expiry_date = date('Y-m-d H:i:s', strtotime("+$rent_day days", strtotime($baseDate)));
                } else {
                    return response()->json(['status' => 400, 'errors' => __('label.select_right_video_or_tvshow')]);
                }

                $rent_price = Rent_Price_List::where('id', $Video['price'])->first();
                if (!isset($rent_price['id'])) {
                    return response()->json(['status' => 400, 'errors' => __('label.rent_price_not_found')]);
                }
                $insert = new Rent_Transaction();
                $insert->coupon_code = "";
                $insert->user_id = $request['user_id'];
                $insert->producer_id = $Video['producer_id'] ?? 0;
                $insert->video_type = $Video['video_type'];
                $insert->sub_video_type = 0;
                if ($Video['video_type'] == 6 || $Video['video_type'] == 7) {
                    $insert->sub_video_type = 1;
                }
                $insert->video_id = $Video['id'];
                $insert->payment_type = 0;
                $insert->price = $rent_price['price'];
                $insert->producer_earning = 0;
                $insert->commission =  0;
                $insert->transaction_id = 'admin';
                $insert->description = 'admin';
                $insert->expiry_date = $expiry_date;
                $insert->transaction_status = 2;
                $insert->status = 1;

                if ($Video['producer_id'] != 0) {

                    $commission = Commission();
                    $commission_price = round(((int)$rent_price['price'] * (int)$commission) / 100);
                    $producer_earning = $rent_price['price'] - $commission_price;
                    $insert->commission =  $commission_price;
                    $insert->producer_earning = $producer_earning;
                }
            } else if ($request['rent_show_id'] != "") {

                $TVShow = TVShow::where('id', $request['rent_show_id'])->first();
                if ($TVShow) {

                    $rent_day = $TVShow['rent_day'];
                    $baseDate = now();
                    $expiry_date = date('Y-m-d H:i:s', strtotime("+$rent_day days", strtotime($baseDate)));
                } else {
                    return response()->json(['status' => 400, 'errors' => __('label.select_right_video_or_tvshow')]);
                }

                $rent_price = Rent_Price_List::where('id', $TVShow['price'])->first();
                if (!isset($rent_price['id'])) {
                    return response()->json(['status' => 400, 'errors' => __('label.rent_price_not_found')]);
                }
                $insert = new Rent_Transaction();
                $insert->coupon_code = "";
                $insert->user_id = $request['user_id'];
                $insert->producer_id = $TVShow['producer_id'] ?? 0;
                $insert->video_type = $TVShow['video_type'];
                $insert->sub_video_type = 0;
                if ($TVShow['video_type'] == 6 || $TVShow['video_type'] == 7) {
                    $insert->sub_video_type = 2;
                }
                $insert->video_id = $TVShow['id'];
                $insert->payment_type = 0;
                $insert->price = $rent_price['price'];
                $insert->commission =  0;
                $insert->producer_earning = 0;
                $insert->transaction_id = 'admin';
                $insert->description = 'admin';
                $insert->expiry_date = $expiry_date;
                $insert->transaction_status = 2;
                $insert->status = 1;

                if ($TVShow['producer_id'] != 0) {

                    $commission = Commission();
                    $commission_price = round(((int)$rent_price['price'] * (int)$commission) / 100);
                    $producer_earning = $rent_price['price'] - $commission_price;
                    $insert->commission =  $commission_price;
                    $insert->producer_earning = $producer_earning;
                }
            } else {
                return response()->json(['status' => 400, 'errors' => __('label.select_right_video_or_tvshow')]);
            }

            if ($insert->save()) {
                $producer = Producer::where('id', $insert->producer_id)->first();
                if ($producer) {
                    $producer->increment('wallet', $insert->producer_earning);
                }

                return response()->json(['status' => 200, 'success' => __('label.success_add_transaction')]);
            }
            return response()->json(['status' => 400, 'errors' => __('label.error_add_transaction')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function destroy($id)
    {
        try {

            Rent_Transaction::where('id', $id)->delete();
            return redirect()->route('admin.rent-transaction.index')->with('success', __('label.transaction_delete'));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
