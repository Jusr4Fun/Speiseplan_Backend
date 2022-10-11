<?php

namespace App\Http\Controllers;

use App\Models\Abteilung;
use Illuminate\Http\Request;

class AbteilungController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $abteilung=Abteilung::get();
        return response()->json([
            'data' => $abteilung,
            'message' => 'Alle Abteilungen erfolgreich übergeben',
            'succes' => true,
        ],200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexExpanded($id)
    {
        $data = Abteilung::where('id','=',$id)->with([                            
            'teilnehmer'
            ])->get()[0];
        $name = $data->name;
        $abteilungTeilnehmer = $data->teilnehmer;

        foreach( $abteilungTeilnehmer as $teilnehmer) {
            $teilnehmer->abteilung = $name;
        }
        return response()->json([
            'data' => $abteilungTeilnehmer,
            'message' => 'Alle Teilnehmer der Abteilung erfolgreich übergeben',
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
        $abteilung = Abteiulung::create([]);
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
            'name' => 'required|string|min:10|max:255',
        ]);

        $data = [
            'name' => $fields['name'],
        ];

        $abteilung = Abteilung::create($data);

        return response()->json([
            'data' => $abteilung,
            'message' => 'Neue Abteilung wurde erfolgreich gespeichert',
            'succes' => true,
        ],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Woche  $woche
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $abteilung=Abteilung::find($id);
        return response()->json([
            'data' => $abteilung,
            'message' => 'Einzelne Abteilung erfolgreich geladen',
            'succes' => true,
        ],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Woche  $woche
     * @return \Illuminate\Http\Response
     */
    public function edit(Woche $woche)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Woche  $woche
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Woche $woche)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Woche  $woche
     * @return \Illuminate\Http\Response
     */
    public function destroy(Woche $woche)
    {
        //
    }
}
