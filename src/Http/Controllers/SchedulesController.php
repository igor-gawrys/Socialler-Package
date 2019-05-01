<?php

namespace Igorgawrys\Socialler\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\ScheduleCreateRequest;
use App\Http\Requests\ScheduleUpdateRequest;
use App\Repositories\ScheduleRepository;
use App\Validators\ScheduleValidator;

/**
 * Class SchedulesController.
 *
 * @package namespace Test\Http\Controllers;
 */
class SchedulesController extends Controller
{
    /**
     * @var ScheduleRepository
     */
    protected $repository;

    /**
     * @var ScheduleValidator
     */
    protected $validator;

    /**
     * SchedulesController constructor.
     *
     * @param ScheduleRepository $repository
     * @param ScheduleValidator $validator
     */
    public function __construct(ScheduleRepository $repository, ScheduleValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->repository->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
        $schedules = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $schedules,
            ]);
        }

        return view('schedules.index', compact('schedules'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ScheduleCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(ScheduleCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $schedule = $this->repository->create($request->all());

            $response = [
                'message' => 'Schedule created.',
                'data'    => $schedule->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
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
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $schedule = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $schedule,
            ]);
        }

        return view('schedules.show', compact('schedule'));
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
        $schedule = $this->repository->find($id);

        return view('schedules.edit', compact('schedule'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ScheduleUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(ScheduleUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $schedule = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Schedule updated.',
                'data'    => $schedule->toArray(),
            ];

            if ($request->wantsJson()) {

                return response()->json($response);
            }

            return redirect()->back()->with('message', $response['message']);
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

        if (request()->wantsJson()) {

            return response()->json([
                'message' => 'Schedule deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Schedule deleted.');
    }
}
