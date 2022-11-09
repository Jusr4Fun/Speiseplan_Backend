<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teilnehmer;


class TeilnehmerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'message' => 'Rollen erfolgreich geladen',
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
            'abteilungs_id' => 'required',
        ]);

        $data = [
            'name' => $fields['name'],
            'abteilungs_id' => $fields['abteilungs_id'],
        ];

        $teilnehmer = Teilnehmer::create($data);
        
        return response()->json([
            'data' => $teilnehmer,
            'message' => 'Neuen Teilnehmer erfolgreich gespeichert',
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
            'abteilung_id' => 'required',
        ]);
        $teilnehmer = Teilnehmer::find($fields['id']);
        $teilnehmer->name = $fields['name'];
        $teilnehmer->abteilung_id = $fields['abteilung_id'];
        $teilnehmer->save();
        
        return response()->json([
            'message' => 'Teilnehmer erfolgreich aktualisiert',
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
        Teilnehmer::destroy($id);
        return response()->json([
            'message' => 'Teilnehmer erfolgreich gelöscht',
            'succes' => true,
        ],200);
    }
}
