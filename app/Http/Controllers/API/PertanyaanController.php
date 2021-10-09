<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Pertanyaan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PertanyaanController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul_pertanyaan' => ['required', 'string', 'max:255', 'unique:pertanyaans'],
            'isi_pertanyaan' => ['required', 'string'],
            'file' => ['image', 'max:2048']
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(
                ['error' => $validator->errors()],
                'POST Question Failed',
                401
            );
        }

        try {
            $file = '';
            if ($request->file) {
                $file = $request->file->store('assets/pertanyaan', 'public');
            }

            Pertanyaan::create([
                'judul_pertanyaan' => $request->judul_pertanyaan,
                'isi_pertanyaan' => $request->isi_pertanyaan,
                'id_user' => Auth::user()->id,
                'picture_path' => $file
            ]);
            $data = Pertanyaan::where('judul_pertanyaan', $request->judul_pertanyaan)->first();

            return ResponseFormatter::success($data, 'Question successfully created');
        } catch (Exception $e) {
            return ResponseFormatter::error($e, 'Question is failed to create');
        }
    }

    public function all(Request $request)
    {
        $id = $request->input('id');
        $judul_pertanyaan = $request->input('judul_pertanyaan');
        $id_user = $request->input('id_user');

        if ($id) {
            $question = Pertanyaan::find($id);

            if ($question) {
                return ResponseFormatter::success(
                    $question,
                    'Data pertanyaan berhasil diambil'
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    'Data pertanyaan tidak ditemukan',
                    404
                );
            }
        }

        $question = Pertanyaan::query();

        if ($judul_pertanyaan) {
            $question->where('judul_pertanyaan', 'like', '%' . $judul_pertanyaan . '%');
        }
        if ($id_user) {
            $question->where('id_user', 'like', '%' . $id_user . '%');
        }

        return ResponseFormatter::success($question->paginate(), 'Data list pertanyaan berhasil diambil');
    }
}
