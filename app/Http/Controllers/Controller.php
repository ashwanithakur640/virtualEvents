<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Helper;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Instantiate a new UserController instance.
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
           
            $this->user= Auth::guard('participant')->user();
           
            if(empty($this->user))
            {
                $this->user= Auth::guard('vendor')->user(); 
                $prefix = Helper::prefix($this->user);   
            }
            else
            {
                $prefix = Helper::prefixs($this->user); 
            }
          
            
            $this->Prefix = $prefix;
            View::share('Prefix', $prefix);
            return $next($request);
            


        });
        if (app('request')->route()) {
            $routeArray = app('request')->route()->getAction();
            $controllerAction = class_basename($routeArray['controller']);
            list($controller, $action) = explode('@', $controllerAction);
            View::share('controllerName', $controller);
            View::share('controllerActionName', $action);
        }

    }

}
