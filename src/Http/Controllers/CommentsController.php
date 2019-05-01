<?php

namespace Igorgawrys\Socialler\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\CommentCreateRequest;
use App\Http\Requests\CommentUpdateRequest;
use App\Repositories\CommentRepository;
use App\Validators\CommentValidator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use App\Events\CommentCreatedEvent;
use App\Events\CommentDeletedEvent;
/**
 * Class CommentsController.
 *
 * @package namespace Test\Http\Controllers;
 */
class CommentsController extends Controller
{
    /**
     * @var CommentRepository
     */
    protected $repository;

    /**
     * @var CommentValidator
     */
    protected $validator;

    /**
     * CommentsController constructor.
     *
     * @param CommentRepository $repository
     * @param CommentValidator $validator
     */
    public function __construct(CommentRepository $repository, CommentValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
         $this->middleware('comment_permission',['only' => ['update','destroy','show']]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  CommentCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(CommentCreateRequest $request)
    {
        try {
               $user = auth()->user()->load(['role']);
               $images = $request->input('images');
               $images_data = array();
              foreach($images as $image){
                 $exploded = explode(",",$image);
                  if(str_contains($exploded[0],'gif')){
                      $ext = 'gif';
                  }else if(str_contains($exploded[0],'png')){
                      $ext = 'png';
                  } else {
                      $ext = 'jpg';
                  }
                  $filename = str_random().'.'.$ext;
                    \File::put(storage_path(). '/app/public/images/posts/' .$filename,base64_decode($exploded[1]));
                    array_push($images_data,Config::get('app.url')."/storage/images/posts/".$filename);
              }
            if($images_data==array()){
                $type=1;
              }else{
                $type=2;
              }
            $linkify = new \Nahid\Linkify\Linkify;
            if(strpbrk($request->input('content'),'#important')==false AND $user->role->id>2)
            {
              $create = [
              'content' => $request->input('content'),
              'user_id' => $user->id,
              'type' => $type,
              'post_id' => $request->input('post_id'),
              'type' => $type,
              'images' => $images_data
            ];
            }else{
                           $create = [
                 'content' => $linkify->process($request->input('content')),
              'user_id' => $user->id,
              'type' => $type,
              'post_id' => $request->input('post_id'),
              'order' => 2,
              'type' => $type,
              'images' =>  $images_data
            ]; 
            }
            
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $comment = $this->repository->create($create); 
            
            $response = [
                'message' => 'Comment created.',
                'data'    => $comment->toArray(),
            ];

             event(new CommentCreatedEvent($comment,auth()->user()));
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
        $comment = $this->repository->find($id);


            return response()->json([
                'data' => $comment,
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CommentUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(CommentUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $comment = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Comment updated.',
                'data'    => $comment->toArray(),
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
      //Deletead relatioship
      $comment = $this->repository->find($id);
      $comment->answers()->delete();
      //End deledet relationship
        $deleted = $this->repository->delete($id);
       event(new CommentDeletedEvent($comment,auth()->user()));
            return response()->json([
                'message' => 'Comment deleted.',
                'deleted' => $deleted,
            ]);

    }
}
