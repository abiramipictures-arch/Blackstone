<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Validator;
use App\Models\Type;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Common;
use App\Models\Home_Section;
use Exception;

// Type : 1- Movies, 2- TVShow, 3- Category, 4-Language, 5- Upcoming Content, 6- Channel Content, 7- Kids Content, 8- Shorts
class TypeController extends Controller
{
    private $folder = "type";
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {

            $params['data'] = Type::where('status', 1)->orderby('sort_order', 'asc')->latest()->get();
            if ($request->ajax()) {

                $input_search = $request['input_search'];
                $input_type = $request['input_type'];

                $query = Type::query();
                if ($input_search != null) {
                    $query->where('name', 'LIKE', "%{$input_search}%");
                }
                if ($input_type != 0) {
                    $query->where('type', $input_type);
                }
                $data = $query->orderby('status', 'desc')->orderby('sort_order', 'asc')->latest();

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('icon', function ($row) {
                        return $this->common->getImage($this->folder, $row['icon'], 'normal', $row['storage_type']);
                    })
                    ->addColumn('action', function ($row) {

                        $delete = '<form class="delete-form" method="POST" action="' . route('admin.type.destroy', [$row->id]) . '">
                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="edit-delete-btn"><i class="fa-solid fa-trash-can fa-xl"></i></button></form>';

                        $btn = '<div class="d-flex justify-content-around">';
                        $btn .= '<a class="edit-delete-btn mr-2 edit_type" data-toggle="modal" href="#EditModel" data-id="' . $row->id . '" data-name="' . $row->name . '" data-type="' . $row->type . '" data-icon="' . $this->common->getImage($this->folder, $row['icon'], 'normal', $row['storage_type']) . '" data-storage_type="' . $row->storage_type . '">';
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
                    ->rawColumns(['action', 'status'])
                    ->make(true);
            }
            return view('admin.type.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:2',
                'type' => 'required',
                'icon' => 'image|mimes:jpeg,png,jpg,webp',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()->all()]);
            }

            $requestData = $request->all();
            $requestData['storage_type'] = Storage_Type();
            if (isset($requestData['icon'])) {
                $requestData['icon'] = $this->common->saveImage($requestData['icon'], $this->folder, 'type_', $requestData['storage_type']);
            } else {
                $requestData['icon'] = "";
            }
            $requestData['sort_order'] = 0;
            $requestData['status'] = 1;

            $data = Type::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($data->id)) {
                return response()->json(['status' => 200, 'success' => __('label.success_add_type')]);
            }
            return response()->json(['status' => 400, 'errors' => __('label.error_add_type')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function update($id, Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:2',
                'type' => 'required',
                'icon' => 'image|mimes:jpeg,png,jpg,webp',
            ]);
            if ($validator->fails()) {
                return response()->json(['status' => 400, 'errors' => $validator->errors()->all()]);
            }

            $requestData = $request->all();
            if (isset($requestData['icon'])) {
                $requestData['storage_type'] = Storage_Type();
                $requestData['icon'] = $this->common->saveImage($requestData['icon'], $this->folder, 'type_',  $requestData['storage_type']);

                $this->common->deleteImageToFolder($this->folder, basename($requestData['old_icon']), $request['old_storage_type']);
            }
            unset($requestData['old_icon'], $requestData['old_storage_type']);

            $data = Type::updateOrCreate(['id' => $requestData['id']], $requestData);
            if (isset($data->id)) {
                return response()->json(['status' => 200, 'success' => __('label.success_edit_type')]);
            }
            return response()->json(['status' => 400, 'errors' => __('label.error_edit_type')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function destroy($id)
    {
        try {

            $data = Type::where('id', $id)->first();
            if ($data) {
                $this->common->deleteImageToFolder($this->folder, $data['icon'], $data['storage_type']);
                $data->delete();

                // Delete all related data
                Banner::where('type_id', $id)->delete();
                Home_Section::where('type_id', $id)->delete();
            }
            return redirect()->route('admin.type.index')->with('success', __('label.type_delete'));
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function show($id)
    {
        try {

            $data = Type::where('id', $id)->first();
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
    // Sortable
    public function TypeSortableSave(Request $request)
    {
        try {

            $ids = $request['ids'];
            if (isset($ids) && $ids != null && $ids != "") {

                $id_array = explode(',', $ids);
                for ($i = 0; $i < count($id_array); $i++) {
                    Type::where('id', $id_array[$i])->update(['sort_order' => $i + 1]);
                }
            }
            return response()->json(['status' => 200, 'success' => __('label.sort_order_saved')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
