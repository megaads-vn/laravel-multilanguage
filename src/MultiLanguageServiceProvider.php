<?php 
namespace Megaads\Multilanguage;

use Megaads\LaravelMultiLanguage\Console\GenerateLanguage;
use Illuminate\Support\ServiceProvider;

class MultiLanguageServiceProvider extends ServiceProvider {

    public function boot()
    {
        $framework = $this->checkFrameWork();
        if ($framework && $framework['key'] == 'laravel/framework' && $framework['version'] >= 52 ) {
            include __DIR__ . '/routes.php';
        } else {  
            if ( method_exists($this, 'routesAreCached') ) {
                if (!$this->app->routesAreCached()) {
                    include __DIR__ . '/routes.php';
                }
            }
        }
    }

    public function register() {
        $this->app->singleton('command.generate.lang', function($app) {
            return new GenerateLanguage();
        });
        $this->commands('command.generate.lang');
    }

    private function checkFrameWork() {
        $findFrameWork = ['laravel/framework','laravel/lumen-framework'];
        $frameworkDeclare = file_get_contents(__DIR__ . '../../../../../composer.json');
        $frameworkDeclare = json_decode($frameworkDeclare, true);
        $required =  array_key_exists('require', $frameworkDeclare) ? $frameworkDeclare['require'] : [];
        $requiredKeys = [];
        if ( !empty($required) ) {
            $requiredKeys = array_keys($required);
            foreach($requiredKeys as $key) {
                if ( in_array($key, $findFrameWork) ) {
                    $version = $required[$key];
                    $version = str_replace('*', '',$version);
                    $version = preg_replace('/\./', '', $version);
                    return ['key' => $key, 'version' => (int) $version];
                }
            }
        }
        return NULL;
    }
}