<?php

namespace Igorgawrys\Socialler\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\SchoolCreateRequest;
use App\Http\Requests\SchoolUpdateRequest;
use App\Http\Requests\SchoolBuyRequest;
use App\Repositories\SchoolRepository;
use Laravolt\Avatar\Facade as Avatar;
use App\Validators\SchoolValidator;
use Carbon\Carbon;
/**
 * Class SchoolsController.
 *
 * @package namespace Test\Http\Controllers;
 */
class SchoolsController extends Controller
{
    /**
     * @var SchoolRepository
     */
    protected $repository;

    /**
     * @var SchoolValidator
     */
    protected $validator;

    /**
     * SchoolsController constructor.
     *
     * @param SchoolRepository $repository
     * @param SchoolValidator $validator
     */
    public function __construct(SchoolRepository $repository, SchoolValidator $validator)
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
 
        $schools = $this->repository->all();
            return response()->json([
                'data' => $schools,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  SchoolCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(SchoolCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);
             $avatar = "/images/avatars/avatar_user_".str_random(20).".png";
           Avatar::create($request->input('name'))->save(__DIR__."/../../../storage/app/public".$avatar);
           $avatar = "http://api.socialler.pl/storage".$avatar;
           $short_name = $request->input('name');
           $verify_key  = md5(mt_rand());
            $school = $this->repository->create(array_merge($request->all(),["avatar" => $avatar,"short_name" => $short_name,'verify_key'=>$verify_key]));
            
            $response = [
                'message' => 'School created.',
                'data'    => $school->toArray(),
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
        $school = $this->repository->with(['user' => function($query){$query->with('role');$query->with('point');$query->with('grade');$query->with('schools');},'grades','subscriptions'])->find($id);

            return response()->json([
                'data' => $school,
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  SchoolUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(SchoolUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $school = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'School updated.',
                'data'    => $school->toArray(),
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
 public function users($id){
   $school = $this->repository->find($id);
   $users = $school->users()->with(['lock','role','grade','school','point','giveBadges' => function($query){$query->with('badge');}])->orderby('id','desc')->get();
    return response()->json(['data' => $users]);
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
      $school = $this->repository->find($id);
      $grades = $school->grades()->get();
      ////////////////////////////////////////////////////////
      //                                                    //
      //                     ZRÓB TO  !!                    //
      //                                                    //
      ////////////////////////////////////////////////////////
    //  foreach($grades as $grade)
    //  {
    //    $grade->users()->delete();
    //    $grade->posts()->delete();
    //    $grade->readings()->delete();
    ///    $grade->schedule()->delete();
    ///    $grade->comments()->delete();
    ///    $grade->lessons()->delete();
    //    $grade->locks()->delete();
    //    $grade->answers()->delete();
    //    $grade->rooms()->delete();
    //    $grade->trusts()->delete();
    //    $grade->points()->delete();
    //    $grade->teachers()->delete();
    //  }
        $deleted = $this->repository->delete($id);
            return response()->json([
                'message' => 'School deleted.',
                'deleted' => $deleted,
            ]);
    }
    public function Buy($id,$subscription){
  $school = $this->repository->find($id);
       $subscription =  $school->newSubscription($subscription,$subscription)->create();
       $subscription->update(["ends_at" => Carbon::now()->addDays(365)]);
            $response = [
                'message' => 'School subscription buyied.',
                'data'    => $school->toArray(),
            ];

                return response()->json($response);
    }
    public function updateCard($id,Request $request){
        //Create Stripe Token
\Stripe\Stripe::setApiKey(config("services.stripe.secret"));
$card = \Stripe\Token::create(array(
  "card" => array(
    "number"    => $request->input('card_number'),
    "exp_month" => $request->input('exp_month'),
    "exp_year"  => $request->input('exp_year'),
    "cvc"       => $request->input('cvc'),
    "name"      => $request->input('name'),
)));
    
       $school = $this->repository->update(["stripe_id"=>$card->card->id,"card_brand" => $card->card->brand,"card_last_four"=>$card->card->last4], $id);
      $school->createAsStripeCustomer("tok_visa");
         return response()->json(["data" => $school,"card" => $card]);      
      }
    public function subscriptions($id){
      $school = $this->repository->find($id);
           return response()->json(['data' => $school->subscriptions()->get()]);
      }
    public function subscription($id){
      $school = $this->repository->find($id);
       return response()->json(['data' => $school->subscriptions()->first()]);
    }
    public function posts($id){
      $school = $this->repository->find($id);
           return response()->json(['data' => $school->posts()->with(['trusts' => function($query){$query->with('user');},'user' => function($query){ $query->with('point'); $query->with('role');},'comments' => function($query){
         $query->orderby('id','desc');
         $query->with(['user' => function($query){ $query->with('point'); $query->with('role');},'answers' => function($query){
           $query->orderby('id','desc');
           $query->with(['user' => function($query){ $query->with('point'); $query->with('role');}]);
         }]);
    }])->orderby('id','desc')->get()]);
    }
}
