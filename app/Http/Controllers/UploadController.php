<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class UploadController extends BaseController
{
    public function upload(Request $request) {
        $input = $request->all();

        # 初期化
        $ret = Array();

        # 変数の確認、保存場所の生成、保存の実行
        if( array_key_exists("info", $input)) {
            if( array_key_exists("title", $input["info"])) {
                $dir = strtolower(str_replace(['\\','/',':','*','?','"','<','>','|', ' '], "_", $input["info"]["title"]));
            } else {
                $dir = "notitled";
            }
            Storage::makeDirectory($dir);

            if( array_key_exists("version", $input["info"])) {
                $file = "swagger_" . strtolower(str_replace(['\\','/',':','*','?','"','<','>','|', ' '], "_", $input["info"]["version"])) . ".json";
            } else {
                $file = "swagger.json";
            }
            $json = json_encode($input,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            $path = $dir . "/" . $file;
            Storage::put($path, $json);

            $ret["status"] = "success";
            $ret["swagger_url"] = $request->root() . "/ui/?url=" . $request->root() . "/files/" . $path;
        } else {
            $ret["status"] = "failure";
        }
        
        return response()->json($ret, 200,  ['Content-Type' => 'application/json'], JSON_UNESCAPED_SLASHES);
    }
}
