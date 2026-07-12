<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Review;
use App\Models\Shorts;
use App\Models\Video;
use App\Models\TVShow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class ReviewController extends Controller
{
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {
            $params['total']    = Review::count();
            $params['pending']  = Review::where('status', 0)->count();
            $params['approved'] = Review::where('status', 1)->count();
            $params['rejected'] = Review::where('status', 2)->count();

            if ($request->ajax()) {

                $input_status = $request['input_status'];
                $input_search = $request['input_search'];

                $query = Review::with([
                    'user:id,full_name,image,storage_type',
                    'video:id,name,thumbnail,storage_type',
                    'tvshow:id,name,thumbnail,storage_type',
                    'shorts:id,name,thumbnail,storage_type',
                ])->select('tbl_review.*');

                if ($input_status !== null && $input_status !== '') {
                    $query->where('status', (int) $input_status);
                }
                if (!empty($input_search)) {
                    $query->where(function ($q) use ($input_search) {
                        $q->whereHas('video', fn($v) => $v->where('name', 'LIKE', "%{$input_search}%"))
                            ->orWhereHas('tvshow', fn($v) => $v->where('name', 'LIKE', "%{$input_search}%"))
                            ->orWhereHas('shorts', fn($v) => $v->where('name', 'LIKE', "%{$input_search}%"))
                            ->orWhereHas('user', fn($v) => $v->where('full_name', 'LIKE', "%{$input_search}%"))
                            ->orWhere('review_text', 'LIKE', "%{$input_search}%");
                    });
                }
                $data = $query->orderBy('status', 'asc')->latest();

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('content', function ($row) {
                        if ($row->video_type == 1 || ($row->video_type == 6 && $row->sub_video_type == 1) || ($row->video_type == 7 && $row->sub_video_type == 1)) {
                            $content = $row->video;
                        } else if ($row->video_type == 2 || ($row->video_type == 6 && $row->sub_video_type == 2) || ($row->video_type == 7 && $row->sub_video_type == 2)) {
                            $content = $row->tvshow;
                        } else if ($row->video_type == 8) {
                            $content = $row->shorts;
                        }
                        return '<div class="d-flex align-items-center">' . ($content->name ?? '-') . '</div>';
                    })
                    ->addColumn('type_badge', function ($row) {

                        $text = '-';
                        if ($row->video_type == 1) {
                            $text = __('label.video');
                        }
                        if ($row->video_type == 2) {
                            $text = __('label.tv_show');
                        }
                        if ($row->video_type == 6 && $row->sub_video_type == 1) {
                            $text = __('label.channel_video');
                        }
                        if ($row->video_type == 6 && $row->sub_video_type == 2) {
                            $text = __('label.channel_tvshow');
                        }
                        if ($row->video_type == 7 && $row->sub_video_type == 1) {
                            $text = __('label.kids_video');
                        }
                        if ($row->video_type == 7 && $row->sub_video_type == 2) {
                            $text = __('label.kids_tvshow');
                        }
                        if ($row->video_type == 8) {
                            $text = __('label.shorts');
                        }
                        return "<h6 class='primary-color d-flex align-items-center'>" .  $text . "</h6>";
                    })
                    ->addColumn('user_info', function ($row) {
                        return '<div class="d-flex align-items-center">' . ($row->user->full_name ?? '-') . '</div>';
                    })
                    ->addColumn('rating_stars', function ($row) {
                        $stars = '';
                        for ($i = 1; $i <= 5; $i++) {
                            $stars .= $i <= $row->rating
                                ? '<i class="fa-solid fa-star" style="color:#f5a623;font-size:12px;"></i>'
                                : '<i class="fa-regular fa-star" style="color:#ccc;font-size:12px;"></i>';
                        }
                        return $stars;
                    })
                    ->addColumn('review_text', function ($row) {
                        if (!$row->review_text) {
                            return '<span class="text-muted">-</span>';
                        }
                        $short = mb_strlen($row->review_text) > 80
                            ? mb_substr($row->review_text, 0, 80) . '...'
                            : $row->review_text;
                        return '<span title="' . e($row->review_text) . '" data-toggle="tooltip">' . e($short) . '</span>';
                    })
                    ->addColumn('date', fn($row) => date('d M Y', strtotime($row->created_at)))
                    ->addColumn('status_badge', function ($row) {
                        if ($row->status == 0) {
                            return "<span class='badge badge-warning text-dark p-2' style='font-size:14px;'>" . __('label.pending') . "</span>";
                        } elseif ($row->status == 1) {
                            return "<span class='badge badge-success p-2' style='font-size:14px;'>" . __('label.approved') . "</span>";
                        }
                        return "<span class='badge badge-danger p-2' style='font-size:14px;'>" . __('label.rejected') . "</span>";
                    })
                    ->addColumn('action', function ($row) {
                        $btn = '<div class="d-flex justify-content-around">';
                        if ($row->status == 0) {
                            $btn .= '<button type="button" onclick="approve_review(' . $row->id . ')" class="edit-delete-btn mr-2" title="' . __('label.approve') . '">'
                                . '<i class="fa-solid fa-check fa-xl text-success"></i>'
                                . '</button>';
                            $btn .= '<button type="button" onclick="reject_review(' . $row->id . ')" class="edit-delete-btn mr-2" title="' . __('label.reject') . '">'
                                . '<i class="fa-solid fa-xmark fa-xl text-warning"></i>'
                                . '</button>';
                        }
                        $btn .= '<button type="button" class="edit-delete-btn" title="' . __('label.delete') . '" onclick="deleteReview(\'' . route('admin.reviews.destroy', $row->id) . '\')">'
                            . '<i class="fa-solid fa-trash-can fa-xl"></i>'
                            . '</button>';

                        $btn .= '</div>';
                        return $btn;
                    })
                    ->rawColumns(['content', 'type_badge', 'user_info', 'rating_stars', 'review_text', 'status_badge', 'action'])
                    ->make(true);
            }
            return view('admin.review.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function approve(int $id)
    {
        try {
            $review = Review::where('id', $id)->first();
            if (!$review) {
                return response()->json(['status' => 400, 'errors' => __('label.data_not_found')]);
            }
            if (!in_array($review->status, [0, 2])) {
                return response()->json(['status' => 400, 'errors' => __('label.invalid_action')]);
            }

            DB::transaction(function () use ($review) {
                $review->update(['status' => 1]);
                $this->recalculate_avg_rating($review->video_type, $review->sub_video_type, $review->video_id);
            });

            return response()->json(['status' => 200, 'success' => __('label.review_approved')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function reject(int $id)
    {
        try {
            $review = Review::where('id', $id)->first();
            if (!$review) {
                return response()->json(['status' => 400, 'errors' => __('label.data_not_found')]);
            }
            if (!in_array($review->status, [0, 1])) {
                return response()->json(['status' => 400, 'errors' => __('label.invalid_action')]);
            }

            DB::transaction(function () use ($review) {
                $review->update(['status' => 2]);
                $this->recalculate_avg_rating($review->video_type, $review->sub_video_type, $review->video_id);
            });

            return response()->json(['status' => 200, 'success' => __('label.review_rejected')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function destroy(int $id)
    {
        try {
            $review = Review::where('id', $id)->first();
            if ($review) {
                $review->delete();
                $this->recalculate_avg_rating($review->video_type, $review->sub_video_type, $review->video_id);
            }

            return redirect()->route('admin.reviews.index')->with('success', __('label.review_deleted'));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    private function recalculate_avg_rating(int $videoType, int $subVideoType, int $videoId): void
    {
        $result = Review::selectRaw('AVG(rating) as avg_rating, COUNT(*) as total_reviews')->where('video_type', $videoType)->where('sub_video_type', $subVideoType)->where('video_id', $videoId)->where('status', 1)->first();
        $avg   = round($result->avg_rating ?? 0, 1);
        $count = $result->total_reviews ?? 0;

        if ($videoType == 8) {
            Shorts::where('id', $videoId)->update(['avg_rating' => $avg, 'total_review' => $count]);
        } elseif ($videoType == 1 || ($videoType == 5 && $subVideoType == 1) || ($videoType == 6 && $subVideoType == 1) || ($videoType == 7 && $subVideoType == 1)) {
            Video::where('id', $videoId)->update(['avg_rating' => $avg, 'total_review' => $count]);
        } elseif ($videoType == 2 || ($videoType == 5 && $subVideoType == 2) || ($videoType == 6 && $subVideoType == 2) || ($videoType == 7 && $subVideoType == 2)) {
            TVShow::where('id', $videoId)->update(['avg_rating' => $avg, 'total_review' => $count]);
        }
    }
}
