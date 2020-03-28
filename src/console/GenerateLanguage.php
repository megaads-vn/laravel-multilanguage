<?php
/**
 * Created by PhpStorm.
 * User: KimTung
 * Date: 3/27/2020
 * Time: 2:58 PM
 */

namespace Megaads\MultiLanguage\Console;

use Illuminate\Console\Command;

class GenerateLanguage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lang:generate {--output=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate to multiple language file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $fileName = config('app.locale') .'.json';
        $options = $this->option();
        if (array_key_exists('output', $options) && $options['output'] != '') {
            $fileName = $options['output'] . '.json';
        }
        $path = base_path();
        $viewsPath = $this->listFolderFiles($path);
        $outFile  = $this->checkOutputFile($fileName);
        if (!empty($viewsPath)) {
            foreach ($viewsPath as $viewPath) {
                $fileContent = file_get_contents($viewPath);
                $regexGetText = '/__\((.*?)\)/m';
                preg_match_all($regexGetText, $fileContent, $matches);
                if (!empty($matches[1])) {
                    $formatedData = [];
                    $temp = [];
                    foreach ($matches[1] as $match) {
                        $match = ltrim($match,'"');
                        $match = ltrim($match,'\'');
                        $match = rtrim($match, '"');
                        $match = rtrim($match, '\'');
                        $match = preg_replace('/\$(.*?)$/i', '', $match);
                        if (!in_array($match, $temp) && $match != '') {
                            $temp[] = $match;
                            $formatedData[] = $match;
                        }
                    }
                    $this->writeContentToFile($outFile, $formatedData);
                }
            }
            echo "Done \n";
        }
    }

    protected function writeContentToFile($outFile, $writeData)
    {
        $isPush = false;
        $outFileContent = file_get_contents($outFile);
        if (!empty($outFileContent)) {
            $objectContent = json_decode($outFileContent, true);
            foreach ($objectContent as $key => $value) {
                if (in_array($key, $writeData)) {
                    $rmIndex = array_search($key, $writeData);
                    unset($writeData[$rmIndex]);
                }
            }
            if (!empty($writeData)) {
                foreach ($writeData as $item) {
                    $pushItem = array($item => "");
                    $objectContent = $objectContent + $pushItem;
                }
                $isPush = true;
            }
        }
        if ($isPush) {
            $fp = fopen($outFile, 'w');
            fwrite($fp, json_encode($objectContent, JSON_UNESCAPED_UNICODE));
            fclose($fp);
        }
    }

    protected function checkOutputFile($fileName)
    {
        $outFilePath = base_path('resources/lang/' . $fileName);
        if (!file_exists($outFilePath)) {
            $content = '{}';
            $fp = fopen($outFilePath, 'w');
            fwrite($fp, $content);
            fclose($fp);
            chmod($outFilePath, 0777);
        }
        return $outFilePath;
    }

    protected function readDirs($path)
    {
        $dirHandle = opendir($path);
        $allPath  = [];
        while ($item = readdir($dirHandle)) {
            $newPath = $path . "/" . $item;
            if (is_dir($newPath) && $item != '.' && $item != '..') {
                $dirPath = $this->readDirs($newPath);
                $allPath = $allPath + $dirPath;
            } else {
                if ($item != '.' && $item != '..') {
                    $fullPath = $newPath;
                    $allPath[]  = $fullPath;
                }
            }
        }
        return $allPath;
    }

    protected function listFolderFiles($dir){
        $retval = [];
        if ( is_file($dir) ) {
            $fileContents = file_get_contents($dir);
            if (preg_match_all('/__\([\'"](.*?)[\'"]\)/', $fileContents, $matches)) {
                print_r($matches); // Get whole match values
            }
            return false;
        }

        $ffs = scandir($dir);

        unset($ffs[array_search('.', $ffs, true)]);
        unset($ffs[array_search('..', $ffs, true)]);

        // prevent empty ordered elements
        if (count($ffs) < 1)
            return;

        $excepDir = ['vendor', 'storage', 'tests', 'public', 'bootstrap', 'database', '.git', '.env'];
        foreach($ffs as $ff){
            if ( is_file($dir . '/' . $ff) ) {
                $filePath = $dir . "/" . $ff ;
                $retval[] = $filePath;
            }
            $folders = $dir.'/'.$ff;
            if(is_dir($folders) && !in_array($ff, $excepDir) ) {
                $dirFile = $this->listFolderFiles($folders);
                foreach( $dirFile as $itemFile ) {
                    $retval[] = $itemFile;
                }
            }
        }

        return $retval;
    }
}
