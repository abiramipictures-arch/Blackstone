<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Cast;
use App\Models\Common;
use App\Models\User;
use App\Models\Category;
use App\Models\Channel;
use App\Models\Package;
use App\Models\Producer;
use App\Models\Rent_Transaction;
use App\Models\Shorts;
use App\Models\Transaction;
use App\Models\TVShow;
use App\Models\Video;
use App\Models\Withdrawal_Request;
use Exception;

class DashboardController extends Controller
{
    private $folder_content = "content";
    private $folder_category = "category";
    private $folder_channel = "channel";
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index()
    {
        try {

            // Package Expiry
            $this->common->package_expiry();

            // First Card
            $params['UserCount'] = User::count();
            $params['VideoCount'] = Video::count();
            $params['TVShowCount'] = TVShow::count();
            $params['ChannelCount'] = Channel::count();
            $params['CastCount'] = Cast::count();
            // Second Card
            $params['ProducerCount'] = Producer::count();
            $params['PackageCount'] = Package::count();
            $params['TotalWithdrawalCount'] = Withdrawal_Request::where('status', 1)->sum('price');
            // Fourth Card
            $params['ShortsCount'] = Shorts::count();
            $params['ActiveSubscriptions'] = Transaction::where('status', 1)->count();
            $params['TodayRevenue'] = Transaction::where('transaction_status', 2)->whereDate('created_at', today())->sum('price');
            $params['PendingWithdrawals'] = Withdrawal_Request::where('status', 0)->count();
            $params['CurrentMounthCount'] = Transaction::where('transaction_status', 2)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->sum('price');
            $params['CurrentMounthRentCount'] = Rent_Transaction::where('transaction_status', 2)->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->sum('price');
            $params['TransactionCount'] = Transaction::where('transaction_status', 2)->sum('price');
            $params['RentTransactionCount'] = Rent_Transaction::where('transaction_status', 2)->sum('price');

            // User Statistice
            $user_year = [];
            $user_month = [];
            $d = date('t', mktime(0, 0, 0, date('m'), 1, date('Y')));
            for ($i = 1; $i < 13; $i++) {
                $Sum = User::whereYear('created_at', date('Y'))->whereMonth('created_at', $i)->count();
                $user_year['sum'][] = (int) $Sum;
            }
            for ($i = 1; $i <= $d; $i++) {

                $Sum = User::whereYear('created_at', date('Y'))->whereMonth('created_at', date('m'))->whereDay('created_at', $i)->count();
                $user_month['sum'][] = (int) $Sum;
            }
            $params['user_year'] = json_encode($user_year);
            $params['user_month'] = json_encode($user_month);

            // Rent Earning Statistice
            $rent_data = [];
            for ($i = 1; $i < 13; $i++) {
                $Sum = Rent_Transaction::where('transaction_status', 2)->whereYear('created_at', date('Y'))->whereMonth('created_at', $i)->sum('price');
                $rent_data['sum'][] = (int) $Sum;
            }
            $params['rent_earning'] = json_encode($rent_data);

            // Package Statistice
            $subscription = Package::where('package_type', 1)->get();
            $pack_data = [];
            foreach ($subscription as $row) {

                $sum = array();
                for ($i = 1; $i < 13; $i++) {

                    $Sum = Transaction::where('transaction_status', 2)->where('package_id', $row->id)->whereYear('created_at', date('Y'))->whereMonth('created_at', $i)->sum('price');
                    $sum[] = (int) $Sum;
                }
                $pack_data['label'][] = $row->name;
                $pack_data['sum'][] = $sum;
            }
            $params['package_data'] = json_encode($pack_data);

            // Most Used Categorise
            $categories = [];
            $video_categroy_ids = [];
            $tvshow_categroy_ids = [];

            $videos = Video::pluck('category_id'); // video
            foreach ($videos as $videoCategories) {
                $ids = explode(',', $videoCategories);
                foreach ($ids as $id) {
                    $video_categroy_ids[] = trim($id);
                }
            }
            $tvshows = TVShow::pluck('category_id'); // tvshow
            foreach ($tvshows as $tvCategories) {
                $ids = explode(',', $tvCategories);
                foreach ($ids as $id) {
                    $tvshow_categroy_ids[] = trim($id);
                }
            }
            $categories = array_merge($video_categroy_ids, $tvshow_categroy_ids);
            // Count frequency of each category
            $counts = array_count_values($categories);
            // Sort by descending
            arsort($counts);
            $category_id = array_slice($counts, 0, 4, true);

            // Prepare chart data
            $labels = [];
            $sum = [];

            foreach ($category_id as $key => $value) {
                $data = Category::find($key);
                if ($data) {
                    $labels[] = $data->name;   // category name
                    $sum[] = $value;           // usage count
                }
            }
            $params['most_used_categorise'] = json_encode([
                'labels' => $labels,
                'sum'    => $sum
            ]);

            // Most View Video & TVShow & Shorts
            $params['top_video_view'] = Video::orderBy('total_view', 'desc')->where('total_view', '!=', 0)->where('status', 1)->with('type')->take(5)->get();
            $params['top_tvshow_view'] = TVShow::orderBy('total_view', 'desc')->where('total_view', '!=', 0)->where('status', 1)->with('type')->take(5)->get();
            $params['top_shorts_view'] = Shorts::orderBy('total_view', 'desc')->where('total_view', '!=', 0)->where('status', 1)->with('type')->take(5)->get();
            $this->common->imageNameToUrl($params['top_video_view'], 'thumbnail', $this->folder_content, 'portrait');
            $this->common->imageNameToUrl($params['top_tvshow_view'], 'thumbnail', $this->folder_content, 'portrait');
            $this->common->imageNameToUrl($params['top_shorts_view'], 'thumbnail', $this->folder_content, 'portrait');

            // Best Category
            $params['best_category'] = Category::orderBy('id', 'desc')->take(8)->get();
            $this->common->imageNameToUrl($params['best_category'], 'image', $this->folder_category, 'normal');

            // Best Channel
            $params['best_channel'] = Channel::orderBy('id', 'desc')->take(8)->get();
            $this->common->imageNameToUrl($params['best_channel'], 'portrait_img', $this->folder_channel, 'portrait');

            return view('admin.dashboard.dashboard', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
