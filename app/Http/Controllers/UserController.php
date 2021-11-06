<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Models\Vote;
use App\Models\Pertanyaan;
use App\Models\Jawaban;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::paginate(20);
        return view('users.index', [
            'users' => $users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('users.index');
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
    public function edit(User $user)
    {
        return view('users.edit', [
            'item' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $data = $request->all();
        $user->update($data);

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $pertanyaan = Pertanyaan::where('id_user',$user->id);
        $jawaban = Jawaban::where('id_user',$user->id);
        $vote = Vote::where('id_user',$user->id);
        $pertanyaan->delete();
        $jawaban->delete();
        $vote->delete();
        $user->delete();
        return redirect()->route('users.index');
    }

    public function searchUser(Request $request)
    {
        $keyword = $request->search;
        $users = User::where('name', 'like', "%" . $keyword . "%")->orWhere('email', 'like', "%" . $keyword . "%")->orWhere('id', 'like', "%" . $keyword . "%")->orWhere('role', 'like', "%" . $keyword . "%")->paginate(5);
        return view('users.index', compact('users'))->with('i', (request()->input('page', 1) - 1) * 5);
    }
}
