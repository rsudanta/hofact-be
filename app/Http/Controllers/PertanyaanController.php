<?php

namespace App\Http\Controllers;

use App\Models\Jawaban;
use App\Models\Pertanyaan;
use Illuminate\Http\Request;

class PertanyaanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $questions = Pertanyaan::with(['user'])->paginate(20);
        return view('questions.index', [
            'questions' => $questions
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Pertanyaan $question)
    {
        $answers = Jawaban::with(['pertanyaan', 'user'])->where('id_pertanyaan', $question->id)->get();

        return view('questions.edit', [
            'question' => $question,
            'answers' => $answers
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pertanyaan $question)
    {
        $question->delete();
        return redirect()->route('questions.index');
    }

    public function searchQuestion(Request $request)
    {
        $keyword = $request->search;
        $questions = Pertanyaan::where('judul_pertanyaan', 'like', "%" . $keyword . "%")->orWhere('id', 'like', "%" . $keyword . "%")->paginate(5);
        return view('questions.index', compact('questions'))->with('i', (request()->input('page', 1) - 1) * 5);
    }
}
