<?php
/**
 * Created by PhpStorm.
 * User: KimTung
 * Date: 3/27/2020
 * Time: 4:02 PM
 */

namespace Megaads\MultiLanguage\Controller;

use File;
use Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Request;

class MultiLanguageController extends BaseController
{
    /**
     * Show lang editor view and handle submit edited
     * 
     */
    public function langEditor() {
        $input = \Request::all();
        $locale = config('app.locale');
        if (array_key_exists('locale', $input)) {
            $locale = $input['locale'];
        }
        $path = base_path('resources/lang/' . $locale . '.json');
        $content = file_get_contents($path);
        $objectContent = json_decode($content, true);
        if ( \Request::isMethod('post') ) {
            $langKey = json_decode($input['key']);
            $langValue = $input['value'];
            $objectContent[$langKey] = $langValue;
            $fp = fopen($path, 'w');
            fwrite($fp, json_encode($objectContent, JSON_UNESCAPED_UNICODE));
            fclose($fp);
            return response()->json(['status' => 'successful', 'data' => $langValue]);
        }
        return view('multi-language::index')->with(compact('objectContent', 'locale'));
    }

    /**
     * Delete item function 
     * 
     */
    public function deleteItem() {
        $response = [
            'status' => 'fail'
        ];
        $request = \Request::all();

        $locale = config('app.locale');
        if (array_key_exists('locale', $request)) {
            $locale = $request['locale'];
        }
        $path = base_path('resources/lang/' . $locale . '.json');
        $content = file_get_contents($path);
        $objectContent = json_decode($content, true);
        if (array_key_exists('key', $request)) {
            $key = json_decode($request['key']);
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

    public function resources($file) {
        $extractFileType = explode('.', $file);
        $fileExtension = end($extractFileType);
        $basePath = base_path('/vendor/megaads/laravel-multilanguage/src/Resources');
        $pathByType = '';
        $mimeType = '';
        if ($fileExtension) {
            $fileExtension = strtolower($fileExtension);
            switch ($fileExtension) {
                case 'png':
                case 'jpg':
                case 'jpeg':
                case 'gif':
                    $pathByType = 'images';
                    if ($fileExtension == 'jpeg') {
                        $fileExtension = 'jpg';
                    }
                    $mimeType = 'image/' . $fileExtension;
                    break;
                case 'css':
                    $pathByType = 'css';
                    $mimeType = 'text/css';
                break;
                case 'js':
                    $pathByType = 'js';
                    $mimeType = 'application/javascript';
                break;
            }
            $basePath = $basePath . '/' . $pathByType . '/' . $file;
        }
        if (!File::exists($basePath)) { 
            abort(404);
        }
        $file = File::get($basePath);
        $response = Response::make($file, 200);
        $response->header("Content-Type", $mimeType);

        return $response;
    }

    public function addLanguageKey() {
        $response = [
            'status' => 'fail'
        ];
        $input = \Request::all();
        $locale = config('app.locale');
        if (array_key_exists('locale', $input)) {
            $locale = $input['locale'];
        }
        $path = base_path('resources/lang/' . $locale . '.json');
        $content = file_get_contents($path);
        $objectContent = json_decode($content, true);
        if (array_key_exists('key', $input)) {
            $langKey = json_decode($input['key']);
            $langValue = $input['value'];
            $objectContent[$langKey] = $langValue;
            $fp = fopen($path, 'w');
            fwrite($fp, json_encode($objectContent, JSON_UNESCAPED_UNICODE));
            fclose($fp);
            return response()->json(['status' => 'successful', 'data' => $langValue]);
        } else {
            $response['message'] = 'Missing some params';
        }
        return response()->json($response);
    }
}