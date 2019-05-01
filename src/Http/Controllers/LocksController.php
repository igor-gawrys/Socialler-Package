<?php

namespace Igorgawrys\Socialler\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\LockCreateRequest;
use App\Http\Requests\LockUpdateRequest;
use App\Repositories\LockRepository;
use App\Validators\LockValidator;
use App\Events\LockCreatedEvent;
use App\Events\LockDeletedEvent;

/**
 * Class LocksController.
 *
 * @package namespace Igorgawrys\Socialler\Http\Controllers;
 */
class LocksController extends Controller
{
    /**
     * @var LockRepository
     */
    protected $repository;

    /**
     * @var LockValidator
     */
    protected $validator;

    /**
     * LocksController constructor.
     *
     * @param LockRepository $repository
     * @param LockValidator $validator
     */
    public function __construct(LockRepository $repository, LockValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

  

    /**
     * Store a newly created resource in storage.
     *
     * @param  LockCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(LockCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $lock = $this->repository->create(['content' => $request->input('content')]);
            $response = [
                'message' => 'Lock created.',
                'data'    => $lock->toArray(),
            ];
            event(new LockCreatedEvent($lock));
                return response()->json($response);
        } catch (ValidatorException $e) {
            if ($request->wantsJson()) {
                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
            }

            return redirect()->back()->withErrors($e->getMessageBag())->withInput();
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
                'message' => 'Lock deleted.',
                'deleted' => $deleted,
            ]);
         event(new LockDeletedEvent($lock));

        return redirect()->back()->with('message', 'Lock deleted.');
    }
}
