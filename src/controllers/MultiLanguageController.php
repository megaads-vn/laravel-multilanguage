<?php
/**
 * Created by PhpStorm.
 * User: KimTung
 * Date: 3/27/2020
 * Time: 4:02 PM
 */

namespace Megaads\MultiLanguage\Controller;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Request;

class MultiLanguageController extends BaseController
{
    public function langEditor() {
        $path = base_path('resources/lang/' . config('app.locale') . '.json');
        $content = file_get_contents($path);
        $objectContent = json_decode($content, true);
        if ( \Request::isMethod('post') ) {
            $all = \Request::all();
            $langKey = $all['key'];
            $langValue = $all['value'];
            $objectContent[$langKey] = $langValue;
            $fp = fopen($path, 'w');
            fwrite($fp, json_encode($objectContent, JSON_UNESCAPED_UNICODE));
            fclose($fp);
            return response()->json(['status' => 'successful', 'data' => $langValue]);
        }
        return view('multi-language::lang-editor')->with(compact('objectContent'));
    }

    public function deleteItem() {
        $response = [
            'status' => 'fail'
        ];
        $request = \Request::all();
        $path = base_path('resources/lang/' . config('app.locale') . '.json');
        $content = file_get_contents($path);
        $objectContent = json_decode($content, true);
        if (array_key_exists('key', $request)) {
            $key = $request['key'];
            if (isset($objectContent[$key])) {
                unset($objectContent[$key]);
            }
            $fp = fopen($path, 'w');
            fwrite($fp, json_encode($objectContent, JSON_UNESCAPED_UNICODE));
            fclose($fp);
            $response['status'] = 'successful';
            $response['data'] = '';
        } else {
            $response['message'] = 'Invalid params';
        }
        return response()->json($response);
    }
}