<?php

namespace App\Http\Controllers;

use App\Models\Jawaban;
use App\Models\Pertanyaan;
use Illuminate\Http\Request;

class JawabanController extends Controller
{
    public function destroy(Jawaban $answer)
    {
        $answer->delete();
        return redirect()->route('questions.edit', $answer->pertanyaan->id);
    }
    public function verified($id)
    {
        $jawaban = Jawaban::where('id', $id)->first();
        $pertanyaan = Pertanyaan::where('id', $jawaban->pertanyaan->id)->first();
        $jawaban->is_terverifikasi = 1;
        $pertanyaan->is_terverifikasi = 1;
        $pertanyaan->save();
        $jawaban->save();

        return redirect()->route('questions.edit', $jawaban->pertanyaan->id);
    }
    public function notVerified($id)
    {
        $jawaban = Jawaban::where('id', $id)->first();
        $pertanyaan = Pertanyaan::where('id', $jawaban->pertanyaan->id)->first();
        $jawaban->is_terverifikasi = 0;
        $jawaban->save();

        $countVerified = Jawaban::where('id_pertanyaan', $jawaban->pertanyaan->id)->where('is_terverifikasi', 1)->count();
        if ($countVerified < 1) {
            $pertanyaan->is_terverifikasi = 0;
            $pertanyaan->save();
        }

        return redirect()->route('questions.edit', $jawaban->pertanyaan->id);
    }
}
