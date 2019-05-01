<?php

namespace Igorgawrys\Socialler\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Repositories\UserRepository;
use App\Validators\UserValidator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Notifications\NotifyGenerate;
use PDF;

/**
 * Class UsersController.
 *
 * @package namespace Test\Http\Controllers;
 */
class UsersController extends Controller
{
    /**
     * @var UserRepository
     */
    protected $repository;

    /**
     * @var UserValidator
     */
    protected $validator;

    /**
     * UsersController constructor.
     *
     * @param UserRepository $repository
     * @param UserValidator $validator
     */
    public function __construct(UserRepository $repository, UserValidator $validator)
    {
      $this->middleware('jwt.auth',['except' => 'show']);
        $this->repository = $repository;
        $this->validator  = $validator;
    $this->middleware('admin_perrmission',['only' => ['store','destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
      $users = $this->repository->with(['role','grade','school'])->all();
          return response()->json([
              'data' => $users,
          ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  UserCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(UserCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $user = $this->repository->create([
              'email' => $request->input('email'),
              'full_name' => $request->input('full_name'),
              'password' => bcrypt($request->input('password')),
              'role_id' => $request->input('role_id'),
              'grade_id' => $request->input('grade_id'),
              'school_id' => $request->input('school_id'),
              'social_code' => $request->input('social_code')
              ]); 
           $user->point()->create(['quantity' => 0]);
            $response = [
                'message' => 'User created.',
                'data'    => $user->toArray(),
            ];
                return response()->json($response);
        } catch (ValidatorException $e) {
                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = $this->repository->with(['lock','role','grade','school','point','giveBadges' => function($query){$query->with('badge');}])->find($id);
            return response()->json([
                'data' => $user,
            ]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = $this->repository->find($id);

        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UserUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(UserUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
            $user = ['full_name' => $request->input('full_name'),'email' => $request->input('email')];
            if($request->filled('lock_id')){
              if($request->input('lock_id')=="none"){
                  $user['lock_id'] = null;
              }else{
              $user['lock_id'] = $request->input('lock_id');
              }
            }
             if($request->filled('social_code')){
           $user['social_code'] = $request->input('social_code');
              
            }
            if($request->input('avatar') !=null){
                  $exploded = explode(",",$request->input('avatar'));
                  if(str_contains($exploded[0],'gif')){
                      $ext = 'gif';
                  }else if(str_contains($exploded[0],'png')){
                      $ext = 'png';
                  }else if(str_contains($exploded[0],'jpg')){
                      $ext = 'jpg';
                  }
              else if(str_contains($exploded[0],'jpeg')){
                      $ext = 'jpeg';
                  }else{
                    return;
                  }
                  $filename = str_random().'.'.$ext;
                    \File::put(storage_path(). '/app/public/images/avatars/' .$filename,base64_decode($exploded[1]));
                    $user['avatar'] = config('app.url')."/storage/images/avatars/".$filename;
            }
            if($request->filled('password')){
              $user['password'] =  bcrypt($request->input('password'));
            }
                 if($request->filled('qr_code')){
              $user['qr_code'] =  $request->input('qr_code');
            }
           if($request->filled('grade_id')){
              $user['grade_id'] =  $request->input('grade_id');
            }
          $user['first_logged'] = false;
               $user = $this->repository->update($user, $id);
            $response = [
                'message' => 'User updated.',
                'data'    => $user->toArray(),
            ];
                return response()->json($response);
        } catch (ValidatorException $e) {

                          return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
        }
    }

   public function generate($id){
      $user = $this->repository->find($id);
      $qr = "data:image/png;base64,".base64_encode(file_get_contents("https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=".$user->qr_code));
        $html = file_get_contents(storage_path().'/app/sketch/invitation.htm');
      $html = str_replace('<qr>',$qr,$html);
    $html = str_replace('<email>',''.$user->email,$html);
    $password = md5(mt_rand());
    $user->update(['password' => bcrypt($password)]);
    $html = str_replace('<password>',''.$password,$html);
      $key = md5(mt_rand());
      PDF::loadHTML($html,'UTF-8')->setPaper('a4', 'potrait')->setWarnings(false)->save('../storage/app/public/downloads/glowne'.$key.'.pdf');
      file_put_contents(storage_path(). '/app/public/downloads/glowne'.$key.'.html',$html);
       $path =  config('app.url')."/storage/downloads/glowne".$key.".html";
        auth()->user()->notify(new NotifyGenerate($path));
     return response()->json(['to_main_url' => config('app.url')."/storage/downloads/glowne".$key.".pdf",'to_html_main_url'=>config('app.url')."/storage/downloads/glowne".$key.".html"]);
   }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deleted = $this->repository->delete($id);

            return response()->json([
                'message' => 'User deleted.',
                'deleted' => $deleted,
            ]);
    }
    
}
