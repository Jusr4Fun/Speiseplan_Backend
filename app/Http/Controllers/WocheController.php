<?php

namespace App\Http\Controllers;

use App\Models\Woche;
use Illuminate\Http\Request;

class WocheController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wochen=Woche::get();
        return response()->json([
            'data' => $wochen,
            'message' => 'Alle Wochen erfolgreich übergeben',
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
        $woche = Woche::create([]);
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

        $woche = Woche::create($data);

        return response()->json([
            'data' => $woche,
            'message' => 'Neue Woche wurde erfolgreich gespeichert',
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
        $woche=Woche::find($id);
        return response()->json([
            'data' => $woche,
            'message' => 'Einzelne Woche erfolgreich geladen',
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
