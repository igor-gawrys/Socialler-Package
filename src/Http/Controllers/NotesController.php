<?php

namespace Igorgawrys\Socialler\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use App\Http\Requests\NoteCreateRequest;
use App\Http\Requests\NoteUpdateRequest;
use App\Repositories\NoteRepository;
use App\Validators\NoteValidator;

/**
 * Class NotesController.
 *
 * @package namespace Igorgawrys\Socialler\Http\Controllers;
 */
class NotesController extends Controller
{
    /**
     * @var NoteRepository
     */
    protected $repository;

    /**
     * @var NoteValidator
     */
    protected $validator;

    /**
     * NotesController constructor.
     *
     * @param NoteRepository $repository
     * @param NoteValidator $validator
     */
    public function __construct(NoteRepository $repository, NoteValidator $validator)
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
        $notes = $this->repository->all();

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $notes,
            ]);
        }

        return view('notes.index', compact('notes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  NoteCreateRequest $request
     *
     * @return \Illuminate\Http\Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function store(NoteCreateRequest $request)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_CREATE);

            $note = $this->repository->create($request->all());

            $response = [
                'message' => 'Note created.',
                'data'    => $note->toArray(),
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
        $note = $this->repository->find($id);

        if (request()->wantsJson()) {

            return response()->json([
                'data' => $note,
            ]);
        }

        return view('notes.show', compact('note'));
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
        $note = $this->repository->find($id);

        return view('notes.edit', compact('note'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  NoteUpdateRequest $request
     * @param  string            $id
     *
     * @return Response
     *
     * @throws \Prettus\Validator\Exceptions\ValidatorException
     */
    public function update(NoteUpdateRequest $request, $id)
    {
        try {

            $this->validator->with($request->all())->passesOrFail(ValidatorInterface::RULE_UPDATE);

            $note = $this->repository->update($request->all(), $id);

            $response = [
                'message' => 'Note updated.',
                'data'    => $note->toArray(),
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
                'message' => 'Note deleted.',
                'deleted' => $deleted,
            ]);
        }

        return redirect()->back()->with('message', 'Note deleted.');
    }
}
