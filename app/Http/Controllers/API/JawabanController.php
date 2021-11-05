<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Jawaban;
use App\Models\User;
use App\Models\Vote;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class JawabanController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'id_user' => 'exists:users,id',
            'id_pertanyaan' => 'required|exists:pertanyaans,id',
            'isi_jawaban' => 'required',
        ]);

        try {
            $answer = Jawaban::with(['user', 'pertanyaan'])->create([
                'id_user' => Auth::user()->id,
                'id_pertanyaan' => $request->id_pertanyaan,
                'isi_jawaban' => $request->isi_jawaban,
            ]);

            $user = Auth::user();
            $user->poin += 10;
            $user->save();

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
            $answer->total_vote += 1;
            $answer->save();
            $has_downvote->delete();

            $id_user = Jawaban::where('id', $id)->value('id_user');
            $user = User::where('id', $id_user)->first();
            $user->poin += 1;
            $user->save();
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
                    $answer->total_vote += 1;
                    $answer->save();

                    $id_user = Jawaban::where('id', $id)->value('id_user');
                    $user = User::where('id', $id_user)->first();
                    $user->poin += 1;
                    $user->save();

                    return ResponseFormatter::success($upvote, 'Upvote is success');
                } catch (Exception $e) {
                    return ResponseFormatter::error($e, 'Upvote is failed');
                }
            }
            return ResponseFormatter::error(null, 'Upvote is failed');
        } else {
            return ResponseFormatter::error(null, 'Anda sudah memberikan vote');
        }
    }

    public function downvote(Request $request, $id)
    {
        $has_upvote = Vote::where('id_user', Auth::user()->id)->where('id_jawaban', $id)->where('status', 'UPVOTE')->first();
        if ($has_upvote) {
            $answer = Jawaban::where('id', $id)->first();
            $answer->total_vote -= 1;
            $answer->save();
            $has_upvote->delete();

            $id_user = Jawaban::where('id', $id)->value('id_user');
            $user = User::where('id', $id_user)->first();
            $user->poin -= 1;
            $user->save();
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
                    $answer->total_vote -= 1;
                    $answer->save();

                    $id_user = Jawaban::where('id', $id)->value('id_user');
                    $user = User::where('id', $id_user)->first();
                    $user->poin -= 1;
                    $user->save();

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

    public function all(Request $request)
    {
        $id = $request->input('id');
        $id_pertanyaan = $request->input('id_pertanyaan');
        $id_user = $request->input('id_user');


        if ($id) {
            $question = Jawaban::find($id);

            if ($question) {
                return ResponseFormatter::success(
                    $question,
                    'Data jawaban berhasil diambil'
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Data jawaban tidak ditemukan',
                    404
                );
            }
        }

        $question = Jawaban::query();

        if ($id_pertanyaan) {
            $question->where('id_pertanyaan', $id_pertanyaan);
        }

        if ($id_user) {
            $question->where('id_user', $id_user);
        }

        return ResponseFormatter::success($question->orderBy('is_terverifikasi', 'desc')->orderBy('total_vote', 'desc')->paginate(), 'Data list jawaban berhasil diambil');
    }


    public function getVote(Request $request, $id)
    {

        $id_jawaban = $request->input('id_jawaban');

        $vote = Vote::where('id_user', $id)->get();

        return ResponseFormatter::success($vote, 'Data list jawaban berhasil diambil');
    }

    public function updatePhoto(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|image|max:2048'
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(
                ['error' => $validator->errors()],
                'Update photo fails',
                401
            );
        }

        if ($request->file('file')) {
            $file = $request->file->store('assets/jawaban', 'public');

            $jawaban = Jawaban::find($id);
            if (Auth::user()->id == $jawaban->id_user) {
                $jawaban->gambar_url = $file;
                $jawaban->update();

                return ResponseFormatter::success([$file], 'File successfully uploaded');
            }
        }
    }
}
