<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Common;
use App\Models\General_Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\URL;

class PageController extends Controller
{
    private $folder_app = "app";
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {
            $params['setting_data'] = Setting_Data();

            if ($request->ajax()) {

                $query = Page::query();

                $input_search = $request['input_search'];
                if ($input_search != null) {
                    $query->where('title', 'LIKE', "%{$input_search}%");
                }
                $data = $query->orderby('status', 'desc')->latest()->get();

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('icon', function ($row) {
                        return $this->common->getImage($this->folder_app, $row['icon'], 'normal', $row['storage_type']);
                    })
                    ->addColumn('action', function ($row) {

                        $delete = '<form class="delete-form" method="POST" action="' . route('admin.page.destroy', [$row->id]) . '">
                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="edit-delete-btn"><i class="fa-solid fa-trash-can fa-xl"></i></button></form>';

                        $btn = '<div class="d-flex justify-content-around">';
                        $btn .= '<a href="' . route('page.view', [$row->title]) . '" class="edit-delete-btn mr-2" target="_blank">';
                        $btn .= '<i class="fa-regular fa-eye fa-xl"></i>';
                        $btn .= '</a>';
                        $btn .= '<a href="' . route('admin.page.edit', [$row->id]) . '" class="edit-delete-btn mr-2">';
                        $btn .= '<i class="fa-solid fa-pen-to-square fa-xl"></i>';
                        $btn .= '</a>';
                        $btn .= $delete;
                        $btn .= '</div>';
                        return $btn;
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
                    ->rawColumns(['action', 'status'])
                    ->make(true);
            }
            return view('admin.page.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function create()
    {
        try {
            $params['settings'] = Setting_Data();
            return view('admin.page.add', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required',
                'page_subtitle' => 'required',
                'icon' => 'required|image|mimes:jpeg,png,jpg,webp',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()->all()]);
            }

            $page = new Page();
            $page->title = $request->title;
            $page->description = $request->description;
            $page->page_subtitle = $request->page_subtitle;
            $page->storage_type = Storage_Type();
            $page->icon = $this->common->saveImage($request->icon, $this->folder_app, 'pages_',  $page->storage_type);
            $page->status = 1;
            if ($page->save()) {
                return response()->json(['status' => 200, 'success' => __('label.success_add_page')]);
            }
            return response()->json(['status' => 400, 'errors' => __('label.error_add_page')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function edit($id)
    {
        try {
            $params['data'] = Page::where('id', $id)->first();
            if ($params['data']) {

                $params['settings'] = Setting_Data();
                $params['data']['icon'] = $this->common->getImage($this->folder_app, $params['data']['icon'], 'normal', $params['data']['storage_type']);

                return view('admin.page.edit', $params);
            }
            return redirect()->back()->with('error', __('label.data_not_found'));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required',
                'description' => 'required',
                'page_subtitle' => 'required',
                'icon' => 'image|mimes:jpeg,png,jpg,webp',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()->all()]);
            }

            $page = Page::where('id', $request->id)->first();
            if (isset($page->id)) {

                $page->title = $request->title;
                $page->description = $request->description;
                $page->page_subtitle = $request->page_subtitle;
                $page->status = 1;

                if (isset($request->icon)) {
                    $page->storage_type = Storage_Type();
                    $page->icon = $this->common->saveImage($request->icon, $this->folder_app, 'pages_',  $page->storage_type);

                    $this->common->deleteImageToFolder($this->folder_app, basename($request->old_icon), $request->old_storage_type);
                }

                if ($page->save()) {
                    return response()->json(['status' => 200, 'success' => __('label.success_edit_page')]);
                }
            }
            return response()->json(['status' => 400, 'errors' => __('label.error_edit_page')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function destroy($id)
    {
        try {
            $data = Page::where('id', $id)->first();
            if ($data != null) {
                $this->common->deleteImageToFolder($this->folder_app, $data['icon'], $data['storage_type']);
                $data->delete();
            }
            return redirect()->route('admin.page.index')->with('success', __('label.page_delete'));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function show($id)
    {
        try {

            $data = Page::where('id', $id)->first();
            if (isset($data)) {

                $data->status = $data->status === 1 ? 0 : 1;
                $data->save();
                return response()->json(['status' => 200, 'success' => __('label.status_changed'), 'status_code' => $data->status]);
            }
            return response()->json(['status' => 400, 'errors' => __('label.data_not_found')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function PageSettingSave(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'background_color' => 'required',
                'title_color' => 'required',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()->all()]);
            }

            $data = $request->all();
            $data["page_background_color"] = isset($data['background_color']) ? $data['background_color'] : '';
            $data["page_title_color"] = isset($data['title_color']) ? $data['title_color'] : '';

            foreach ($data as $key => $value) {
                $setting = General_Setting::where('key', $key)->first();
                if (isset($setting->id)) {
                    $setting->value = $value;
                    $setting->save();
                }
            }
            return response()->json(['status' => 200, 'success' => __('label.data_edit_successfully')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function page_view($type)
    {
        try {
            $currentURL = URL::current();

            $link_array = explode('/', $currentURL);
            $page = urldecode(end($link_array));

            $params['result'] = Page::where('title', $page)->first();
            if (isset($params['result'])) {

                $params['settings'] = Setting_Data();

                return view('page', $params);
            }
            return view('errors.404');
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
