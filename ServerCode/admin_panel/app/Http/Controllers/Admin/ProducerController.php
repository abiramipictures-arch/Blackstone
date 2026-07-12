<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Producer;
use App\Models\Shorts;
use App\Models\TVShow;
use App\Models\Video;
use Illuminate\Support\Facades\Validator;
use Exception;
use Illuminate\Support\Facades\Hash;

class ProducerController extends Controller
{
    private $folder = "producer";
    private $folder_content = "content";
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {
            $params['data'] = [];
            if ($request->ajax()) {
                $input_search = $request['input_search'];
                $input_type   = $request['input_type'];

                $query = Producer::query();
                if ($input_search) {
                    $query->where(function ($q) use ($input_search) {
                        $q->where('full_name', 'LIKE', "%{$input_search}%")
                            ->orWhere('user_name', 'LIKE', "%{$input_search}%")
                            ->orWhere('email', 'LIKE', "%{$input_search}%")
                            ->orWhere('mobile_number', 'LIKE', "%{$input_search}%");
                    });
                }
                if ($input_type == 'today') {
                    $query->whereDay('created_at', date('d'))->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'));
                } elseif ($input_type == 'month') {
                    $query->whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'));
                } elseif ($input_type == 'year') {
                    $query->whereYear('created_at', date('Y'));
                }
                $data = $query->orderBy('status', 'desc')->latest();

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('image', function ($row) {
                        return $this->common->getImage($this->folder, $row['image'], 'profile', $row['storage_type']);
                    })
                    ->addColumn('content', function ($row) {
                        $movies  = Video::where('producer_id', $row->id)->count();
                        $tvshows = TVShow::where('producer_id', $row->id)->count();
                        $shorts  = Shorts::where('producer_id', $row->id)->count();
                        $moviesUrl = route('admin.producer.content', ['producer_id' => $row->id, 'content_type' => 1]);
                        $tvshowUrl = route('admin.producer.content', ['producer_id' => $row->id, 'content_type' => 2]);
                        $shortsUrl = route('admin.producer.content', ['producer_id' => $row->id, 'content_type' => 3]);
                        return "<div class='d-flex'>
                            <a href='{$moviesUrl}' class='prod-cnt-btn mr-3'><i class='fa-solid fa-video fa-xl mr-2'></i>{$movies}</a>
                            <a href='{$tvshowUrl}' class='prod-cnt-btn mr-3'><i class='fa-solid fa-tv fa-xl mr-2'></i>{$tvshows}</a>
                            <a href='{$shortsUrl}' class='prod-cnt-btn mr-3'><i class='fa-solid fa-bolt fa-xl mr-2'></i>{$shorts}</a>
                        </div>";
                    })
                    ->addColumn('action', function ($row) {

                        return '<div class="d-flex justify-content-around">
                            <a href="' . route('admin.producer.edit', $row->id) . '" class="edit-delete-btn mr-2">
                                <i class="fa-solid fa-pen-to-square fa-xl"></i>
                            </a>

                            <form class="delete-form" method="POST" action="' . route('admin.producer.destroy', $row->id) . '">
                                ' . csrf_field() . '
                                ' . method_field('DELETE') . '
                                <button type="submit" class="edit-delete-btn"><i class="fa-solid fa-trash-can fa-xl"></i></button>
                            </form>
                        </div>';
                    })
                    ->addColumn('status', function ($row) {
                        if ($row->status == 1) {
                            return "<div class='d-flex flex-column align-items-center' style='gap:4px;'>
                                <label id='$row->id' class='status-toggle status-on' onclick='change_status($row->id)'><span class='status-toggle-track'><span class='status-toggle-thumb'></span></span></label>
                                <small id='text_$row->id' class='font-weight-bold text-success'>" . __('label.active') . "</small>
                            </div>";
                        }
                        return "<div class='d-flex flex-column align-items-center' style='gap:4px;'>
                            <label id='$row->id' class='status-toggle status-off' onclick='change_status($row->id)'><span class='status-toggle-track'><span class='status-toggle-thumb'></span></span></label>
                            <small id='text_$row->id' class='font-weight-bold text-danger'>" . __('label.inactive') . "</small>
                        </div>";
                    })
                    ->addColumn('date', function ($row) {
                        return date("Y-m-d", strtotime($row->created_at));
                    })
                    ->rawColumns(['action', 'status', 'image', 'content'])
                    ->make(true);
            }
            return view('admin.producer.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function create()
    {
        try {
            $params['data'] = [];
            return view('admin.producer.add', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_name' => 'required|min:2|unique:tbl_producer,user_name',
                'full_name' => 'required|min:2',
                'email' => 'required|unique:tbl_producer,email|email',
                'password' => 'required|min:4',
                'mobile_number' => 'required|numeric|unique:tbl_producer,mobile_number',
                'image' => 'image|mimes:jpeg,png,jpg,webp',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()->all()]);
            }

            $requestData = $request->all();
            $requestData['storage_type'] = Storage_Type();
            $requestData['password'] = Hash::make($requestData['password']);
            if (isset($requestData['image'])) {
                $requestData['image'] = $this->common->saveImage($requestData['image'], $this->folder, 'prod_', $requestData['storage_type']);
            } else {
                $requestData['image'] = '';
            }
            $requestData['wallet'] = 0;
            $requestData['status'] = 1;

            $data = Producer::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($data->id)) {
                return response()->json(['status' => 200, 'success' => __('label.success_add_producer')]);
            }
            return response()->json(['status' => 400, 'errors' => __('label.error_add_producer')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function edit($id)
    {
        try {

            $params['data'] = Producer::where('id', $id)->first();
            if ($params['data'] != null) {

                $params['data']['image'] = $this->common->getImage($this->folder, $params['data']['image'], 'profile', $params['data']['storage_type']);

                // Demo Mode Masking
                $mode = Demo_Mode();
                if ($mode == 0) {

                    // Mask Email
                    if (!empty($params['data']['email'])) {
                        $emailParts = explode('@', $params['data']['email']);
                        $username = substr($emailParts[0], 0, 1) . '******';
                        $params['data']['email'] = $username . '@' . ($emailParts[1] ?? '');
                    }
                    // Mask Mobile
                    if (!empty($params['data']['mobile_number'])) {
                        $params['data']['mobile_number'] = '******' . substr($params['data']['mobile_number'], -4);
                    }
                }
                return view('admin.producer.edit', $params);
            }
            return redirect()->back()->with('error', __('label.data_not_found'));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function update($id, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_name' => 'required|min:2|unique:tbl_producer,user_name,' . $id,
                'full_name' => 'required|min:2',
                'email' => 'required|email|unique:tbl_producer,email,' . $id,
                'mobile_number' => 'required|numeric|unique:tbl_producer,mobile_number,' . $id,
                'image' => 'image|mimes:jpeg,png,jpg,webp',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()->all()]);
            }

            $requestData = $request->all();

            if (isset($requestData['password'])) {
                $requestData['password'] = Hash::make($requestData['password']);
            } else {
                unset($requestData['password']);
            }

            if (isset($requestData['image'])) {

                $requestData['storage_type'] = Storage_Type();
                $requestData['image'] = $this->common->saveImage($requestData['image'], $this->folder, 'prod_', $requestData['storage_type']);

                $this->common->deleteImageToFolder($this->folder, basename($requestData['old_image']), $request['old_storage_type']);
            }
            unset($requestData['old_image'], $requestData['old_storage_type']);

            $data = Producer::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($data->id)) {
                return response()->json(['status' => 200, 'success' => __('label.success_edit_producer')]);
            }
            return response()->json(['status' => 400, 'errors' => __('label.error_edit_producer')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function destroy($id)
    {
        try {

            $data = Producer::where('id', $id)->first();
            if (isset($data)) {
                $this->common->deleteImageToFolder($this->folder, $data['image'], $data['storage_type']);
                $data->delete();
            }
            return redirect()->route('admin.producer.index')->with('success', __('label.producer_delete'));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function show($id)
    {
        try {

            $data = Producer::where('id', $id)->first();
            if ($data) {

                $data['status'] = $data['status'] === 1 ? 0 : 1;
                $data->save();
                return response()->json(['status' => 200, 'success' => __('label.status_changed'), 'status_code' => $data['status']]);
            }
            return response()->json(['status' => 400, 'errors' => __('label.data_not_found')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    // Content
    public function content_index(Request $request, $producer_id, $content_type)
    {
        try {

            $params['producer_id']       = $producer_id;
            $params['content_type']      = $content_type;
            $params['videos_count']      = Video::where('producer_id', $producer_id)->count();
            $params['tvshows_count']     = TVShow::where('producer_id', $producer_id)->count();
            $params['shorts_count']      = Shorts::where('producer_id', $producer_id)->count();
            $params['producer']          = Producer::where('id', $producer_id)->select('id', 'full_name', 'user_name', 'image', 'storage_type', 'status')->first();
            $params['producer']['image'] = $this->common->getImage($this->folder, $params['producer']['image'], 'profile', $params['producer']['storage_type']);

            $params['result']        = [];
            $input_search  = $request['input_search'];
            $input_rent    = $request['input_rent'];
            $input_status  = $request['input_status'];

            if ($content_type == 1) {
                $input_premimum = $request['input_premimum'];
                $query = Video::where('producer_id', $producer_id);
            } elseif ($content_type == 2) {
                $query = TVShow::where('producer_id', $producer_id);
            } else {
                $query = Shorts::where('producer_id', $producer_id);
            }

            if ($input_search != null) {
                $query->where('name', 'LIKE', "%{$input_search}%");
            }
            if ($content_type != 3 && $input_rent != null && $input_rent != 0) {
                $query->where('is_rent', 1);
            }
            if ($content_type == 1 && isset($input_premimum) && $input_premimum != null && $input_premimum != 'all') {
                $query->where('is_premium', $input_premimum);
            }
            if ($input_status != null && $input_status != 'all') {
                $query->where('status', $input_status);
            }

            $params['result'] = $query->with('type')->orderBy('status', 'desc')->latest()->paginate(20);

            $this->common->imageNameToUrl($params['result'], 'thumbnail', $this->folder_content, 'portrait');

            return view('admin.producer.content_index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function changeStatus(Request $request)
    {
        try {
            if ($request['content_type'] == 1) {
                $data = Video::where('id', $request->id)->first();
            } else if ($request['content_type'] == 2) {
                $data = TVShow::where('id', $request->id)->first();
            } else {
                $data = Shorts::where('id', $request->id)->first();
            }

            if ($data) {
                $data['status'] = $data['status'] === 1 ? 0 : 1;
                $data->save();
                return response()->json(['status' => 200, 'success' => __('label.status_changed'), 'status_code' => $data['status']]);
            }
            return response()->json(['status' => 400, 'errors' => __('label.data_not_found')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
