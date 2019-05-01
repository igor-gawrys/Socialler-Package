<?php

namespace Igorgawrys\Socialler\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\TrustCreateRequest;
use App\Repositories\TrustRepository;
use App\Validators\TrustValidator;
use Illuminate\Support\Facades\Auth;

/**
 * Class TrustsController.
 *
 * @package namespace Igorgawrys\Socialler\Http\Controllers;
 */
class TrustsController extends Controller
{
    /**
     * @var TrustRepository
     */
    protected $repository;

    /**
     * @var TrustValidator
     */
    protected $validator;

    /**
     * TrustsController constructor.
     *
     * @param TrustRepository $repository
     * @param TrustValidator $validator
     */
    public function __construct(TrustRepository $repository, TrustValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  TrustCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(TrustCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
            $trust = $this->repository->findWhere(['post_id' => $request->input('post_id'),'user_id' => Auth::id()]);
            if(count($trust)==0){
            $trust = $this->repository->create([
            	 'user_id' => Auth::id(),
            	 'post_id' => $request->input('post_id')
            	]);
           $this->repository->find($trust->id)->post()->first()->user()->first()->addPoint();
           $response = [
                'message' => 'Trust created.',
                'data'    => $trust->toArray(),
            ];
            }else{
             $this->repository->find($trust[0]->id)->post()->first()->user()->first()->subtractPoint();
             $deleted = $this->repository->delete($trust[0]->id);
              $response = [
                'message' => 'Trust deleted',
                'deleted'    => $deleted,
            ];
            }
         return response()->json($response);
        } catch (ValidatorException $e) {
                return response()->json([
                    'error'   => true,
                    'message' => $e->getMessageBag()
                ]);
        }
    }
    public function Is(Request $request){
    	 $trust = $this->repository->findWhere(['post_id' => $request->input('post_id'),'user_id' => Auth::id()]);
             return response()->json(['trust' => !count($trust)==0]);
    }
}
