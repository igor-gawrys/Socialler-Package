<?php

namespace Igorgawrys\Socialler\Http\Middleware;

use Closure;
use App\Entities\Grade;
class GradePerrmission
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
    	$grade = Grade::where('id','=',$request->grade)->orwhere('name','=',$request->grade)->first();
    	if( auth()->user()->load(['role'])->role->id > 2 || auth()->user()->load(['grade'])->grade->id == $grade->id || auth()->user()->id==$grade->user_id){
    		  return $next($request);
    	}else{
    			return response()->json(['error' => 'Unauthorized'], 401);
    	}
      
    }
}
