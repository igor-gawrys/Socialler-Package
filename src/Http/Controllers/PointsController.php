<?php

namespace Igorgawrys\Socialler\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\PointCreateRequest;
use App\Http\Requests\PointUpdateRequest;
use App\Repositories\PointRepository;
use App\Validators\PointValidator;
use App\Events\PointUpdatedEvent;

/**
 * Class PointsController.
 *
 * @package namespace Igorgawrys\Socialler\Http\Controllers;
 */
class PointsController extends Controller
{
    /**
     * @var PointRepository
     */
    protected $repository;

    /**
     * @var PointValidator
     */
    protected $validator;

    /**
     * PointsController constructor.
     *
     * @param PointRepository $repository
     * @param PointValidator $validator
     */
    public function __construct(PointRepository $repository, PointValidator $validator)
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
        $points = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $points,
            ]);
        }

        return view('points.index', compact('points'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  PointCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(PointCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $point = $this->repository->create($request->all());

            $response = [
                'message' => 'Point created.',
                'data'    => $point->toArray(),
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
        $point = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $point,
            ]);
        }

        return view('points.show', compact('point'));
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
        $point = $this->repository->find($id);

        return view('points.edit', compact('point'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  PointUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(PointUpdateRequest $request, $id)
    {
        try {
         
            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $point = $this->repository->update(['quantity' => $request->input('quantity')], $id);
            event(new PointUpdatedEvent($point));
            $response = [
                'message' => 'Point updated.',
                'data'    => $point->toArray(),
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

        if (request()->wantsJson()) {

            return response()->json([  
                'message' => 'Point deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Point deleted.');
    }
}
