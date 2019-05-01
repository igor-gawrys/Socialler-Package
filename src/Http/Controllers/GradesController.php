<?php

namespace Igorgawrys\Socialler\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\GradeCreateRequest;
use App\Http\Requests\GradeUpdateRequest;
use App\Http\Requests\GradeRegisterRequest;
use App\Repositories\GradeRepository;
use App\Validators\GradeValidator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Notifications\NotifyVerify;
  use App\Notifications\NotifyGenerate;
use Laravolt\Avatar\Facade as Avatar;
use App\Entities\School; 
use Carbon\Carbon;
use PDF;
/**
 * Class GradesController.
 *
 * @package namespace Test\Http\Controllers;
 */
class GradesController extends Controller
{
    /**
     * @var GradeRepository
     */
    protected $repository;

    /**
     * @var GradeValidator
     */
    protected $validator;

    /**
     * GradesController constructor. 
     * @param GradeValidator $validator
     */
    public function __construct(GradeRepository $repository, GradeValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    $this->middleware('guest',['only' => ['register']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $grades = $this->repository->all();


            return response()->json([
                'data' => $grades,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  GradeCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(GradeCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $grade = $this->repository->create($request->all());

            $response = [
                'message' => 'Grade created.',
                'data'    => $grade->toArray(),
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
        $grade = $this->repository->find($id);

            return response()->json([
                'data' => $grade,
            ]);

    }
       public function generate($id){
      $grade = $this->repository->find($id);
        $html = file_get_contents(storage_path().'/app/sketch/invitation_grade.htm');
      $html = str_replace('<key>',$grade->join_key,$html);
      $key = md5(mt_rand());
      PDF::loadHTML($html,'UTF-8')->setPaper('a4', 'potrait')->setWarnings(false)->save('../storage/app/public/downloads/glowne'.$key.'.pdf');
      file_put_contents(storage_path(). '/app/public/downloads/glowne'.$key.'.html',$html);
         $path =  config('app.url')."/storage/downloads/glowne".$key.".html";
        auth()->user()->notify(new NotifyGenerate($path));
     return response()->json(['to_main_url' => config('app.url')."/storage/downloads/glowne".$key.".pdf",'to_html_main_url'=>config('app.url')."/storage/downloads/glowne".$key.".html"]);
   }

    /**
     * Update the specified resource in storage.
     *
     * @param  GradeUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(GradeUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);
            $grade = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Grade updated.',
                'data'    => $grade->toArray(),
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
                'message' => 'Grade deleted.',
                'deleted' => $deleted,
            ]);
    }
    public function exists($name){
        $grade = $this->repository->with(['school' => function($query){$query->with('subscriptions');}])->findByField('name',$name);
          return response()->json(['message' => 'Checked exists this grade','exists' => !count($grade)==0,'data' => $grade[0]]);
    }
    public function posts($id,Request $request){
      if(auth()->user()->role_id<2){
           $posts = $this->repository->find($id)->posts()->with(['trusts' => function($query){$query->with('user');},'user' => function($query){ $query->with('point'); $query->with('role');},'comments' => function($query){
         $query->orderby('id','desc');
         $query->with(['user' => function($query){ $query->with('point'); $query->with('role');},'answers' => function($query){
           $query->orderby('id','desc');
           $query->with(['user' => function($query){ $query->with('point'); $query->with('role');}]);
         }]);
    }])->orderby('order','desc')->orderby('id','dsesc')->get();
        }else{
             $posts = $this->repository->find($id)->posts()->withTrashed()->with(['trusts' => function($query){$query->with('user');},'user' => function($query){ $query->with('point'); $query->with('role');},'comments' => function($query){
                 $query->withTrashed();
         $query->orderby('id','desc');
         $query->with(['user' => function($query){ $query->with('point'); $query->with('role');},'answers' => function($query){
           $query->withTrashed();
           $query->orderby('id','desc');
           $query->with(['user' => function($query){ $query->with('point'); $query->with('role');}]);
         }]);
    }])->orderby('order','desc')->orderby('id','dsesc')->get();
          }
       
 
               
               return response()->json(['data' => $posts]);
    }
    public function homeworks($id){
   $posts = $this->repository->find($id)->posts()->withTrashed()->where('content','LIKE','%'.'#homework'.'%')->with(['trusts' => function($query){$query->with('user');},'user' => function($query){ $query->with('point'); $query->with('role');},'comments' => function($query){
        $query->withTrashed();
        $query->orderby('id','desc');
           $query->with(['user' => function($query){  $query->with('point'); $query->with('role');},'answers' => function($query){
             $query->withTrashed();
                $query->orderby('id','desc');
             $query->with(['user' => function($query){ $query->with('point'); $query->with('role');}]);
           }]);
      }])->orderby('order','desc')->orderby('id','desc')->get();
   return response()->json(['data' => $posts]);
    }
        public function notes_from_lesson($id){
   $posts = $this->repository->find($id)->posts()->withTrashed()->where('content','LIKE','%'.'#notes_from_lesson'.'%')->with(['trusts' => function($query){$query->with('user');},'user' => function($query){ $query->with('point'); $query->with('role');},'comments' => function($query){
        $query->withTrashed();
        $query->orderby('id','desc');
           $query->with(['user' => function($query){  $query->with('point'); $query->with('role');},'answers' => function($query){
             $query->withTrashed();
                $query->orderby('id','desc');
             $query->with(['user' => function($query){ $query->with('point'); $query->with('role');}]);
           }]);
      }])->orderby('order','desc')->orderby('id','desc')->get();
   return response()->json(['data' => $posts]);
    }
    public function users($id){
   $grade = $this->repository->find($id);
   $users = $grade->users()->where('id', '!=', Auth::id())->with(['lock','role','grade','school','point','giveBadges' => function($query){$query->with('badge');}])->orderby('id','desc')->get();
   if($grade->type==2){
   $school = \App\Entities\School::with(['grades' => function($query){$query->with(['users' => function($query){
     $query->where('id', '!=', Auth::id());
   }]);
   }])->findorfail(auth()->user()->school_id);
      foreach($school->grades as $grade){
        $users = $grade->users->merge($users);
   }
   }
   return response()->json(['data' => $users]);
    }
      public function quizes($id){
   $grade = $this->repository->find($id);
   $quizes = $grade->quizes()->with(['questions'])->get();
   return response()->json(['data' => $quizes]);
   }
    public function schedule($id){
      $schedule = $this->repository->with(['schedule' => function($query){$query->with('rooms'); $query->with(['teachers' => function($query){$query->with('room');}]);$query->with(['days' => function($query){$query->orderby('order','asc');$query->with('events');$query->with(['lessons' => function($query){$query->orderby('order','asc');$query->with('room');$query->with('teacher');}]);}]);}])->find($id);
        $response = [
                'message' => 'Get schedule for grade',
                'data'    => $schedule->toArray(),
            ];
    return response()->json($response);
    }
    public function readings($id){
      $readings = $this->repository->with('readings')->find($id);
        $response = [
                'message' => 'Get readings for grade',
                'data'    => $readings->toArray(),
            ];
    return response()->json($response);
    }
    public function messagesAlls($id){
      if(auth()->user()->load('role')->role->id>1){
          $messages = $this->repository->with(['messagesAlls' => function($query){$query->withTrashed(); $query->with(['user' => function($query) { $query->with('point'); $query->with('role'); }]);}])->find($id);
      }else{
          $messages = $this->repository->with(['messagesAlls' => function($query){$query->with(['user' => function($query) { $query->with('point'); $query->with('role'); }]);}])->find($id);
      }
        $response = [
                'message' => 'Get messagesAll for grade',
                'data'    => $messages->toArray(),
            ];
    return response()->json($response);
    }
    public function postsSearch($query){
       $posts = $this->repository->find($id)->posts()->withTrashed()->where('content','LIKE','%'.$query.'%')->with(['trusts' => function($query){$query->with('user');},'user' => function($query){ $query->with('point'); $query->with('role');},'comments' => function($query){
        $query->withTrashed();
        $query->orderby('id','desc');
           $query->with(['user' => function($query){  $query->with('point'); $query->with('role');},'answers' => function($query){
             $query->withTrashed();
                $query->orderby('id','desc');
             $query->with(['user' => function($query){ $query->with('point'); $query->with('role');}]);
           }]);
      }])->orderby('order','desc')->orderby('id','desc')->get();
   return response()->json(['data' => $posts]);
    }
    public function register(GradeRegisterRequest $request){
      try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_REGISTER);
           if($request->has("verify_key")){
             $date = Carbon::now()->toDateTimeString();
           }else{
             $date = null;
             
           }
  $school = School::findorfail($request->input('school_id'));
            $grade = $this->repository->create([
              'name' => $request->input('name'),
              'description' => $request->input('description'),
              'school_id' => $school->id,
              'join_key' => str_random(10),
              'verifed_at' =>  $date,
              'type' => 1
            ]);
  $grade->schedule()->create();
            $avatar = "/images/avatars/avatar_user_".str_random(20).".png";
           Avatar::create($request->input('user_full_name'))->save(__DIR__."/../../../storage/app/public".$avatar);
           $avatar =   config('app.url')."/storage".$avatar;
             if($school->user_id==null){
               $role_id = 5;
               }else{
                 $role_id = 3;
                 }
            $user =  \App\Entities\User::create(['full_name' => $request->input('user_full_name'),'email' => $request->input('user_email'),'password' => Hash::make($request->input('user_password')),'role_id' => $role_id,'school_id' => $request->input('school_id'),'grade_id' => $grade->id,'avatar' => $avatar,'qr_code' => str_random(10),'social_code' => str_random(10)]);
           if($request->has("verify_key")==false){
              $user->notify(new NotifyVerify($user));
           }
     $grade->update(['user_id' => $user->id]);
           $school->update(['user_id' => $user->id]);
           $user->point()->create(['quantity' => 0]);
            $response = [
                'message' => 'User registred and grade created.',
                'data'    => ['grade' => $grade->toArray(),'user' => $user],
            ];

              
               return response()->json($response);

        } catch (ValidatorException $e) {
                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ],505);
        }
    }
        public function user(GradeRegisterRequest $request){
      try {
        $grade = $this->repository->with('user')->findByField('join_key',$request->input('join_key'));
            $avatar = "/images/avatars/avatar_user_".str_random(20).".png";
           Avatar::create($request->input('full_name'))->save(__DIR__."/../../../storage/app/public".$avatar);
                     $avatar =   config('app.url')."/storage".$avatar;
              $grade[0]->user->notify(new \App\Notifications\Notify("Do twojej klasy właśnie dołączył nowy użytkownik"));
            $user =  \App\Entities\User::create(['full_name' => $request->input('full_name'),'email' => $request->input('email'),'password' => Hash::make($request->input('password')),'role_id' => 1,'school_id' => $grade[0]->school_id,'grade_id' => $grade[0]->id,'avatar' => $avatar,'first_logged' => false]);
           $user->point()->create(['quantity' => 0]);
            $response = [
                'message' => 'User registred and grade created.',
                'data'    => ['grade' => $grade->toArray(),'user' => $user],
            ];


               return response()->json($response);

        } catch (ValidatorException $e) {
                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
        }
    }
  public function bearning($id){
   return response()->json(['message' => "Actually function doesn't working please a wait day"]);
    }
}
