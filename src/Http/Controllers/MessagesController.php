<?php

namespace Igorgawrys\Socialler\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\MessageCreateRequest;
use App\Http\Requests\MessageUpdateRequest;
use App\Repositories\MessageRepository;
use App\Validators\MessageValidator;
use App\Events\MessageCreatedEvent;
use App\Events\MessageTypedEvent;
use App\Events\MessageDeletedEvent;
use Illuminate\Support\Facades\Auth;
use App\Entities\User;
use App\Criteria\MessagesUserCriteria;
use Carbon\Carbon;
use Event;
/**
 * Class MessagesController.
 *
 * @package namespace Test\Http\Controllers;
 */
class MessagesController extends Controller
{
    /**
     * @var MessageRepository
     */
    protected $repository;

    /**
     * @var MessageValidator
     */
    protected $validator;

    /**
     * MessagesController constructor.
     *
     * @param MessageRepository $repository
     * @param MessageValidator $validator
     */
    public function __construct(MessageRepository $repository, MessageValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  MessageCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(MessageCreateRequest $request)
    {
        try {
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
            $linkify = new \Nahid\Linkify\Linkify;
            $message = $this->repository->create([
              'content' => $linkify->process($request->input('content')),
              'written_id' => Auth::id(),
              'user_id' => $request->input('user_id'),
              'images' => $request->input('images'),
              'files' => $request->input('files')
              ]);

            $response = [
                'message' => 'Message created.',
                'data'    => $message->toArray(),
            ];
            $user = User::find($request->input('user_id'));
             Event::fire(new MessageCreatedEvent($user,$message));
            $user->notify(new \App\Notifications\NotifyMessage());
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
      $message = $this->repository->find($id);
        $deleted = $this->repository->delete($id);
          $user = User::find($message->user_id);
        Event::fire(new MessageDeletedEvent($user,$message));
            return response()->json([
                'message' => 'Message deleted.',
                'deleted' => $deleted,
            ]);

    }
    public function user($user_id){
                   $messages = $this->repository->UserGetMessages($user_id);
           return response()->json([
                'data' => $messages,
            ]);
    }
}
