<?php

namespace Igorgawrys\Socialler\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use  App\Entities\Badge;
use App\Entities\User;
use JWTAuth;
  use Illuminate\Http\Request;
class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['login','qr','image','id']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
        if (! $token = auth()->attempt($credentials)) {
          return response()->json(['error' => 'Unauthorized'], 401);
        }else{
          $user = auth()->user()->load(['grade','school']);
          if($user->grade->verifed_at==null OR $user->school->verifed_at==null){
              return response()->json(['error' => 'Twoja klasa lub szkoła oczekuje na autoryzacje dlatego nie możesz się zalogować'], 401);
          } 
        }
       return $this->respondWithToken($token);
    }
        /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function qr()
    {
       $user =  User::where(["qr_code" =>request()->input('qr_code')])->with(['grade','school'])->first();
        if (! $token = JWTAuth::fromUser($user)) {
          return response()->json(['error' => 'Unauthorized'], 401);
        }else{
          if($user->grade->verifed_at==null OR $user->school->verifed_at==null){
              return response()->json(['error' => 'Twoja klasa lub szkoła oczekuje na autoryzacje dlatego nie możesz się zalogować'], 401);
          } 
        }
       return $this->respondWithToken($token);
    }
  
      /**
     * Login By Social Team 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function id(Request $request){
    $user =  User::where(["social_code" =>request()->input('social_code')])->with(['grade','school'])->first();
        if (! $token = JWTAuth::fromUser($user)) {
          return response()->json(['error' => 'Unauthorized'], 401);
        }else{
          if($user->grade->verifed_at==null OR $user->school->verifed_at==null){
              return response()->json(['error' => 'Twoja klasa lub szkoła oczekuje na autoryzacje dlatego nie możesz się zalogować'], 401);
          } 
        }
       return $this->respondWithToken($token);
    }
  
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me(Request $request)
    {
         $user = auth()->user()->load(['lock','role','notes','grade' => function($query){
        $query->with('users');
       $query->with(['messagesAlls' => function($query){$query->with('user');}]);
        $query->with(['schedule' => function($query){$query->with('rooms'); $query->with(['teachers' => function($query){$query->with('room');}]);$query->with(['days' => function($query){$query->orderby('order','asc');$query->with('events');$query->with(['lessons' => function($query){$query->orderby('order','asc');$query->with('room');$query->with('teacher');}]);}]);}]);
        $query->with(['posts' => function($query){
        $query->with(['trusts' => function($query){$query->with('user');},'user' => function($query){ $query->with('point'); $query->with('role');},'comments' => function($query){
         $query->orderby('id','desc');
         $query->with(['user' => function($query){ $query->with('point'); $query->with('role');},'answers' => function($query){
           $query->orderby('id','desc');
           $query->with(['user' => function($query){ $query->with('point'); $query->with('role');}]);
         }]);
    }])->orderby('order','desc')->orderby('id','dsesc');
        }]);
    },'school' => function($query){$query->with('grades');$query->with('subscriptions');},'point','giveBadges' => function($query){$query->with('badge');}]);
          if($user->first_logged){
        auth()->user()->update(['first_logged' => false]);
        $user['first_logged'] = true;
      }
        return response()->json($user);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
    
    public function notifications(){
      $notifications = auth()->user()->unreadNotifications;
      auth()->user()->unreadNotifications->markAsRead();
      return response()->json(["data" => $notifications]);
    }
    public function image(Request $request){
      $image = $request->input('image');
                 $exploded = explode(",",$image);
                  if(str_contains($exploded[0],'gif')){
                      $ext = 'gif';
                  }else if(str_contains($exploded[0],'png')){
                      $ext = 'png';
                  } elseif(str_contains($exploded[0],'jpg')){
                      $ext = 'jpg';
                  }
                  elseif(str_contains($exploded[0],'jpeg')){
                      $ext = 'jpeg';
                  }else{
                    return;
                  }
                  $filename = str_random().'.'.$ext;
                    \File::put(storage_path(). '/app/public/images/' .$filename,base64_decode($exploded[1]));
                    return response()->json(["data" => ["image" => config('app.url')."/storage/images/".$filename]]);
    }
         public function video(Request $request){
      $video = $request->input('video');
                 $exploded = explode(",",$video);
                  if(str_contains($exploded[0],'mp4')){
                      $ext = 'mp4';
                  }else{
                    return;
                  }
                  $filename = str_random().'.'.$ext;
                    \File::put(storage_path(). '/app/public/videos/' .$filename,base64_decode($exploded[1]));
                    return response()->json(["data" => ["video" => config('app.url')."/storage/videos/".$filename]]);
    }
       public function file(Request $request){
      $file = $request->input('file');
      $exploded = explode(",",$file);
    $filename = str_random().'.'.$request->input("type");
                    \File::put(storage_path(). '/app/public/files/' .$filename,base64_decode($exploded[1]));
                    return response()->json(["data" => ["file" => config('app.url')."/storage/files/".$filename]]);
    }
}
