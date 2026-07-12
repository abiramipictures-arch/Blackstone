<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bookmark;
use App\Models\Cast;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Common;
use App\Models\Language;
use App\Models\Like;
use App\Models\Producer;
use App\Models\Review;
use App\Models\Season;
use App\Models\Shorts;
use App\Models\Shorts_Episode;
use App\Models\Type;
use App\Models\Video_Watch;
use App\Models\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

// Video Type = 1-Video, 2-Show, 3-Language, 4-Category, 5-Upcoming, 6-Channel, 7-Kids, 8-Shorts
class ShortsController extends Controller
{
    private $folder_content = "content";
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request, $type_id)
    {
        try {
            $params['type'] = Type::where('id', $type_id)->where('status', 1)->first();
            if ($params['type'] == null) {
                return view('errors.404');
            }

            $params['producer'] = Producer::where('status', 1)->latest()->get();

            if ($request->ajax()) {
                $input_search   = $request['input_search'];
                $input_producer = $request['input_producer'];
                $input_status   = $request['input_status'];

                $query = Shorts::where('type_id', $type_id)
                    ->select(['id', 'type_id', 'name', 'thumbnail', 'storage_type', 'trailer_url', 'trailer_type', 'trailer_storage_type', 'total_view', 'avg_rating', 'total_review', 'producer_id', 'status']);

                if ($input_search) {
                    $query->where('name', 'LIKE', "%{$input_search}%");
                }
                if ($input_producer && $input_producer != 0) {
                    $query->where('producer_id', $input_producer);
                }
                if ($input_status !== null && $input_status !== 'all') {
                    $query->where('status', $input_status);
                }
                $data = $query->with('producer')->orderBy('status', 'desc')->latest();

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('thumbnail_img', function ($row) {
                        return $this->common->getImage($this->folder_content, $row->thumbnail, 'portrait', $row->storage_type);
                    })
                    ->addColumn('name_col', function ($row) {
                        $producer = $row->producer ? '<br><small class="text-muted">' . e($row->producer->user_name) . '</small>' : '';
                        return '<div style="text-align:left;">' . e($row->name) . $producer . '</div>';
                    })
                    ->addColumn('stats', function ($row) {
                        $views  = No_Format($row->total_view ?? 0);
                        $rating = number_format($row->avg_rating ?? 0, 1);
                        $count  = No_Format($row->total_review ?? 0);
                        return '<div class="vi-stat-row"><i class="fa-solid fa-eye fa-md"></i> ' . $views . '</div>'
                            . '<div class="vi-stat-row vi-stat-rating"><i class="fa-solid fa-star fa-md"></i> ' . $rating . ' <span class="vi-stat-count">(' . $count . ')</span></div>';
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
                    ->addColumn('action', function ($row) use ($type_id) {
                        $btn = '<div class="d-flex justify-content-around">';
                        $btn .= '<a href="' . route('admin.shorts.episode.index', ['id' => $row->id, 'type_id' => $type_id]) . '" class="edit-delete-btn mr-2" title="' . __('label.episode_list') . '"><i class="fa-solid fa-list fa-xl"></i></a>';
                        $btn .= '<a href="' . route('admin.shorts.edit', ['shorts_id' => $row->id, 'type_id' => $type_id]) . '" class="edit-delete-btn mr-2"><i class="fa-solid fa-pen-to-square fa-xl"></i></a>';
                        $btn .= '<button type="button" class="edit-delete-btn" title="' . __('label.delete') . '" onclick="deleteShorts(\'' . route('admin.shorts.show', ['shorts_id' => $row->id, 'type_id' => $type_id]) . '\')"><i class="fa-solid fa-trash-can fa-xl"></i></button>';
                        $btn .= '</div>';
                        return $btn;
                    })
                    ->rawColumns(['thumbnail_img', 'name_col', 'stats', 'status', 'action'])
                    ->make(true);
            }

            return view('admin.shorts.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function create($type_id)
    {
        try {
            $type = Type::where('id', $type_id)->where('status', 1)->first();
            if ($type == null) {
                return view('errors.404');
            }

            $params['type'] = $type;
            $params['category'] = Category::where('status', 1)->orderBy('sort_order', 'asc')->get();
            $params['language'] = Language::where('status', 1)->orderBy('sort_order', 'asc')->get();
            $params['producer'] = Producer::get();
            $params['cast'] = Cast::where('status', 1)->get();

            return view('admin.shorts.add', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function store(Request $request)
    {
        try {
            $rules = [
                'name' => 'required|min:2',
                'type_id' => 'required',
                'video_type' => 'required',
                'is_title' => 'required',
                'is_comment' => 'required',
                'is_like' => 'required',
                'trailer_type' => 'required',
            ];
            if ($request['trailer_type'] == 'server_video') {
                $rules['trailer'] = 'required';
            } else {
                $rules['trailer_url'] = 'required';
            }
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()->all()]);
            }

            $storage_type = Storage_Type();

            $insert = new Shorts();
            $insert['type_id'] = $request['type_id'];
            $insert['video_type'] = $request['video_type'];
            $insert['producer_id'] = $request['producer_id'] ?? 0;
            $insert['category_id'] = is_array($request['category_id']) ? implode(',', $request['category_id']) : '';
            $insert['language_id'] = is_array($request['language_id']) ? implode(',', $request['language_id']) : '';
            $insert['cast_id'] = is_array($request['cast_id']) ?  implode(',', $request['cast_id']) : "";
            $insert['name'] = $request['name'];
            $insert['storage_type'] = $storage_type;
            $insert['thumbnail'] = "";
            $file = $request->file('thumbnail');
            if (isset($file) && $file != null) {
                $insert['thumbnail'] = $this->common->saveImage($file, $this->folder_content, 'shorts_', $insert['storage_type']);
            }

            // Trailer
            $insert['trailer_storage_type'] = $storage_type;
            $insert['trailer_type'] = $request['trailer_type'];
            if ($request['trailer_type'] == 'server_video') {

                $trailer = $request['trailer'] ?? '';
                if ($insert['trailer_storage_type'] == 1) {
                    $insert['trailer_url'] = $trailer;
                } else {
                    $insert['trailer_url'] = $trailer != null ? $this->common->saveImage($trailer, $this->folder_content, 'shorts_', $insert['trailer_storage_type']) : "";
                }
            } else {
                $insert['trailer_url'] = $request['trailer_url'];
            }

            $insert['description'] = $request['description'] ?? "";
            $insert['is_title'] = $request['is_title'];
            $insert['is_comment'] = $request['is_comment'];
            $insert['is_like'] = $request['is_like'];
            $insert['total_view'] = 0;
            $insert['total_like'] = 0;
            $insert['avg_rating'] = 0;
            $insert['total_review'] = 0;
            $insert['status'] = 1;

            if ($insert->save()) {

                // Send Notification
                $check = $this->common->NotificationConfiguration('shorts');
                if (isset($check) && $check['status'] == 1 && $check['send_notification'] == 1) {

                    $imageURL = $this->common->getImage($this->folder_content, $insert->thumbnail, 'normal', $insert->storage_type);
                    $noti_array = array(
                        'id' => $insert->id,
                        'name' => $insert->name,
                        'image' => $imageURL,
                        'type_id' => $insert->type_id,
                        'video_type' => $insert->video_type,
                        'sub_video_type' => 0,
                        'description' => String_Cut($insert->description, 90),
                    );
                    $this->common->sendNotification($noti_array);
                }
                return response()->json(['status' => 200, 'success' => __('label.success_add_shorts')]);
            }
            return response()->json(['status' => 400, 'errors' => __('label.error_add_shorts')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function edit($id, $type_id)
    {
        try {
            $params['type_id'] = $type_id;
            $params['type'] = Type::where('id', $type_id)->where('status', 1)->first();
            if (!$params['type']) {
                return view('errors.404');
            }

            $params['data'] = Shorts::where('id', $id)->first();
            if ($params['data'] != null) {

                $params['data']['thumbnail'] = $this->common->getImage($this->folder_content, $params['data']['thumbnail'], 'portrait', $params['data']['storage_type']);

                $params['category'] = Category::where('status', 1)->orderBy('sort_order', 'asc')->get();
                $params['language'] = Language::where('status', 1)->orderBy('sort_order', 'asc')->get();
                $params['producer'] = Producer::get();
                $params['cast'] = Cast::where('status', 1)->get();

                return view('admin.shorts.edit', $params);
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
                'name' => 'required|min:2',
                'is_title' => 'required',
                'is_comment' => 'required',
                'is_like' => 'required',
                'trailer_type' => 'required',
            ];
            if ($request['trailer_type'] != 'server_video') {
                $rules['trailer_url'] = 'required';
            }
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()->all()]);
            }

            $shorts = Shorts::where('id', $request['id'])->first();
            if (isset($shorts->id)) {

                $shorts['type_id'] = $request['type_id'];
                $shorts['video_type'] = $request['video_type'];
                $shorts['producer_id'] = $request['producer_id'] ?? 0;
                $shorts['category_id'] = is_array($request['category_id']) ? implode(',', $request['category_id']) : '';
                $shorts['language_id'] = is_array($request['language_id']) ? implode(',', $request['language_id']) : '';
                $shorts['cast_id'] = is_array($request['cast_id']) ?  implode(',', $request['cast_id']) : "";
                $shorts['name'] = $request['name'];

                $storage_type = Storage_Type();
                $file = $request->file('thumbnail');
                if ($file != null && isset($file)) {

                    $shorts['storage_type'] = $storage_type;
                    $shorts['thumbnail'] = $this->common->saveImage($file, $this->folder_content, 'shorts_', $storage_type);
                    $this->common->deleteImageToFolder($this->folder_content, basename($request['old_thumbnail']), $request['old_storage_type']);
                }

                // Trailer
                $shorts['trailer_type'] = $request['trailer_type'];
                if ($request['trailer_type'] == 'server_video') {
                    if ($request['trailer_type'] == $request['old_trailer_type']) {
                        if ($request['trailer']) {

                            $shorts['trailer_storage_type'] = $storage_type;
                            if ($storage_type == 1) {
                                $shorts['trailer_url'] = $request['trailer'];
                            } else {
                                $shorts['trailer_url'] = $this->common->saveImage($request['trailer'], $this->folder_content, 'shorts_', $storage_type);
                            }
                            $this->common->deleteImageToFolder($this->folder_content, $request['old_trailer'], $request['old_trailer_storage_type']);
                        }
                    } else {
                        if ($request['trailer']) {

                            $shorts['trailer_storage_type'] = $storage_type;
                            if ($storage_type == 1) {
                                $shorts['trailer_url'] = $request['trailer'];
                            } else {
                                $shorts['trailer_url'] = $this->common->saveImage($request['trailer'], $this->folder_content, 'shorts_', $storage_type);
                            }
                            $this->common->deleteImageToFolder($this->folder_content, $request['old_trailer'], $request['old_trailer_storage_type']);
                        } else {
                            $shorts['trailer_url'] = "";
                        }
                    }
                } else {

                    $this->common->deleteImageToFolder($this->folder_content, basename($request['old_trailer']), $request['old_trailer_storage_type']);

                    $shorts['trailer_storage_type'] = $storage_type;
                    $shorts['trailer_url'] = $request['trailer_url'] ?? '';
                }

                $shorts['description'] = $request['description'] ?? "";
                $shorts['is_title'] = $request['is_title'];
                $shorts['is_comment'] = $request['is_comment'];
                $shorts['is_like'] = $request['is_like'];

                if ($shorts->save()) {
                    return response()->json(['status' => 200, 'success' => __('label.success_edit_shorts')]);
                }
            }
            return response()->json(['status' => 400, 'errors' => __('label.error_edit_shorts')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function show($id, $type)
    {
        try {
            $shorts = Shorts::where('id', $id)->first();
            if ($shorts != null) {

                $this->common->deleteImageToFolder($this->folder_content, $shorts['thumbnail'], $shorts['storage_type']);
                $this->common->deleteImageToFolder($this->folder_content, $shorts['trailer_url'], $shorts['trailer_storage_type']);

                $shorts_episode = Shorts_Episode::where('show_id', $shorts['id'])->get();
                foreach ($shorts_episode as $key => $value) {

                    $this->common->deleteImageToFolder($this->folder_content, $value['thumbnail'], $value['storage_type']);
                    $this->common->deleteImageToFolder($this->folder_content, $value['video_320'], $value['video_storage_type']);

                    $value->delete();

                    // Releted Data Delete
                    Video_Watch::where('video_type', $shorts['video_type'])->where('video_id', $id)->where('episode_id', $value->id)->delete();
                    View::where('video_type', $shorts['video_type'])->where('video_id', $id)->where('episode_id', $value->id)->delete();
                }
                $shorts->delete();

                // Releted Data Delete
                Bookmark::where('video_type', $shorts['video_type'])->where('video_id', $id)->delete();
                Comment::where('video_type', $shorts['video_type'])->where('video_id', $id)->delete();
                Like::where('video_type', $shorts['video_type'])->where('video_id', $id)->delete();
                Review::where('video_type', $shorts['video_type'])->where('video_id', $id)->delete();

                return redirect()->route('admin.shorts.index', ['type_id' => $type])->with('success', __('label.content_delete'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function changeStatus(Request $request)
    {
        try {

            $data = Shorts::where('id', $request->id)->first();
            if ($data != null) {

                $data->status = $data->status === 1 ? 0 : 1;
                $data->save();
                return response()->json(['status' => 200, 'success' => __('label.status_changed'), 'id' => $data->id, 'status_code' => $data->status]);
            }
            return redirect()->back()->with('error', __('label.data_not_found'));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    // Short Episode
    public function ShortsIndex(Request $request, $id, $type_id)
    {
        try {
            $params['type'] = Type::where('id', $type_id)->where('status', 1)->first();
            if ($params['type'] == null) {
                return view('errors.404');
            }

            $params['shorts_id'] = $id;
            $params['season'] = Season::orderBy('sort_order', 'asc')->get();
            $params['sortorder_data'] = Shorts_Episode::where('show_id', $id)->where('status', 1)->orderBy('sort_order', 'asc')->get();

            if ($request->ajax()) {
                $input_search = $request['input_search'];
                $input_season = $request['input_season'];

                $query = Shorts_Episode::where('show_id', $id)
                    ->select(['id', 'show_id', 'season_id', 'name', 'thumbnail', 'storage_type', 'video_upload_type', 'video_320', 'video_storage_type', 'is_premium', 'total_view', 'status']);

                if ($input_search) {
                    $query->where('name', 'LIKE', "%{$input_search}%");
                }
                if ($input_season && $input_season != 0) {
                    $query->where('season_id', $input_season);
                }
                $data = $query->with('season')->orderBy('status', 'desc')->orderBy('sort_order', 'asc');

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('thumbnail_img', function ($row) {
                        return $this->common->getImage($this->folder_content, $row->thumbnail, 'portrait', $row->storage_type);
                    })
                    ->addColumn('stats', function ($row) {
                        $views = No_Format($row->total_view ?? 0);
                        return '<div class="vi-stat-row"><i class="fa-solid fa-eye fa-md"></i> ' . $views . '</div>';
                    })
                    ->addColumn('action', function ($row) use ($type_id) {
                        $btn = '<div class="d-flex justify-content-around">';
                        if ($row->video_upload_type === 'server_video' && $row->video_320) {
                            $videoUrl = $this->common->getVideo($this->folder_content, $row->video_320, $row->video_storage_type);
                            $thumbUrl = $this->common->getImage($this->folder_content, $row->thumbnail, 'portrait', $row->storage_type);
                            $btn .= '<button type="button" class="edit-delete-btn mr-2 video" data-toggle="modal" data-target="#videoModal" data-video="' . e($videoUrl) . '" data-image="' . e($thumbUrl) . '"><i class="fa-solid fa-circle-play fa-xl"></i></button>';
                        }
                        $btn .= '<a href="' . route('admin.shorts.episode.edit', ['id' => $row->id, 'type_id' => $type_id]) . '" class="edit-delete-btn mr-2"><i class="fa-solid fa-pen-to-square fa-xl"></i></a>';
                        $btn .= '<button type="button" class="edit-delete-btn" title="' . __('label.delete') . '" onclick="deleteEpisode(\'' . route('admin.shorts.episode.delete', ['shorts_id' => $row->show_id, 'id' => $row->id, 'type_id' => $type_id]) . '\')"><i class="fa-solid fa-trash-can fa-xl"></i></button>';
                        $btn .= '</div>';
                        return $btn;
                    })
                    ->addColumn('name_col', function ($row) {
                        $season = $row->season ? '<br><small class="text-muted">' . e($row->season->name) . '</small>' : '';
                        return '<div style="text-align:left;">' . e($row->name) . $season . '</div>';
                    })
                    ->addColumn('type_badge', function ($row) {
                        $badges = '<div class="vi-td-type">';
                        if ($row->is_premium == 1) {
                            $badges .= '<span class="vi-badge vi-badge-premium"><i class="fa-solid fa-crown fa-md mr-1"></i>' . __('label.premium') . '</span>';
                        } else {
                            $badges .= '<span class="vi-badge vi-badge-free"><i class="fa-solid fa-unlock fa-md mr-1"></i>' . __('label.free') . '</span>';
                        }
                        $badges .= '</div>';
                        return $badges;
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
                    ->rawColumns(['thumbnail_img', 'stats', 'action', 'name_col', 'type_badge', 'status'])
                    ->make(true);
            }

            return view('admin.shorts.ep_index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function ShortsAdd($id, $type_id)
    {
        try {
            $params['type'] = Type::where('id', $type_id)->where('status', 1)->first();
            if ($params['type'] == null) {
                return view('errors.404');
            }

            $params['shorts_id'] = $id;
            $params['season'] = Season::orderBy('sort_order', 'asc')->get();

            return view('admin.shorts.ep_add', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function ShortsSave(Request $request)
    {
        try {
            $rules = [
                'name' => 'required',
                'show_id' => 'required',
                'season_id' => 'required',
                'video_upload_type' => 'required',
                'is_premium' => 'required',
                'is_title' => 'required',
            ];
            if ($request['video_upload_type'] == "server_video") {
                $rules['video'] = 'required';
            } else {
                $rules['video_url'] = 'required';
            }
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()->all()]);
            }

            $storage_type = Storage_Type();

            $insert = new Shorts_Episode();
            $insert['show_id'] = $request['show_id'];
            $insert['season_id'] = $request['season_id'];
            $insert['name'] = $request['name'];

            // Image
            $insert['storage_type'] = $storage_type;
            $insert['thumbnail'] = "";
            $file = $request->file('thumbnail');
            if (isset($file) && $file != null) {
                $insert['thumbnail'] = $this->common->saveImage($file, $this->folder_content, 'shorts_ep_', $insert['storage_type']);
            }
            $insert['description'] = $request['description'] ?? "";

            // Video
            $insert['video_storage_type'] = $storage_type;
            $insert['video_upload_type'] = $request['video_upload_type'];
            if ($insert['video_upload_type'] == "server_video") {

                $video_320 = $request['video'] ?? '';
                if ($insert['video_storage_type'] == 1) {
                    $insert['video_320'] = $video_320;
                } else {
                    $insert['video_320'] = $this->common->saveImage($video_320, $this->folder_content, 'shorts_', $insert['video_storage_type']);
                }
            } else {
                $insert['video_320'] = $request['video_url'] ?? '';
            }
            $insert['video_duration'] = isset($request['video_duration']) ? Time_To_Milliseconds($request['video_duration']) : 0;
            $insert['is_premium'] = $request->is_premium;
            $insert['is_title'] = $request->is_title;
            $insert['total_view'] = 0;
            $insert['sort_order'] = 0;
            $insert['status'] = 1;
            if ($insert->save()) {
                return response()->json(['status' => 200, 'success' => __('label.success_add_episode')]);
            }
            return response()->json(['status' => 400, 'errors' => __('label.error_add_episode')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function ShortsEdit($id, $type_id)
    {
        try {
            $params['type'] = Type::where('id', $type_id)->where('status', 1)->first();
            if ($params['type'] == null) {
                return view('errors.404');
            }

            $params['data'] = Shorts_Episode::where('id', $id)->first();
            if ($params['data'] != null) {

                $params['season'] = Season::orderBy('sort_order', 'asc')->get();
                $params['data']['thumbnail'] = $this->common->getImage($this->folder_content, $params['data']['thumbnail'], 'portrait', $params['data']['storage_type']);

                return view('admin.shorts.ep_edit', $params);
            }
            return redirect()->back()->with('error', __('label.data_not_found'));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function ShortsUpdate(Request $request)
    {
        try {
            $rules = [
                'name' => 'required',
                'show_id' => 'required',
                'season_id' => 'required',
                'video_upload_type' => 'required',
                'is_premium' => 'required',
                'is_title' => 'required',
            ];
            if ($request['video_upload_type'] != "server_video") {
                $rules['video_url'] = 'required';
            }
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()->all()]);
            }

            $episode = Shorts_Episode::where('id', $request['id'])->first();
            if ($episode) {

                $episode['season_id'] = $request['season_id'];
                $episode['name'] = $request['name'];
                $storage_type = Storage_Type();
                $file = $request->file('thumbnail');
                if ($file != null) {
                    $episode['storage_type'] = $storage_type;
                    $episode['thumbnail'] = $this->common->saveImage($file, $this->folder_content, 'shorts_ep_', $storage_type);
                    $this->common->deleteImageToFolder($this->folder_content, basename($request['old_thumbnail']), $request['old_storage_type']);
                }
                $episode['description'] = $request['description'] ?? "";
                $episode['video_upload_type'] = $request['video_upload_type'];
                if ($request['video_upload_type'] == "server_video") {
                    if ($request['video_upload_type'] == $request['old_video_upload_type']) {
                        if ($request['video']) {
                            if ($storage_type == 1) {
                                $episode['video_320'] = $request['video'];
                            } else {
                                $episode['video_320'] = $this->common->saveImage($request['video'], $this->folder_content, 'shorts_', $storage_type);
                            }
                            $this->common->deleteImageToFolder($this->folder_content, $request['old_video_320'], $request['old_video_storage_type']);
                            $episode['video_storage_type'] = $storage_type;
                        }
                    } else {
                        if ($request['video']) {
                            if ($storage_type == 1) {
                                $episode['video_320'] = $request['video'];
                            } else {
                                $episode['video_320'] = $this->common->saveImage($request['video'], $this->folder_content, 'shorts_', $storage_type);
                            }
                            $this->common->deleteImageToFolder($this->folder_content, $request['old_video_320'], $request['old_video_storage_type']);
                        } else {
                            $episode['video_320'] = "";
                        }
                    }
                } else {

                    $this->common->deleteImageToFolder($this->folder_content, $request['old_video_320'], $request['old_video_storage_type']);
                    if ($request['video_url']) {
                        $episode['video_320'] = $request['video_url'];
                    }
                }
                $episode['video_duration'] = isset($request->video_duration) ? Time_To_Milliseconds($request->video_duration) : 0;
                $episode['is_premium'] = $request->is_premium;
                $episode['is_title'] = $request->is_title;
                if ($episode->save()) {
                    return response()->json(['status' => 200, 'success' => __('label.success_edit_episode')]);
                }
            }
            return response()->json(['status' => 400, 'errors' => __('label.error_edit_episode')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function ShortsDelete($show_id, $id, $type_id)
    {
        try {

            $short_ep = Shorts_Episode::where('id', $id)->first();
            if ($short_ep != null) {

                $this->common->deleteImageToFolder($this->folder_content, $short_ep['thumbnail'], $short_ep['storage_type']);
                $this->common->deleteImageToFolder($this->folder_content, $short_ep['video_320'], $short_ep['video_storage_type']);
                $short_ep->delete();

                // Releted Data Delete
                Video_Watch::where('video_id', $show_id)->where('episode_id', $short_ep->id)->delete();
                View::where('video_id', $show_id)->where('episode_id', $short_ep->id)->delete();

                return redirect()->route('admin.shorts.episode.index', ['id' => $show_id, 'type_id' => $type_id])->with('success', __('label.episode_delete'));
            }
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function ShortsEpisodeStatus(Request $request)
    {
        try {
            $data = Shorts_Episode::where('id', $request->id)->first();
            if ($data) {
                $data->status = $data->status === 1 ? 0 : 1;
                $data->save();
                return response()->json(['status' => 200, 'success' => __('label.status_changed'), 'status_code' => $data->status]);
            }
            return response()->json(['status' => 400, 'errors' => __('label.data_not_found')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function ShortsSortable(Request $request)
    {
        try {

            $ids = $request['ids'];
            if (isset($ids) && $ids != null && $ids != "") {

                $id_array = explode(',', $ids);
                for ($i = 0; $i < count($id_array); $i++) {
                    Shorts_Episode::where('id', $id_array[$i])->update(['sort_order' => $i + 1]);
                }
            }
            return response()->json(['status' => 200, 'success' => __('label.sort_order_saved')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
