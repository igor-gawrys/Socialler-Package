<?php

namespace Igorgawrys\Socialler\Http\Middleware;

use Closure;
use App\Entities\Post;
class PostPermission
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
    	$post = Post::findorfail($request->post);
       if(auth()->user()->load(['role'])->role->id > 1 || auth()->user()->id == $post->user_id){
    		  return $next($request);
    	}else{
    			return response()->json(['error' => 'Unauthorized'], 401);
    	}
    }
}
