<?php

namespace Igorgawrys\Socialler\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\ReadingCreateRequest;
use App\Http\Requests\ReadingUpdateRequest;
use App\Repositories\ReadingRepository;
use App\Validators\ReadingValidator;

/**
 * Class ReadingsController.
 *
 * @package namespace Igorgawrys\Socialler\Http\Controllers;
 */
class ReadingsController extends Controller
{
    /**
     * @var ReadingRepository
     */
    protected $repository;

    /**
     * @var ReadingValidator
     */
    protected $validator;

    /**
     * ReadingsController constructor.
     *
     * @param ReadingRepository $repository
     * @param ReadingValidator $validator
     */
    public function __construct(ReadingRepository $repository, ReadingValidator $validator)
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
        $readings = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $readings,
            ]);
        }

        return view('readings.index', compact('readings'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ReadingCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(ReadingCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $reading = $this->repository->create($request->all());

            $response = [
                'message' => 'Reading created.',
                'data'    => $reading->toArray(),
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
        $reading = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $reading,
            ]);
        }

        return view('readings.show', compact('reading'));
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
        $reading = $this->repository->find($id);

        return view('readings.edit', compact('reading'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ReadingUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(ReadingUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $reading = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Reading updated.',
                'data'    => $reading->toArray(),
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
                'message' => 'Reading deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Reading deleted.');
    }
}
