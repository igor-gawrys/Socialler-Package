<?php

namespace Igorgawrys\Socialler\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\QuizCreateRequest;
use App\Http\Requests\QuizUpdateRequest;
use App\Repositories\QuizRepository;
use App\Validators\QuizValidator;
use App\Events\QuizJoinedEvent;
use App\Events\QuizGivedEvent;
/**
 * Class QuizzesController.
 *
 * @package namespace Igorgawrys\Socialler\Http\Controllers;
 */
class QuizzesController extends Controller
{
    /**
     * @var QuizRepository
     */
    protected $repository;

    /**
     * @var QuizValidator
     */
    protected $validator;

    /**
     * QuizzesController constructor.
     *
     * @param QuizRepository $repository
     * @param QuizValidator $validator
     */
    public function __construct(QuizRepository $repository, QuizValidator $validator)
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
        $quizzes = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $quizzes,
            ]);
        }

        return view('quizzes.index', compact('quizzes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  QuizCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(QuizCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $quiz = $this->repository->create($request->all());

            $response = [
                'message' => 'Quiz created.',
                'data'    => $quiz->toArray(),
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
        $quiz = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $quiz,
            ]);
        }

        return view('quizzes.show', compact('quiz'));
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
        $quiz = $this->repository->find($id);

        return view('quizzes.edit', compact('quiz'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  QuizUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(QuizUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $quiz = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Quiz updated.',
                'data'    => $quiz->toArray(),
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
                'message' => 'Quiz deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Quiz deleted.');
    }
   public function joint($id){
       $quiz = $this->repository->find($id);
        event(new QuizJoinedEvent(auth()->user(),$quiz));
     return response()->json(['message' => 'Quiz joined.']);
     }
     public function giveAnswer(Request $request,$id){
       $quiz = $this->repository->find($id);
        event(new QuizGivedEvent(auth()->user(),$quiz,$request->input('answer_number')));
     return response()->json(['message' => 'Give answer.']);
     }
}
