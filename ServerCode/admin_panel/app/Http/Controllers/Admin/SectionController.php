<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Channel;
use App\Models\Common;
use App\Models\Home_Section;
use App\Models\Language;
use App\Models\Shorts;
use App\Models\TVShow;
use App\Models\Type;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

/*
=> section_type
    1- Manually
    0- Dynamic
    2- AI

=> is_home_screen
    1- Home Screen
    2- Other Screen

=> video_type
    1- Movies
    2- TVShow
    3- Category
    4- Language
    5- Upcoming Content
    6- Channel Content
    7- Kids Content
    8- Shorts

    101- Continue Watching
    102- Channel List
    103- Rent content

=> sub_video_type
    1- Movies
    2- TVShow
*/

class SectionController extends Controller
{
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {

            $params['type'] = Type::where('status', 1)->orderBy('sort_order', 'asc')->latest()->get();
            $params['category'] = Category::where('status', 1)->orderby('sort_order', 'asc')->latest()->get();
            $params['language'] = Language::where('status', 1)->orderby('sort_order', 'asc')->latest()->get();
            $params['channel'] = Channel::where('status', 1)->latest()->get();

            return view('admin.section.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'is_home_screen' => 'required',
                'top_type_id' => 'required',
                'title' => 'required',
                'screen_layout' => 'required',
                'top_type_type' => 'required',
                'no_of_content'  => 'required|integer|min:1',
                'view_all' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()->all()]);
            }

            $requestData = $request->all();
            $requestData['short_title'] = $request['short_title'] ?? '';
            $requestData['sort_order'] = 0;
            $requestData['is_title'] = 0;
            $requestData['status'] = 1;
            if ($requestData['is_home_screen'] == 1) {
                $validator1 = Validator::make($request->all(), [
                    'video_type' => 'required',
                ]);
                if ($validator1->fails()) {
                    return response()->json(['status' => 400, 'errors' => $validator1->errors()->all()]);
                }
                $requestData['video_type'] = $requestData['video_type'];
            } else {
                $requestData['video_type'] = $requestData['top_type_type'];
            }
            if ($requestData['video_type'] == 5 || $requestData['video_type'] == 6 || $requestData['video_type'] == 7) {
                $validator2 = Validator::make($request->all(), [
                    'sub_video_type' => 'required',
                ]);
                if ($validator2->fails()) {
                    return response()->json(['status' => 400, 'errors' => $validator2->errors()->all()]);
                }
                $requestData['sub_video_type'] = $requestData['sub_video_type'];
            } else {
                $requestData['sub_video_type'] = $request['sub_video_type'] != null ? $request['sub_video_type'] : 0;
            }
            if ($requestData['is_home_screen'] == 1) {
                if (in_array($requestData['video_type'], [1, 2, 5, 6, 7, 8, 103])) {

                    $validator3 = Validator::make($request->all(), [
                        'type_id' => 'required',
                    ]);
                    if ($validator3->fails()) {
                        return response()->json(['status' => 400, 'errors' => $validator3->errors()->all()]);
                    }
                    $requestData['type_id'] = $requestData['type_id'];
                } else {
                    $requestData['type_id'] = 0;
                }
            } else if ($requestData['is_home_screen'] == 2) {
                $requestData['type_id'] = $requestData['top_type_id'];
            } else {
                $requestData['type_id'] = 0;
            }
            if ($requestData['section_type'] == 1) {

                $validator4 = Validator::make($request->all(), [
                    'content' => 'required',
                ]);
                if ($validator4->fails()) {
                    return response()->json(['status' => 400, 'errors' => $validator4->errors()->all()]);
                }
                $requestData['content_ids'] = $request['content'] != null ? implode(',', $request['content']) : "";
            } else {
                $requestData['content_ids'] = "";
            }

            $requestData['category_id'] = 0;
            $requestData['language_id'] = 0;
            $requestData['channel_id'] = 0;
            $requestData['order_by_upload'] = 0;
            $requestData['order_by_view'] = 0;
            $requestData['premium_video'] = 0;
            $requestData['no_of_content'] = 0;
            $requestData['view_all'] = 0;

            $request['no_of_content'] = $request['no_of_content'] == 0 ? 1 : $request['no_of_content'];
            $requestData['no_of_content'] = $request['no_of_content'] ?? 1;

            if (in_array($requestData['video_type'], [1, 2, 5, 7, 8])) {

                $requestData['category_id'] = $request['category_id'] ?? 0;
                $requestData['language_id'] = $request['language_id'] ?? 0;
                $requestData['order_by_upload'] = $request['order_by_upload'] ?? 0;
                $requestData['order_by_view'] = $request['order_by_view'] ?? 0;
                if ($requestData['video_type'] != 2) {
                    $requestData['premium_video'] = $request['premium_video'] ?? 0;
                }
                $requestData['view_all'] = $request['view_all'] ?? 0;
            } else if (in_array($requestData['video_type'], [3, 4, 101, 102])) {

                $requestData['view_all'] = $request['view_all'] ?? 0;
            } else if (in_array($requestData['video_type'], [6, 103])) {

                $requestData['category_id'] = $request['category_id'] ?? 0;
                $requestData['language_id'] = $request['language_id'] ?? 0;
                $requestData['channel_id'] = $request['channel_id'] ?? 0;
                $requestData['order_by_upload'] = $request['order_by_upload'] ?? 0;
                $requestData['order_by_view'] = $request['order_by_view'] ?? 0;
                $requestData['premium_video'] = $request['premium_video'] ?? 0;
                $requestData['view_all'] = $request['view_all'] ?? 0;
            }
            unset($requestData['top_type_id'], $requestData['top_type_type'],  $requestData['content']);

            $data = Home_Section::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($data->id)) {
                return response()->json(['status' => 200, 'success' => __('label.success_add_section')]);
            }
            return response()->json(['status' => 400, 'errors' => __('label.error_add_section')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function GetSectionData(Request $request)
    {
        try {

            $query = Home_Section::where('is_home_screen', $request['is_home_screen']);
            if ($request['is_home_screen'] != 1) {
                $query->where('type_id', $request['top_type_id']);
            }
            $data = $query->orderBy('status', 'desc')->orderBy('sort_order', 'asc')->latest()->get();

            foreach ($data as $value) {

                $type = Type::find($value->type_id);
                $value['type_name'] = $type->name ?? "";

                if ($value->section_type == 1) {

                    $contentIds = explode(',', $value->content_ids);
                    $contentNames = [];

                    foreach ($contentIds as $id) {

                        if ($value->video_type == 1) { // Movies
                            $contentNames[] = optional(Video::find($id))->name;
                        } elseif ($value->video_type == 2) { // TVShow
                            $contentNames[] = optional(TVShow::find($id))->name;
                        } elseif ($value->video_type == 3) { // Category
                            $contentNames[] = optional(Category::find($id))->name;
                        } elseif ($value->video_type == 4) { // Language
                            $contentNames[] = optional(Language::find($id))->name;
                        } elseif (in_array($value->video_type, [5, 6, 7])) { // Upcoming Content, Channel Content, Kids Content
                            $contentNames[] = $value->sub_video_type == 1
                                ? optional(Video::find($id))->name
                                : optional(TVShow::find($id))->name;
                        } elseif ($value->video_type == 8) { // Shorts
                            $contentNames[] = optional(Shorts::find($id))->name;
                        } elseif ($value->video_type == 102) { // Channel List
                            $contentNames[] = optional(Channel::find($id))->name;
                        } elseif ($value->video_type == 103) { // Rent content

                            if ($type->type == 1) {
                                $contentNames[] = optional(Video::find($id))->name;
                            } elseif ($type->type == 2) {
                                $contentNames[] = optional(TVShow::find($id))->name;
                            } elseif (in_array($type->type, [6, 7])) {
                                $contentNames[] = $value->sub_video_type == 1
                                    ? optional(Video::find($id))->name
                                    : optional(TVShow::find($id))->name;
                            }
                        }
                    }

                    $value['content'] = array_filter($contentNames);
                }
            }
            return response()->json(['status' => 200, 'result' => $data]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function SectionDataEdit(Request $request)
    {
        try {

            $data = Home_Section::where('id', $request['id'])->first();
            if ($data['section_type'] == 1) {

                $data['content'] = $data['content_ids'] != null ? explode(',', $data['content_ids']) : [];
                $content_data = [];

                $type = Type::where('id', $data['type_id'])->first();

                if ($data['video_type'] == 1) {

                    $query = Video::where('type_id', $data['type_id'])->where('status', 1);
                } elseif ($data['video_type'] == 2) {

                    $query = TVShow::where('type_id', $data['type_id'])->where('status', 1);
                } elseif ($data['video_type'] == 3) {

                    $query = Category::where('status', 1)->orderBy('sort_order', 'asc');
                } elseif ($data['video_type'] == 4) {

                    $query = Language::where('status', 1)->orderBy('sort_order', 'asc');
                } elseif ($data['video_type'] == 5 || $data['video_type'] == 6 || $data['video_type'] == 7) {

                    if ($data['sub_video_type'] == 1) {
                        $query = Video::where('type_id', $data['type_id'])->where('status', 1);
                    } else if ($data['sub_video_type'] == 2) {
                        $query = TVShow::where('type_id', $data['type_id'])->where('status', 1);
                    }
                    if ($data['video_type'] == 6 && $data['channel_id'] != 0) {
                        $query->where('channel_id', $data['channel_id']);
                    }
                } else if ($data['video_type'] == 8) {
                    $query = Shorts::where('type_id', $data['type_id'])->where('status', 1);
                } else if ($data['video_type'] == 102) {
                    $query = Channel::where('status', 1);
                } else if ($data['video_type'] == 103) {

                    if ($type['type'] == 1) {
                        $query = Video::where('status', 1);
                    } else if ($type['type'] == 2) {
                        $query = TVShow::where('status', 1);
                    }
                    if ($type['type'] == 6 || $type['type'] == 7) {

                        if ($data['sub_video_type'] == 1) {
                            $query = Video::where('status', 1);
                        } else if ($data['sub_video_type'] == 2) {
                            $query = TVShow::where('status', 1);
                        }
                        if ($type['type'] == 6 && $data['channel_id'] != 0) {
                            $query->where('channel_id', $data['channel_id']);
                        }
                    }
                    $query->where('type_id', $data['type_id'])->where('is_rent', 1);
                }

                if (isset($query)) {
                    $content_data = $query->latest()->get();
                }
                $data['content_data'] = $content_data;
            }
            return response()->json(['status' => 200, 'result' => $data]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function update($id, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'is_home_screen' => 'required',
                'title' => 'required',
                'screen_layout' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()->all()]);
            }

            $requestData = $request->all();
            $requestData['short_title'] = $request['short_title'] ?? '';
            if ($requestData['is_home_screen'] == 1) {
                $validator1 = Validator::make($request->all(), [
                    'video_type' => 'required',
                ]);
                if ($validator1->fails()) {
                    return response()->json(['status' => 400, 'errors' => $validator1->errors()->all()]);
                }
                $requestData['video_type'] = $requestData['video_type'];
            } else {
                $requestData['video_type'] = $requestData['top_type_type'];
            }
            if ($requestData['is_home_screen'] == 1) {
                if (in_array($requestData['video_type'], [1, 2, 5, 6, 7, 8, 103])) {

                    $validator3 = Validator::make($request->all(), [
                        'type_id' => 'required',
                    ]);
                    if ($validator3->fails()) {
                        return response()->json(['status' => 400, 'errors' => $validator3->errors()->all()]);
                    }
                    $requestData['type_id'] = $requestData['type_id'];
                } else {
                    $requestData['type_id'] = 0;
                }
            } else if ($requestData['is_home_screen'] == 2) {
                $requestData['type_id'] = $requestData['top_type_id'];
            } else {
                $requestData['type_id'] = 0;
            }
            if (in_array($requestData['video_type'], [5, 6, 7]) || ($requestData['video_type'] == 103 && in_array(Type::where('id', $requestData['type_id'])->value('type'), [6, 7]))) {
                $validator2 = Validator::make($request->all(), [
                    'sub_video_type' => 'required',
                ]);
                if ($validator2->fails()) {
                    return response()->json(['status' => 400, 'errors' => $validator2->errors()->all()]);
                }
                $requestData['sub_video_type'] = $requestData['sub_video_type'];
            } else {
                $requestData['sub_video_type'] = $request['sub_video_type'] ?? 0;
            }
            if ($requestData['section_type'] == 1) {

                $validator4 = Validator::make($request->all(), [
                    'content' => 'required',
                ]);
                if ($validator4->fails()) {
                    return response()->json(['status' => 400, 'errors' => $validator4->errors()->all()]);
                }
                $requestData['content_ids'] = $request['content'] != null ? implode(',', $request['content']) : "";
            } else {
                $requestData['content_ids'] = "";
            }

            $requestData['category_id'] = 0;
            $requestData['language_id'] = 0;
            $requestData['channel_id'] = 0;
            $requestData['order_by_upload'] = 0;
            $requestData['order_by_view'] = 0;
            $requestData['premium_video'] = 0;
            $requestData['no_of_content'] = 0;
            $requestData['view_all'] = 0;
            $request['no_of_content'] = $request['no_of_content'] <= 0 ? 1 : $request['no_of_content'];
            $requestData['no_of_content'] = $request['no_of_content'] ?? 0;

            if (in_array($requestData['video_type'], [1, 2, 5, 7, 8])) {

                $requestData['category_id'] = $request['category_id'] ?? 0;
                $requestData['language_id'] = $request['language_id'] ?? 0;
                $requestData['order_by_upload'] = $request['order_by_upload'] ?? 0;
                $requestData['order_by_view'] = $request['order_by_view'] ?? 0;
                if ($requestData['video_type'] != 2) {
                    $requestData['premium_video'] = $request['premium_video'] ?? 0;
                }
                $requestData['view_all'] = $request['view_all'] ?? 0;
            } else if (in_array($requestData['video_type'], [3, 4, 101, 102])) {

                $requestData['view_all'] = $request['view_all'] ?? 0;
            } else if (in_array($requestData['video_type'], [6, 103])) {

                $requestData['category_id'] = $request['category_id'] ?? 0;
                $requestData['language_id'] = $request['language_id'] ?? 0;
                $requestData['channel_id'] = $request['channel_id'] ?? 0;
                $requestData['order_by_upload'] = $request['order_by_upload'] ?? 0;
                $requestData['order_by_view'] = $request['order_by_view'] ?? 0;
                $requestData['premium_video'] = $request['premium_video'] ?? 0;
                $requestData['view_all'] = $request['view_all'] ?? 0;
            }
            unset($requestData['content'], $requestData['top_type_type'], $requestData['top_type_id']);

            $data = Home_Section::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($data->id)) {
                return response()->json(['status' => 200, 'success' => __('label.success_edit_section')]);
            }
            return response()->json(['status' => 400, 'errors' => __('label.error_edit_section')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function show($id)
    {
        try {

            Home_Section::where('id', $id)->delete();
            return response()->json(['status' => 200, 'success' => __('label.section_delete')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function changeStatus(Request $request)
    {
        try {

            $data = Home_Section::where('id', $request->id)->first();
            $data['status'] = $data['status'] === 1 ? 0 : 1;
            $data->save();
            return response()->json(['status' => 200, 'success' => __('label.status_changed'), 'status_code' => $data['status']]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    // Sortable
    public function SectionSortable(Request $request)
    {
        try {

            $query = Home_Section::select('id', 'title')->where('is_home_screen', $request['is_home_screen'])->where('status', 1)->orderBy('sort_order', 'asc')->latest();
            if ($request['is_home_screen'] != 1) {
                $query->where('type_id', $request['top_type_id']);
            }
            $data = $query->get();

            return response()->json(['status' => 200, 'result' => $data]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function SectionSortableSave(Request $request)
    {
        try {

            $ids = $request['ids'];
            if (isset($ids) && $ids != null && $ids != "") {

                $id_array = explode(',', $ids);
                for ($i = 0; $i < count($id_array); $i++) {
                    Home_Section::where('id', $id_array[$i])->update(['sort_order' => $i + 1]);
                }
            }
            return response()->json(['status' => 200, 'success' => __('label.sort_order_saved')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function get_content(Request $request)
    {
        try {

            $video_type = $request['video_type'];
            $type_id = $request['type_id'];
            $sub_video_type = $request['sub_video_type'];
            $channel_id = $request['channel_id'];
            $type_type = $request['type_type'];

            if ($video_type == 1) {
                $query = Video::where('type_id', $type_id)->where('status', 1);
            } elseif ($video_type == 2) {
                $query = TVShow::where('type_id', $type_id)->where('status', 1);
            } elseif ($video_type == 3) {
                $query = Category::where('status', 1)->orderBy('sort_order', 'asc');
            } elseif ($video_type == 4) {
                $query = Language::where('status', 1)->orderBy('sort_order', 'asc');
            } elseif ($video_type == 5 || $video_type == 6 || $video_type == 7) {

                if ($sub_video_type == 1) {
                    $query = Video::where('type_id', $type_id)->where('status', 1);
                } else if ($sub_video_type == 2) {
                    $query = TVShow::where('type_id', $type_id)->where('status', 1);
                }
                if ($video_type == 6 && $channel_id != 0) {
                    $query->where('channel_id', $channel_id);
                }
            } elseif ($video_type == 8) {
                $query = Shorts::where('type_id', $type_id)->where('status', 1);
            }
            if ($video_type == 102) {
                $query = Channel::where('status', 1);
            } else if ($video_type == 103) {

                if ($type_type == 1) {
                    $query = Video::query();
                } else if ($type_type == 2) {
                    $query = TVShow::query();
                } else if ($type_type == 6 || $type_type == 7) {

                    if ($sub_video_type == 1) {
                        $query = Video::query();
                    } else if ($sub_video_type == 2) {
                        $query = TVShow::query();
                    }
                    if ($type_type == 6 && $channel_id != 0) {
                        $query->where('channel_id', $channel_id);
                    }
                }
                $query->where('type_id', $type_id)->where('is_rent', 1)->where('status', 1);
            }

            $data = [];
            if (isset($query)) {
                $data = $query->latest()->get();
            }
            return response()->json(['status' => 200, 'result' => $data]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
