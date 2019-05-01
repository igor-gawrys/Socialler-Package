<?php

namespace Igorgawrys\Socialler\Http\Controllers;
use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\DayCreateRequest;
use App\Http\Requests\DayUpdateRequest;
use App\Repositories\DayRepository;
use App\Validators\DayValidator;

/**
 * Class DaysController.
 *
 * @package namespace Test\Http\Controllers;
 */
class DaysController extends Controller
{
    /**
     * @var DayRepository
     */
    protected $repository;

    /**
     * @var DayValidator
     */
    protected $validator;

    /**
     * DaysController constructor.
     *
     * @param DayRepository $repository
     * @param DayValidator $validator
     */
    public function __construct(DayRepository $repository, DayValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  DayCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(DayCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $day = $this->repository->create($request->all());

            $response = [
                'message' => 'Day created.',
                'data'    => $day->toArray(),
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
     * Update the specified resource in storage.
     *
     * @param  DayUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(DayUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $day = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Day updated.',
                'data'    => $day->toArray(),
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
      
        $lessons = $this->repository->find($id)->lessons();
       foreach($lessons as $lesson){
         $lesson->delete();
         }
        $deleted = $this->repository->delete($id);

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'Day deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Day deleted.');
    }
}
