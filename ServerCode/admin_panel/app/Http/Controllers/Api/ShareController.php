<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Video;
use Illuminate\Support\Str;
use Exception;
use App\Models\Common;
use App\Models\Shorts;
use App\Models\TVShow;

class ShareController extends Controller
{
    public $folder_content = "content";
    public $common;
    public function __construct()
    {
        $this->common = new Common();
    }

    public function details($video_type = 0, $type_id = 0, $id = 0, $sub_video_type = 0)
    {
        if ($video_type == 1) {

            $data = Video::where('id', $id)->where('video_type', $video_type)->where('type_id', $type_id)->where('status', 1)->first();
        } elseif ($video_type == 2) {

            $data = TVShow::where('id', $id)->where('video_type', $video_type)->where('type_id', $type_id)->where('status', 1)->first();
        } elseif ($video_type == 5 || $video_type == 6 || $video_type == 7) {

            if ($sub_video_type == 1) {
                $data = Video::where('id', $id)->where('video_type', $video_type)->where('type_id', $type_id)->where('status', 1)->first();
            } else if ($sub_video_type == 2) {
                $data = TVShow::where('id', $id)->where('video_type', $video_type)->where('type_id', $type_id)->where('status', 1)->first();
            }
        } else {
            return view('errors.404');
        }

        $image = $data && $data['thumbnail']
            ? $this->common->getImage($this->folder_content, $data['thumbnail'], 'normal', $data['storage_type'])
            : asset('assets/imgs/logo.png');

        $url_path = $sub_video_type > 0
            ? "details/{$video_type}/{$type_id}/{$id}/{$sub_video_type}"
            : "details/{$video_type}/{$type_id}/{$id}";

        return view('share.details', [
            'title' => $data['name'] ?? "",
            'description' => Str::limit($data['description'] ?? "", 150),
            'image' => $image,
            'url' => env('APP_URL') . "/{$url_path}",
            'redirect_url' => env('WEB_URL') . "/{$url_path}",
        ]);
    }
    public function shorts($video_type = 0, $type_id = 0, $id = 0)
    {
        if ($video_type == 8) {
            $data = Shorts::where('id', $id)->where('video_type', $video_type)->where('type_id', $type_id)->where('status', 1)->first();
        } else {
            return view('errors.404');
        }

        $image = $data && $data['thumbnail']
            ? $this->common->getImage($this->folder_content, $data['thumbnail'], 'normal', $data['storage_type'])
            : asset('assets/imgs/logo.png');

        $url_path = "shorts/{$video_type}/{$type_id}/{$id}";

        return view('share.details', [
            'title' => $data['name'] ?? "",
            'description' => Str::limit($data['description'] ?? "", 150),
            'image' => $image,
            'url' => env('APP_URL') . "/{$url_path}",
            'redirect_url' => env('WEB_URL') . "/{$url_path}",
        ]);
    }
}
