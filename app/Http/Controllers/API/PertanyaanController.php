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
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(
                ['error' => $validator->errors()],
                'Post Question Validation Failed',
                401
            );
        }

        try {
            Pertanyaan::create([
                'judul_pertanyaan' => $request->judul_pertanyaan,
                'isi_pertanyaan' => $request->isi_pertanyaan,
                'id_user' => Auth::user()->id,
            ]);

            $data = Pertanyaan::where('judul_pertanyaan', $request->judul_pertanyaan)->first();

            return ResponseFormatter::success($data, 'Question successfully created');
        } catch (Exception $e) {
            return ResponseFormatter::error($e, 'Question is failed to create');
        }
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
            $file = $request->file->store('assets/pertanyaan', 'public');

            $pertanyaan = Pertanyaan::find($id);
            if(Auth::user()->id == $pertanyaan->id_user){
            $pertanyaan->gambar_url = $file;
            $pertanyaan->update();

            return ResponseFormatter::success([$file], 'File successfully uploaded');    
            }
            
        }

        // try {
        //     $request->validate([
        //         'judul_pertanyaan' => ['required', 'string', 'max:255','unique:pertanyaans'],
        //         'isi_pertanyaan' => ['required', 'string', 'max:255'],
        //     ]);

        //     Pertanyaan::create([
        //         'judul_pertanyaan' => $request->judul_pertanyaan,
        //         'isi_pertanyaan' => $request->isi_pertanyaan,
        //         'id_user' => Auth::user()->id,
        //     ]);

        //     $data = Pertanyaan::where('judul_pertanyaan', $request->judul_pertanyaan)->first();
        //     return ResponseFormatter::success($data,'Question successfully created');
        // } catch (Exception $e) {
        //     return ResponseFormatter::error($e,'Question is failed to create');
        // }
    }

    public function all(Request $request)
    {
        $id = $request->input('id');
        $judul_pertanyaan = $request->input('judul_pertanyaan');
        $isi_pertanyaan = $request->input('isi_pertanyaan');
        $id_user = $request->input('id_user');
        $limit = $request->input('limit');


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
        if ($isi_pertanyaan) {
            $question->where('isi_pertanyaan', 'like', '%' . $isi_pertanyaan . '%');
        }
        if ($id_user) {
            $question->where('id_user', $id_user);
        }

        return ResponseFormatter::success($question->orderBy('created_at', 'desc')->paginate($limit), 'Data list pertanyaan berhasil diambil');
    }
}
