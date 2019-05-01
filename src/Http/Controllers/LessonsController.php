<?php

namespace Igorgawrys\Socialler\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\LessonCreateRequest;
use App\Http\Requests\LessonUpdateRequest;
use App\Repositories\LessonRepository;
use App\Validators\LessonValidator;
//use App\Notifications\LessonStatusNotify;
/**
 * Class LessonsController.
 *
 * @package namespace Test\Http\Controllers;
 */
class LessonsController extends Controller
{
    /**
     * @var LessonRepository
     */
    protected $repository;

    /**
     * @var LessonValidator
     */
    protected $validator;

    /**
     * LessonsController constructor.
     *
     * @param LessonRepository $repository
     * @param LessonValidator $validator
     */
    public function __construct(LessonRepository $repository, LessonValidator $validator)
    {
        $this->repository = $repository;
        $this->validator  = $validator;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  LessonCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(LessonCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $lesson = $this->repository->create($request->all());

            $response = [
                'message' => 'Lesson created.',
                'data'    => $lesson->toArray(),
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
     * Update the specified resource in storage.
     *
     * @param  LessonUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(LessonUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $lesson = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Lesson updated.',
                'data'    => $lesson->toArray(),
            ];
          \Notification::send(auth()->user()->load(["grade" => function($query){$query->with('users');}])->grade->users,new \App\Notifications\LessonStatusNotify());
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
                'message' => 'Lesson deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Lesson deleted.');
    }
}
