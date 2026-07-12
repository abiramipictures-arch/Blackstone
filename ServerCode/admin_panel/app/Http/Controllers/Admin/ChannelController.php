<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\Common;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class ChannelController extends Controller
{
    private $folder = "channel";
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

                $query = Channel::query();
                if ($input_search != null) {
                    $query->where('name', 'LIKE', "%{$input_search}%");
                }
                $data = $query->orderby('status', 'desc')->latest();

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('portrait_img', function ($row) {
                        return $this->common->getImage($this->folder, $row['portrait_img'], 'portrait', $row['storage_type']);
                    })
                    ->addColumn('action', function ($row) {

                        $portrait_url = $this->common->getImage($this->folder, $row['portrait_img'], 'portrait', $row['storage_type']);
                        $landscape_url = $this->common->getImage($this->folder, $row['landscape_img'], 'landscape', $row['storage_type']);

                        $delete = '<form class="delete-form" method="POST" action="' . route('admin.channel.destroy', [$row->id]) . '">
                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="edit-delete-btn"><i class="fa-solid fa-trash-can fa-xl"></i></button></form>';

                        $btn = '<div class="d-flex justify-content-around">';
                        $btn .= '<a class="edit-delete-btn mr-2 edit_channel" data-toggle="modal" href="#EditModel" data-id="' . $row->id . '" data-name="' . $row->name . '" data-portrait_img="' . $portrait_url . '" data-landscape_img="' . $landscape_url . '" data-is_title="' . $row->is_title . '" data-storage_type="' . $row->storage_type . '">';
                        $btn .= '<i class="fa-solid fa-pen-to-square fa-xl"></i>';
                        $btn .= '</a>';
                        $btn .= $delete;
                        $btn .= '</a></div>';
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
                    ->rawColumns(['portrait_img', 'action', 'status'])
                    ->make(true);
            }
            return view('admin.channel.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:2',
                'is_title' => 'required',
                'portrait_img' => 'image|mimes:jpeg,png,jpg,webp',
                'landscape_img' => 'image|mimes:jpeg,png,jpg,webp',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()->all()]);
            }

            $requestData = $request->all();
            $requestData['storage_type'] = Storage_Type();
            if (isset($requestData['portrait_img'])) {
                $requestData['portrait_img'] = $this->common->saveImage($requestData['portrait_img'], $this->folder, 'ch_port_', $requestData['storage_type']);
            } else {
                $requestData['portrait_img'] = "";
            }
            if (isset($requestData['landscape_img'])) {
                $requestData['landscape_img'] = $this->common->saveImage($requestData['landscape_img'], $this->folder, 'ch_land_', $requestData['storage_type']);
            } else {
                $requestData['landscape_img'] = "";
            }
            $requestData['status'] = 1;

            $data = Channel::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($data->id)) {
                return response()->json(['status' => 200, 'success' => __('label.success_add_channel')]);
            }
            return response()->json(['status' => 400, 'errors' => __('label.error_add_channel')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function update($id, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:2',
                'is_title' => 'required',
                'portrait_img' => 'image|mimes:jpeg,png,jpg,webp',
                'landscape_img' => 'image|mimes:jpeg,png,jpg,webp',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()->all()]);
            }

            $requestData = $request->all();
            $requestData['storage_type'] = Storage_Type();
            if (isset($requestData['portrait_img'])) {
                $requestData['portrait_img'] = $this->common->saveImage($requestData['portrait_img'], $this->folder, 'ch_port_', $requestData['storage_type']);
                $this->common->deleteImageToFolder($this->folder, basename($requestData['old_portrait_img']), $request['old_storage_type']);
            }
            if (isset($requestData['landscape_img'])) {
                $requestData['landscape_img'] = $this->common->saveImage($requestData['landscape_img'], $this->folder, 'ch_land_', $requestData['storage_type']);
                $this->common->deleteImageToFolder($this->folder, basename($requestData['old_landscape_img']), $request['old_storage_type']);
            }
            unset($requestData['old_portrait_img'], $requestData['old_landscape_img'], $requestData['old_storage_type']);

            $data = Channel::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($data->id)) {
                return response()->json(['status' => 200, 'success' => __('label.success_edit_channel')]);
            }
            return response()->json(['status' => 400, 'errors' => __('label.error_edit_channel')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function destroy($id)
    {
        try {

            $data = Channel::where('id', $id)->first();
            if ($data) {
                $this->common->deleteImageToFolder($this->folder, $data['portrait_img'], $data['storage_type']);
                $this->common->deleteImageToFolder($this->folder, $data['landscape_img'], $data['storage_type']);
                $data->delete();
            }
            return redirect()->route('admin.channel.index')->with('success', __('label.channel_delete'));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function show($id)
    {
        try {

            $data = Channel::where('id', $id)->first();
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
