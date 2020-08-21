<?php 
namespace Megaads\MultiLanguage;

use Megaads\MultiLanguage\Console\GenerateLanguage;
use Illuminate\Support\ServiceProvider;

class MultiLanguageServiceProvider extends ServiceProvider {

    public function boot(\Illuminate\Routing\Router $router)
    {
        $framework = $this->checkFrameWork();
        if ($framework && $framework['key'] == 'laravel/framework' && $framework['version'] >= 54 ) {
            $router = $this->app['router'];
            $router->aliasMiddleware('auth.lang', \Megaads\MultiLanguage\Middleware\LangAuthenticate::class);
            //or
//            $router->pushMiddlewareToGroup('auth.lang', \Megaads\MultiLanguage\Middleware\LangAuthenticate::class);
        } else {
            $router->middleware('auth.lang', 'Megaads\MultiLanguage\Middleware\LangAuthenticate');

        }
        if ($framework && $framework['key'] == 'laravel/framework' && $framework['version'] >= 52 ) {
            include __DIR__ . '/routes.php';
        } else {  
            if ( method_exists($this, 'routesAreCached') ) {
                if (!$this->app->routesAreCached()) {
                    include __DIR__ . '/routes.php';
                }
            }
        }
        $this->publishes([
            __DIR__.'/assets' => public_path('vendor/multi-language'),
        ], 'public');

        $this->loadViewsFrom(__DIR__.'/views', 'multi-language');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {
        // $this->commands('command.generate.lang');
        $this->registerCommands();
    }

     /**
     * Register the commands.
     *
     * @return void
     */
    protected function registerCommands() {
        $this->registerInstallCommand();
    }

    /**
     * Register the 'lang:generate' command.
     *
     * @return void
     */
    protected function registerInstallCommand() {
        $this->app->singleton('command.generate.lang', function($app) {
            return new GenerateLanguage();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'command.generate.lang'
        ];
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