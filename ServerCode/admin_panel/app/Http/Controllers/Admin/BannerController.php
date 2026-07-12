<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Common;
use App\Models\Shorts;
use App\Models\TVShow;
use App\Models\Type;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class BannerController extends Controller
{
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index()
    {
        try {

            $params['type'] = Type::where('status', 1)->orderBy('sort_order', 'asc')->get();
            $params['video'] = [];
            if ($params['type'] != null && count($params['type']) > 0) {

                if ($params['type'][0]['type'] == 1) {
                    $params['video'] = Video::where('type_id', $params['type'][0]['id'])->where('status', 1)->get();
                } else if ($params['type'][0]['type'] == 2) {
                    $params['video'] = TVShow::where('type_id', $params['type'][0]['id'])->where('status', 1)->get();
                } else if ($params['type'][0]['type'] == 8) {
                    $params['video'] = Shorts::where('type_id', $params['type'][0]['id'])->where('status', 1)->get();
                }
            }
            return view('admin.banner.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'is_home_screen' => 'required',
                'type_id' => 'required',
                'video_type' => 'required',
                'video_id' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()->all()]);
            }

            $banner = new Banner();
            $banner['is_home_screen'] = $request['is_home_screen'];
            $banner['type_id'] = $request['type_id'];
            $banner['video_type'] = $request['video_type'];
            $banner['subvideo_type'] = $request['subvideo_type'] ?? 0;
            $banner['video_id'] = $request['video_id'];
            $banner['sort_order'] = 0;
            $banner['status'] = 1;
            if ($banner->save()) {
                return response()->json(['status' => 200, 'success' => __('label.success_add_banner')]);
            }
            return response()->json(['status' => 400, 'errors' => __('label.error_add_banner')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function TypeByData(Request $request)
    {
        try {

            $existing_ids = Banner::where('type_id', $request['type_id'])->pluck('video_id')->toArray();

            $data = [];
            if ($request['type'] == 1) {
                $data = Video::select('id', 'name')->where('type_id', $request['type_id'])->where('status', 1)->whereNotIn('id', $existing_ids)->get();
            } else if ($request['type'] == 2) {
                $data = TVShow::select('id', 'name')->where('type_id', $request['type_id'])->where('status', 1)->whereNotIn('id', $existing_ids)->get();
            } else if ($request['type'] == 5 || $request['type'] == 6 || $request['type'] == 7) {
                if ($request['subvideo_type'] == 1) {
                    $data = Video::select('id', 'name')->where('type_id', $request['type_id'])->where('status', 1)->whereNotIn('id', $existing_ids)->get();
                } else if ($request['subvideo_type'] == 2) {
                    $data = TVShow::select('id', 'name')->where('type_id', $request['type_id'])->where('status', 1)->whereNotIn('id', $existing_ids)->get();
                }
            } else if ($request['type'] == 8) {
                $data = Shorts::select('id', 'name')->where('type_id', $request['type_id'])->where('status', 1)->whereNotIn('id', $existing_ids)->get();
            }

            return response()->json(['status' => 200, 'result' => $data]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function BannerList(Request $request)
    {
        try {

            if ($request['is_home_screen'] == 1) {

                $data = Banner::where('is_home_screen', $request['is_home_screen'])->with('type')->orderBy('sort_order', 'asc')->latest()->get();
                for ($i = 0; $i < count($data); $i++) {

                    if ($data[$i]['video_type'] == 1) {
                        $data[$i]['video'] = Video::select('id', 'name')->where('id', $data[$i]['video_id'])->first();
                    } else if ($data[$i]['video_type'] == 2) {
                        $data[$i]['video'] = TVShow::select('id', 'name')->where('id', $data[$i]['video_id'])->first();
                    } else if ($data[$i]['video_type'] == 5 || $data[$i]['video_type'] == 6 || $data[$i]['video_type'] == 7) {

                        if ($data[$i]['subvideo_type'] == 1) {
                            $data[$i]['video'] = Video::select('id', 'name')->where('id', $data[$i]['video_id'])->first();
                        } else if ($data[$i]['subvideo_type'] == 2) {
                            $data[$i]['video'] = TVShow::select('id', 'name')->where('id', $data[$i]['video_id'])->first();
                        }
                    } else if ($data[$i]['video_type'] == 8) {
                        $data[$i]['video'] = Shorts::select('id', 'name')->where('id', $data[$i]['video_id'])->first();
                    }
                }
            } else {

                $data = Banner::where('type_id', $request['type_id'])->where('is_home_screen', $request['is_home_screen'])->with('type')->orderBy('sort_order', 'asc')->latest()->get();
                for ($i = 0; $i < count($data); $i++) {

                    if ($data[$i]['video_type'] == 1) {
                        $data[$i]['video'] = Video::select('id', 'name')->where('id', $data[$i]['video_id'])->first();
                    } else if ($data[$i]['video_type'] == 2) {
                        $data[$i]['video'] = TVShow::select('id', 'name')->where('id', $data[$i]['video_id'])->first();
                    } else if ($data[$i]['video_type'] == 5 || $data[$i]['video_type'] == 6 || $data[$i]['video_type'] == 7) {

                        if ($data[$i]['subvideo_type'] == 1) {
                            $data[$i]['video'] = Video::select('id', 'name')->where('id', $data[$i]['video_id'])->first();
                        } else if ($data[$i]['subvideo_type'] == 2) {
                            $data[$i]['video'] = TVShow::select('id', 'name')->where('id', $data[$i]['video_id'])->first();
                        }
                    } else if ($data[$i]['video_type'] == 8) {
                        $data[$i]['video'] = Shorts::select('id', 'name')->where('id', $data[$i]['video_id'])->first();
                    }
                }
            }
            return response()->json(['status' => 200, 'result' => $data]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function destroy($id)
    {
        try {

            Banner::where('id', $id)->delete();
            return response()->json(['status' => 200, 'success' => __('label.banner_delete')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    // Sortable
    public function BannerSortableSave(Request $request)
    {
        try {

            $ids = $request['ids'];
            if (isset($ids) && $ids != null && $ids != "") {

                $id_array = explode(',', $ids);
                for ($i = 0; $i < count($id_array); $i++) {
                    Banner::where('id', $id_array[$i])->update(['sort_order' => $i + 1]);
                }
            }
            return response()->json(['status' => 200, 'success' => __('label.sort_order_saved')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
