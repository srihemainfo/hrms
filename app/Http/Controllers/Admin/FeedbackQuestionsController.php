<?php

namespace App\Http\Controllers\Admin;

use App\Models\Feedback_questions;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class FeedbackQuestionsController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Feedback_questions::query()->select(sprintf('%s.*', (new Feedback_questions)->table));
            $table = DataTables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = '';
                $editGate = 'feedback_question_edit';
                $deleteGate = 'feedback_question_delete';
                $crudRoutePart = 'feedback_questions';

                return view('partials.datatablesActions', compact(
                    'viewGate',
                    'editGate',
                    'deleteGate',
                    'crudRoutePart',
                    'row'
                )
                );
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('category', function ($row) {
                return $row->category ? $row->category : '';
            });
            $table->editColumn('questions', function ($row) {
                return $row->questions ? $row->questions : '';
            });

            $table->rawColumns(['actions', 'placeholder']);

            return $table->make(true);
        }
        return view('admin.feedback_question.index');
    }

    public function create()
    {
        return view('admin.feedback_question.create');
    }


    public function store(Request $request)
    {
        $Feedback_questions = Feedback_questions::create($request->all());

        return redirect()->route('admin.feedback_questions.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Feedback_questions  $feedback_questions
     * @return \Illuminate\Http\Response
     */
    public function show(Feedback_questions $feedback_questions)
    {
        //
    }


    public function edit(Feedback_questions $feedback_questions, Request $request, $id)
    {

        $datas = Feedback_questions::where('id', $id)->first();

        return view('admin.feedback_question.edit', compact('datas'));
    }


    public function update(Request $request, Feedback_questions $feedback_questions)
    {
        //    dd($request->id);
        $updatedData = [
            'category' => $request->category != '' ? $request->category : null,
            'questions' => $request->questions != '' ? $request->questions : null,
            'answertype' => $request->answertype != '' ? $request->answertype : null,
        ];
        Feedback_questions::where('id', $request->id)->update($updatedData);

        return redirect()->route('admin.feedback_questions.index');

    }


    public function destroy($request)
    {
        $Feedback_questions=Feedback_questions::find($request);
        $Feedback_questions->delete();

        return back();
    }
}
