<?php

namespace Igorgawrys\Socialler\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\BadgeCreateRequest;
use App\Http\Requests\BadgeUpdateRequest;
use App\Repositories\BadgeRepository;
use App\Validators\BadgeValidator;

/**
 * Class BadgesController.
 *
 * @package namespace Igorgawrys\Socialler\Http\Controllers;
 */
class BadgesController extends Controller
{
    /**
     * @var BadgeRepository
     */
    protected $repository;

    /**
     * @var BadgeValidator
     */
    protected $validator;

    /**
     * BadgesController constructor.
     *
     * @param BadgeRepository $repository
     * @param BadgeValidator $validator
     */
    public function __construct(BadgeRepository $repository, BadgeValidator $validator)
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
        $badges = $this->repository->all();

            return response()->json([
                'data' => $badges,
            ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  BadgeCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(BadgeCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $badge = $this->repository->create($request->all());

            $response = [
                'message' => 'Badge created.',
                'data'    => $badge->toArray(),
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
        $badge = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $badge,
            ]);
        }

        return view('badges.show', compact('badge'));
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
        $badge = $this->repository->find($id);

        return view('badges.edit', compact('badge'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  BadgeUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(BadgeUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $badge = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Badge updated.',
                'data'    => $badge->toArray(),
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
                'message' => 'Badge deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Badge deleted.');
    }
}
