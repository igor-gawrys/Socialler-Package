<?php

namespace Igorgawrys\Socialler\Http\Middleware;

use Closure;

class AdminPerrmission
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
    	if(auth()->user()->load(['role'])->role->id > 1){
    		  return $next($request);
    	}else{
    			return response()->json(['error' => 'Unauthorized'], 401);
    	}
      
    }
}
