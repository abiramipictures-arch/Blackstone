<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Refer_Earn;
use Illuminate\Http\Request;
use Exception;

class ReferEarnController extends Controller
{
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {
            $params['total_referrals'] = Refer_Earn::count();
            $params['total_parent_earn'] = Refer_Earn::sum('parent_earn');
            $params['total_child_earn'] = Refer_Earn::sum('child_earn');

            if ($request->ajax()) {

                $input_search     = $request['input_search'] ?? '';
                $query = Refer_Earn::with([
                    'parent_user:id,full_name,email,mobile_number',
                    'child_user:id,full_name,email,mobile_number',
                ])->select('tbl_refer_earn.*');

                if (!empty($input_search)) {
                    $query->whereHas('parent_user', function ($q) use ($input_search) {
                        $q->where('full_name', 'LIKE', "%{$input_search}%")->orWhere('email', 'LIKE', "%{$input_search}%");
                    })->orwhereHas('child_user', function ($q) use ($input_search) {
                        $q->where('full_name', 'LIKE', "%{$input_search}%")->orWhere('email', 'LIKE', "%{$input_search}%");
                    })->orWhere('reference_code', 'LIKE', "%{$input_search}%");
                }
                $data = $query->latest();

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->addColumn('parent_name', fn($row) => $row->parent_user->full_name ?? '-')
                    ->addColumn('parent_email', fn($row) => $row->parent_user->email ?? '-')
                    ->addColumn('child_name', fn($row) => $row->child_user->full_name ?? '-')
                    ->addColumn('child_email', fn($row) => $row->child_user->email ?? '-')
                    ->addColumn('date', fn($row) => date('d M Y', strtotime($row->created_at)))
                    ->make(true);
            }
            return view('admin.refer_earn.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
