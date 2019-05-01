<?php

namespace Igorgawrys\Socialler\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Repositories\PostRepository;
use App\Validators\PostValidator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Events\PostCreatedEvent;
use App\Events\PostDeletedEvent;
use Carbon\Carbon;

/**
 * Class PostsController.
 *
 * @package namespace Test\Http\Controllers;
 */
class PostsController extends Controller
{
    /**
     * @var PostRepository
     */
    protected $repository;

    /**
     * @var PostValidator
     */
    protected $validator;

    /**
     * PostsController constructor.
     *
     * @param PostRepository $repository
     * @param PostValidator $validator
     */
    public function __construct(PostRepository $repository, PostValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
        $this->middleware('post_permission',['only' => ['update','destroy','show']]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  PostCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(PostCreateRequest $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
            $user = auth()->user()->load(['role']);
               $images = $request->input('images');
               $files = $request->input('files');
       $videos = $request->input('videos');
          if($request->has('grade_id')){
              $grade_id = $request->input('grade_id');
              }else{
              $grade_id = null;
              }
                if($request->has('school_id')){
              $school_id = $request->input('school_id');
              }else{
              $school_id = null;
              }
            $linkify = new \Nahid\Linkify\Linkify;
            if(strpbrk($request->input('content'),'#important')==false AND $user->role->id>2)
            {
              $create = [
              'content' =>  $linkify->process($request->input('content')),
              'user_id' => $user->id,
              'grade_id' => $grade_id,
              'school_id' => $school_id,
              'images' => $images,
                   'videos' => $videos,
                'files' => $files
            ];
            }else{
                           $create = [
              'content' => $linkify->process($request->input('content')),
              'user_id' => $user->id,
              'grade_id' => $grade_id,
              'school_id'=> $school_id,
              'order' => 2,
                 'images' => $images,
                   'videos' => $videos,
                'files' => $files           
                             ]; 
            }
            if($grade_id  !=null){
              $users = \App\Entities\Grade::with(['users' => function($query){$query->where('id','!=',auth()->user()->id);}])->find(auth()->user()->load(['grade'])->grade->id)->users;
            if(strpbrk($request->input('content'),'#homework')){
                       \Notification::send($users,new \App\Notifications\NotifyHomework());
            }else{
            $user = auth()->user()->load('grade');
            $grade =  \App\Entities\Grade::findorfail($request->input('grade_id'));
            $type = $grade->type;
              if($type==1){
                  \Notification::send($users,new \App\Notifications\NotifyPost());
              }else if($type==2){
                  $school = \App\Entities\School::with(['grades' => function($query){$query->with('users');}])->findorfail($grade->school_id);
                foreach($school->grades as $grade){
                  \Notification::send($grade->users,new \App\Notifications\NotifyPostAutonomy());
                }
              }
              
            }
            }
            $post = $this->repository->create($create);
                    event(new PostCreatedEvent($post));
                 
            $response = [
                'message' => 'Post created.',
                'data'    => $post->toArray(),
            ];
    
                return response()->json($response);
          
        } catch (ValidatorException $e) {
                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag(),
                ],401);
            
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
        $post = $this->repository->find($id);
            return response()->json([
                'data' => $post,
            ]);

    }

   
    /**
     * Update the specified resource in storage.
     *
     * @param  PostUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(PostUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $post = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Post updated.',
                'data'    => $post->toArray(),
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
       //Deleted relationship
      $post = $this->repository->find($id);
      $comments = $post->comments()->get();
      foreach($comments as $comment){
        $comment->answers()->delete();
      }
        $post->comments()->delete();
        //End deledet relationship
        $deleted = $this->repository->delete($id);
                 event(new PostDeletedEvent($post));
        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'Post deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Post deleted.');
    }
    public function pick($id){
        $post = $this->repository->update(['order' => 2], $id);
            $response = [
                'message' => 'Post picked.',
                'data'    => $post->toArray(),
            ];
                return response()->json($response);
    }
      public function unpick($id){
        $post = $this->repository->update(['order' => 1], $id);
            $response = [
                'message' => 'Post picked.',
                'data'    => $post->toArray(),
            ];
                return response()->json($response);
    }
}
