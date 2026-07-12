<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Common;
use App\Models\Notification_Configuration;
use Illuminate\Http\Request;
use Exception;

class NotificationConfigurationController extends Controller
{
    public $common;
    public function __construct()
    {
        $this->common = new Common;
    }

    public function index(Request $request)
    {
        try {
            $getstatus = Notification_Configuration::first();
            $params['main_status'] = $getstatus->status;

            if ($request->ajax()) {
                $data = Notification_Configuration::orderBy('id', 'asc')->get();

                return DataTables()::of($data)
                    ->addIndexColumn()
                    ->make(true);
            }

            return view('admin.notification_configuration.index', $params);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
    public function store(Request $request)
    {
        try {
            $type    = $request->type;
            $status  = $request->status;
            $entries = $request->input('entries');

            if ($type == 'all') {
                if ($status == 1) {
                    Notification_Configuration::query()->update(['status' => 1]);
                } elseif ($status == 0) {
                    Notification_Configuration::query()->update([
                        'status'            => 0,
                        'send_mail'         => 0,
                        'send_notification' => 0,
                    ]);
                }
            } else {
                foreach ($entries as $entry) {
                    Notification_Configuration::where('id', $entry['id'])->update([
                        'send_notification' => $entry['notification'],
                        'send_mail'         => $entry['email'],
                    ]);
                }
            }

            return response()->json(['status' => 200, 'success' => __('label.configuration_update')]);
        } catch (Exception $e) {
            return response()->json(['status' => 400, 'errors' => $e->getMessage()]);
        }
    }
}
