<?php

namespace Megaads\MultiLanguage\Middleware;

use Closure;
use Config;
use Illuminate\Support\Facades\Auth;

class LangAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // Status flag:
        $loginSuccessful = false;
        // Check username and password:
        if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])){
        
            $username = $_SERVER['PHP_AUTH_USER'];
            $password = $_SERVER['PHP_AUTH_PW'];
        
            if ($username == Config::get('app.basicAuthentication.username') && $password == Config::get('app.basicAuthentication.password')){
                $loginSuccessful = true;
            }
        }
        if ($loginSuccessful){
            return $next($request);
        }else{
            return response('Unauthorized.', 401,["WWW-Authenticate"=>"Basic realm='xxxxxxx'"]);
        }
    }

}
