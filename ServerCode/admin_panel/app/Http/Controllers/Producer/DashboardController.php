<?php

namespace App\Http\Controllers\Producer;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Rent_Transaction;
use App\Models\Shorts;
use App\Models\TVShow;
use App\Models\Video;
use App\Models\Withdrawal_Request;
use Exception;

class DashboardController extends Controller
{
    private $folder_content = "content";
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index()
    {
        try {
            $producer = Producer_Data();

            // Stat Cards
            $params['VideoCount'] = Video::where('producer_id', $producer['id'])->count();
            $params['TVShowCount'] = TVShow::where('producer_id', $producer['id'])->count();
            $params['ShortsCount'] = Shorts::where('producer_id', $producer['id'])->count();
            $params['CurrentMounthRentCount'] = Rent_Transaction::where('transaction_status', 2)->where('producer_id', $producer['id'])->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->sum('price');
            $params['RentTransactionCount'] = Rent_Transaction::where('transaction_status', 2)->where('producer_id', $producer['id'])->sum('price');
            $params['PendingWithdrawalCount'] = Withdrawal_Request::where('producer_id', $producer['id'])->where('status', 0)->count();

            // Most View Video & TVShow
            $params['top_video_view'] = Video::where('producer_id', $producer['id'])->orderBy('total_view', 'desc')->where('total_view', '!=', 0)->where('status', 1)->with('type')->take(5)->get();
            $params['top_tvshow_view'] = TVShow::where('producer_id', $producer['id'])->orderBy('total_view', 'desc')->where('total_view', '!=', 0)->where('status', 1)->with('type')->take(5)->get();
            $params['top_shorts_view'] = Shorts::where('producer_id', $producer['id'])->orderBy('total_view', 'desc')->where('total_view', '!=', 0)->where('status', 1)->with('type')->take(5)->get();
            $this->common->imageNameToUrl($params['top_video_view'], 'thumbnail', $this->folder_content, 'portrait');
            $this->common->imageNameToUrl($params['top_tvshow_view'], 'thumbnail', $this->folder_content, 'portrait');
            $this->common->imageNameToUrl($params['top_shorts_view'], 'thumbnail', $this->folder_content, 'portrait');

            // Rent Earning Statistice
            $rent_earning_year = [];
            $rent_earning_month = [];
            $d = date('t', mktime(0, 0, 0, date('m'), 1, date('Y')));
            for ($i = 1; $i < 13; $i++) {
                $Sum = Rent_Transaction::where('transaction_status', 2)->where('producer_id', $producer['id'])->whereYear('created_at', date('Y'))->whereMonth('created_at', $i)->sum('price');
                $rent_earning_year['sum'][] = (int) $Sum;
            }
            for ($i = 1; $i <= $d; $i++) {
                $Sum = Rent_Transaction::where('transaction_status', 2)->where('producer_id', $producer['id'])->whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'))->whereDay('created_at', $i)->sum('price');
                $rent_earning_month['sum'][] = (int) $Sum;
            }
            $params['rent_earning_year'] = json_encode($rent_earning_year);
            $params['rent_earning_month'] = json_encode($rent_earning_month);

            return view('producer.dashboard.dashboard', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
