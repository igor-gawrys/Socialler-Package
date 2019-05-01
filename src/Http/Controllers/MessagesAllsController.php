<?php

namespace Igorgawrys\Socialler\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\MessagesAllCreateRequest;
use App\Http\Requests\MessagesAllUpdateRequest;
use App\Repositories\MessagesAllRepository;
use App\Validators\MessagesAllValidator;
use App\Events\MessagesAllCreatedEvent;
use App\Events\MessagesAllDeletedEvent;
use Event;

/**
 * Class MessagesAllsController.
 *
 * @package namespace Igorgawrys\Socialler\Http\Controllers;
 */
class MessagesAllsController extends Controller
{
    /**
     * @var MessagesAllRepository
     */
    protected $repository;

    /**
     * @var MessagesAllValidator
     */
    protected $validator;

    /**
     * MessagesAllsController constructor.
     *
     * @param MessagesAllRepository $repository
     * @param MessagesAllValidator $validator
     */
    public function __construct(MessagesAllRepository $repository, MessagesAllValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

  
    /**
     * Store a newly created resource in storage.
     *
     * @param  MessagesAllCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(MessagesAllCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
            $linkify = new \Nahid\Linkify\Linkify;
            $messagesAll = $this->repository->create([
              'content' => $linkify->process($request->input('content')),
              'user_id' => auth()->user()->id,
              'grade_id' => $request->input('grade_id')
             ]);
 Event::fire(new MessagesAllCreatedEvent(auth()->user()->load('grade')->grade));
            $response = [
                'message' => 'MessagesAll created.',
                'data'    => $messagesAll->toArray(),
            ];
            
                return response()->json($response);
            return redirect()->back()->with('message', $response['message']);
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
 Event::fire(new MessagesAllDeletedEvent(auth()->user()->load('grade')->grade));
            return response()->json([
                'message' => 'MessagesAll deleted.',
                'deleted' => $deleted,
            ]);
    }
}
