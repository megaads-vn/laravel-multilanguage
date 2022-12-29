<?php
/**
 * Created by PhpStorm.
 * User: KimTung
 * Date: 3/27/2020
 * Time: 4:02 PM
 */

namespace Megaads\MultiLanguage\Controllers;

use File;
use Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;

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
        $listLocale = $this->listLanguage();
        return view('multi-language::index')->with(compact('objectContent', 'locale', 'listLocale'));
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

    public function downloadLanguageFile() {
        $locale = config('app.locale');
        $path = base_path('resources/lang/' . $locale . '.json');
        $content = file_get_contents($path);
        header('Content-Type: application/json; charset=utf-8');
        return $content;
    }

    public function export(Request $request) 
    {
        $locale = config('app.locale');

        if ($request->input('locale')) {
            $locale = $request->input('locale');
        }

        $path = resource_path('lang/' . $locale . '.json');
    
        $content = file_get_contents($path);

        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $locale . '.json"');

        return $content;
    }

    public function import(Request $request)
    {
        $locale = config('app.locale');

        if ($request->input('locale')) {
            $locale = $request->input('locale');
        }

        $file = $request->file('content');
        $file->move(resource_path('lang'), $locale . '.json');

        echo '<meta http-equiv="refresh" content="3;url=' . url()->previous() . '" />';
        echo "Import successful, Redirecting back in 3s...";
    }

    private function listLanguage() {
        $retVal = [];
        $langPath = resource_path('/lang');
        $listFile = array_diff(scandir($langPath), array('.', '..'));
        if (count($listFile) > 0) {
            foreach ($listFile as $item) {
                $getExtension = explode('.', $item);
                $ext = end($getExtension);
                if ($ext == 'json') {
                    $retVal[] = [
                        "code" => $getExtension[0],
                        "text" => $this->mapLocaleToName($getExtension[0])
                    ];
                }
            }
        }
        return $retVal;
    }

    private function mapLocaleToName ($locale) {
        $retVal = $locale;
        switch ($locale) {
            case 'en':
                    $retVal = 'English';
                break;
            case 'de':
                    $retVal = 'Germany';
                break;
            case 'dk':
                    $retVal = 'Denmark';
               break;
            case 'es':
                    $retVal = 'Spain';
                break;
            case 'fi':
                    $retVal = 'Finland';
                break;
            case 'fr':
                    $retVal = 'French';
                break;
            case 'id':
                    $retVal = 'Indonesia';
                break;
            case 'ir':
                    $retVal = 'Iran';
                break;
            case 'it';
                    $retVal = 'Italy';
                break;
            case 'jp':
                    $retVal = 'Japan';
                break;
            case 'kr';
                    $retVal = 'Korea';
                break;
            case 'pl':
                    $retVal = 'Poland';
                break;
            case 'pt':
                    $retVal= 'Portugal';
                break;
            case 'ru':
                    $retVal = 'Russia';
                break;
            case 'se':
                    $retVal = 'Sweden';
                break;
            case 'th';
                    $retVal = 'Thailand';
                break;
            case 'vi';
                    $retVal = 'Viet Nam';
                break;
            case 'ca':
                    $retVal = 'Canada';
                break;
            case 'au':
                    $retVal = 'Australia';
                break;
        }
        return $retVal;
    }
}