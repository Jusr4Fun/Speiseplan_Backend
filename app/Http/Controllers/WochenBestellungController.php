<?php

namespace App\Http\Controllers;

use App\Models\WochenBestellung;
use Illuminate\Http\Request;

class WochenBestellungController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wochen=WochenBestellung::get();
        return response()->json([
            'data' => $wochen,
            'message' => 'Alle Wochen Bestellungen erfolgreich übergeben',
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\WochenBestellung  $wochenBestellung
     * @return \Illuminate\Http\Response
     */
    public function show(WochenBestellung $wochenBestellung)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\WochenBestellung  $wochenBestellung
     * @return \Illuminate\Http\Response
     */
    public function edit(WochenBestellung $wochenBestellung)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\WochenBestellung  $wochenBestellung
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WochenBestellung $wochenBestellung)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\WochenBestellung  $wochenBestellung
     * @return \Illuminate\Http\Response
     */
    public function destroy(WochenBestellung $wochenBestellung)
    {
        //
    }
}
