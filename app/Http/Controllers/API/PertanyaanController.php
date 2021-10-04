<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Pertanyaan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PertanyaanController extends Controller
{
    public function create(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'judul_pertanyaan' => ['required', 'string', 'max:255', 'unique:pertanyaans'],
            'isi_pertanyaan' => ['required', 'string', 'max:255'],
        ]);
        if ($validator->fails()) {
            return ResponseFormatter::error(
                ['error' => $validator->errors()],
                'POST Question Failed',
                401
            );
        }
        Pertanyaan::create([
            'judul_pertanyaan' => $request->judul_pertanyaan,
            'isi_pertanyaan' => $request->isi_pertanyaan,
            'id_user' => Auth::user()->id,
        ]);
        $data = Pertanyaan::where('judul_pertanyaan', $request->judul_pertanyaan)->first();

        return ResponseFormatter::success($data, 'Question successfully created');
    }
}
