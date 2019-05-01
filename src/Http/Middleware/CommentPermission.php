<?php

namespace Igorgawrys\Socialler\Http\Middleware;

use Closure;
use App\Entities\Comment;
class CommentPermission
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
    		$comment = Comment::findorfail($request->comment);
    	       if(auth()->user()->load(['role'])->role->id > 1 || auth()->user()->id == $comment->user_id){
    		  return $next($request);
    	}else{
    			return response()->json(['error' => 'Unauthorized'], 401);
    	}
    }
}
