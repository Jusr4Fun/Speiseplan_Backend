<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user=User::get();
        return response()->json([
            'data' => $user,
            'message' => 'Nutzerindex erfolgreich geladen',
            'succes' => true,
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = User::create([]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string|min:2|max:20',
            'abteilung' => 'required|string|min:2|max:99',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:10|max:20',
        ]);

        $data = [
            'name' => $fields['name'],
            'abteilung' => $fields['abteilung'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
        ];

        $user = User::create($data);
        
        return response()->json([
            'data' => $user,
            'message' => 'Neuen Nutzer erfolgreich gespeichert',
            'succes' => true,
        ],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user=User::find($id);
        return response()->json([
            'data' => $user,
            'message' => 'Einzelnen Nutzer erfolgreich geladen',
            'succes' => true,
        ],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $fields = $request->validate([
            'id' => 'required',
            'name' => 'required|string|min:2|max:99',
            'abteilung' => 'required|string|min:2|max:99',
            'email' => 'required|email|unique:users,email,'.$request->id,
        ]);
        
        $user = User::find($fields['id']);
        $user->name = $fields['name'];
        $user->abteilung = $fields['abteilung'];
        $user->email = $fields['email'];
        $user->save();
        
        return response()->json([
            'message' => 'Nutzer erfolgreich aktualisiert',
            'succes' => true,
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::destroy($id);
        return response()->json([
            'message' => 'Nutzer erfolgreich gelöscht',
            'succes' => true,
        ],200);
    }
}
