<?php

namespace Igorgawrys\Socialler\Http\Middleware;

use Closure;
use App\Entities\Answer;
class AnswerPermission
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
    	$answer = Answer::findorfail($request->answer);
              if(auth()->user()->load(['role'])->role->id > 1 || auth()->user()->id == $answer->user_id){
    		  return $next($request);
    	}else{
    			return response()->json(['error' => 'Unauthorized'], 401);
    	}
        return $next($request);
    }
}
