<?php

namespace Igorgawrys\Socialler\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\GiveBadgeCreateRequest;
use App\Http\Requests\GiveBadgeUpdateRequest;
use App\Repositories\GiveBadgeRepository;
use App\Validators\GiveBadgeValidator;

/**
 * Class GiveBadgesController.
 *
 * @package namespace Igorgawrys\Socialler\Http\Controllers;
 */
class GiveBadgesController extends Controller
{
    /**
     * @var GiveBadgeRepository
     */
    protected $repository;

    /**
     * @var GiveBadgeValidator
     */
    protected $validator;

    /**
     * GiveBadgesController constructor.
     *
     * @param GiveBadgeRepository $repository
     * @param GiveBadgeValidator $validator
     */
    public function __construct(GiveBadgeRepository $repository, GiveBadgeValidator $validator)
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
        $giveBadges = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $giveBadges,
            ]);
        }

        return view('giveBadges.index', compact('giveBadges'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  GiveBadgeCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(GiveBadgeCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $giveBadge = $this->repository->create(['badge_id' => $request->input('badge_id'),'user_id' => $request->input('user_id')]);

            $response = [
                'message' => 'GiveBadge created.',
                'data'    => $giveBadge->toArray(),
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
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $giveBadge = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $giveBadge,
            ]);
        }

        return view('giveBadges.show', compact('giveBadge'));
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
        $giveBadge = $this->repository->find($id);

        return view('giveBadges.edit', compact('giveBadge'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  GiveBadgeUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(GiveBadgeUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $giveBadge = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'GiveBadge updated.',
                'data'    => $giveBadge->toArray(),
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

            return response()->json([
                'message' => 'GiveBadge deleted.',
                'deleted' => $deleted,
            ]);
       
    }
}
