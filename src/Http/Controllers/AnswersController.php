<?php

namespace Igorgawrys\Socialler\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\AnswerCreateRequest;
use App\Http\Requests\AnswerUpdateRequest;
use App\Repositories\AnswerRepository;
use App\Validators\AnswerValidator;
use Illuminate\Support\Facades\Auth;
use App\Events\AnswerCreatedEvent;
use App\Events\AnswerDeletedEvent;

/**
 * Class AnswersController.
 *
 * @package namespace Test\Http\Controllers;
 */
class AnswersController extends Controller
{
    /**
     * @var AnswerRepository
     */
    protected $repository;

    /**
     * @var AnswerValidator
     */
    protected $validator;

    /**
     * AnswersController constructor.
     *
     * @param AnswerRepository $repository
     * @param AnswerValidator $validator
     */
    public function __construct(AnswerRepository $repository, AnswerValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
         $this->middleware('answer_permission',['only' => ['update','destroy','show']]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  AnswerCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(AnswerCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
            $linkify = new \Nahid\Linkify\Linkify;
            $answer = $this->repository->create([
              'content' => $linkify->process($request->input('content')),
              'user_id' => Auth::id(),
              'comment_id' => $request->input('comment_id')
              ]);

            $response = [
                'message' => 'Answer created.',
                'data'    => $answer->toArray(),
            ];
               event(new AnswerCreatedEvent($answer,auth()->user()));
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
        $answer = $this->repository->find($id);


            return response()->json([
                'data' => $answer,
            ]);
       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  AnswerUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(AnswerUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $answer = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Answer updated.',
                'data'    => $answer->toArray(),
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
    {   $answer = $this->repository->find($id);
        $deleted = $this->repository->delete($id);
         event(new AnswerDeletedEvent($answer,auth()->user()));
            return response()->json([
                'message' => 'Answer deleted.',
                'deleted' => $deleted,
            ]);
    }
}
