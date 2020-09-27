<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App\Models\Visiter;
use App\Events\VisiteMyApp;

class SaveVisite
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if($request->method() == 'GET') {
            if(! Auth::guard('admin')->check()) {
                $route = $request->path();
                $key = 'visited' . $route;
                if(! session()->has($key)) {
                    $IP = $request -> ip();
                    $mac = substr(exec('getmac'), 0, 17);
                    $device_data = $request->header('User-Agent');
                    $ref = $request->has('ref') ? $request->get('ref') : null;

                    $visiter = Visiter::where('ip_address',$IP)
                        -> where('device_data', $device_data)
                        -> where('mac_address', $mac)->first();

                    $user_id = Auth::check() ? Auth::user()->id : null;
                    event(new VisiteMyApp($visiter,$IP,$mac,$device_data,$route,$ref,$user_id));
                }
            }
        }
        return $next($request);
    }
}
