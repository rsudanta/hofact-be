<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Jawaban;
use App\Models\Vote;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JawabanController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'id_user' => 'exists:users,id',
            'id_pertanyaan' => 'required|exists:pertanyaans,id',
            'isi_jawaban' => 'required',
            'file' => 'file|max:2048',
        ]);

        try {
            $file = $request->file->store('assets/jawaban', 'public');

            $answer = Jawaban::with(['user', 'pertanyaan'])->create([
                'id_user' => Auth::user()->id,
                'id_pertanyaan' => $request->id_pertanyaan,
                'isi_jawaban' => $request->isi_jawaban,
                'file' => $file,
            ]);
            return ResponseFormatter::success($answer, 'Post answer is success');
        } catch (Exception $e) {
            return ResponseFormatter::error($e, 'Post answer is failed');
        }
    }

    public function upvote(Request $request, $id)
    {

        $has_downvote = Vote::where('id_user', Auth::user()->id)->where('id_jawaban', $id)->where('status', 'DOWNVOTE')->first();
        if ($has_downvote) {
            $answer = Jawaban::where('id', $id)->first();
            $answer->vote += 1;
            $answer->save();
            $has_downvote->delete();
        }
        $user = Vote::where('id_user', Auth::user()->id)->where('id_jawaban', $id)->where('status', 'UPVOTE')->first();
        if ($user == null) {
            $request->validate([
                'id_user' => 'exists:users,id',
                'id_jawaban' => 'exists:jawabans,id',
            ]);
            $has_jawaban = Jawaban::where('id', $id)->first();
            if ($has_jawaban != null) {
                try {
                    $upvote = Vote::with(['user', 'jawaban'])->create([
                        'id_user' => Auth::user()->id,
                        'id_jawaban' => $id,
                        'status' => "UPVOTE",
                    ]);
                    $answer = Jawaban::where('id', $id)->first();
                    $answer->vote += 1;
                    $answer->save();
                    return ResponseFormatter::success($upvote, 'Upvote is success');
                } catch (Exception $e) {
                    return ResponseFormatter::error($e, 'Upvote is failed');
                }
            }
            return ResponseFormatter::error(null, 'Upvote is failed');
        } else {
            return ResponseFormatter::error(null, 'Upvote is failed');
        }
    }

    public function downvote(Request $request, $id)
    {
        $has_upvote = Vote::where('id_user', Auth::user()->id)->where('id_jawaban', $id)->where('status', 'UPVOTE')->first();
        if ($has_upvote) {
            $answer = Jawaban::where('id', $id)->first();
            $answer->vote -= 1;
            $answer->save();
            $has_upvote->delete();
        }
        $user = Vote::where('id_user', Auth::user()->id)->where('id_jawaban', $id)->where('status', 'DOWNVOTE')->first();
        if ($user == null) {
            $request->validate([
                'id_user' => 'exists:users,id',
                'id_jawaban' => 'exists:jawabans,id',
            ]);
            $has_jawaban = Jawaban::where('id', $id)->first();
            if ($has_jawaban != null) {
                try {
                    $downvote = Vote::with(['user', 'jawaban'])->create([
                        'id_user' => Auth::user()->id,
                        'id_jawaban' => $id,
                        'status' => "DOWNVOTE",
                    ]);

                    $answer = Jawaban::where('id', $id)->first();
                    $answer->vote -= 1;
                    $answer->save();
                    return ResponseFormatter::success($downvote, 'Downvote is success');
                } catch (Exception $e) {
                    return ResponseFormatter::error($e, 'Downvote is failed');
                }
            }
            return ResponseFormatter::error(null, 'Downvote is failed');
        } else {
            return ResponseFormatter::error(null, 'Downvote is failed');
        }
    }
}
