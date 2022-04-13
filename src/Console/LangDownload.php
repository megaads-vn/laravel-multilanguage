<?php

namespace Megaads\MultiLanguage\Console;

use Illuminate\Console\Command;

class LangDownload extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lang:download {--site=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get json from {--site} and update at current site';

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
        $path = base_path() . '/resources/lang/' . $fileName;
        $options = $this->option();
        if (array_key_exists('site', $options) && $options['site'] != '') {
            $url = $options['site'];
            $response = $this->getDataFromUrl($url);
            $currentData = $this->getCurrentData();
            if ($response) {
                $comingData = json_decode($response, true);
                foreach ($comingData as $key => $value) {
                    if (isset($currentData[$key])) {
                        if (!$currentData[$key] && $comingData[$key]) {
                            $currentData[$key] = $comingData[$key];
                        }
                    } else {
                        $currentData[$key] = $comingData[$key];
                    }
                }
            }
        }
        $this->writeContentToFile($path, $currentData);
        echo 'Done';
    }

    public function getDataFromUrl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        $retval = curl_exec($ch);
        curl_close($ch);
        return $retval;
    }

    public function getCurrentData() {
        $locale = config('app.locale');
        $path = base_path('resources/lang/' . $locale . '.json');
        $content = file_get_contents($path);
        $objectContent = json_decode($content, true);
        return $objectContent;
    }


    protected function writeContentToFile($outFile, $writeData)
    {
        $fp = fopen($outFile, 'w');
        fwrite($fp, json_encode($writeData, JSON_UNESCAPED_UNICODE));
        fclose($fp);
        return true;
    }
}
