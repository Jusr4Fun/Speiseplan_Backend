<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Woche;
use Illuminate\Http\Request;
use \Datetime;
use \DatetimeZone;

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

    /**
     * Übergibt alle Wochenbestellungen der spezifizierten ID
     *
     * @return \Illuminate\Http\Response
     */
    public function returnWochenBestellungen($id) {
        $data = Woche::where('id','=',$id)->with([                            
            'wochenBestellungen'
            ])->get()[0];

        return response()->json([
                'data' => $data,
                'message' => 'Wochenbestellungen der Woche erfolgreich geladen',
                'succes' => true,
        ],200);
    }
    

    public function returnSpezialEssen($id)
    {
        $data = Woche::where('id','=',$id)->with([                            
            'wochenBestellungen.spezialEssen.teilnehmer',
            'wochenBestellungen.spezialEssen.wochentag',
            'wochenBestellungen.spezialEssen.essen'
            ])->get()[0];
        
        $test = json_encode($data);
        $test = json_decode($test);
        $spezialEssenArray = [];
        $essenAnzahl = array(array(0,0,0,0,0),array(0,0,0,0,0),array(0,0,0,0,0),array(0,0,0,0,0),array(0,0,0,0,0));
        foreach( $test->wochen_bestellungen as $wochen) {
            foreach( $wochen->spezial_essen as $essen) {
                if ( $this->istTvohanden($spezialEssenArray, $essen)) {
                foreach( $spezialEssenArray as $teilnehmer) {
                        if ( $teilnehmer->Teilnehmer_id == $essen->teilnehmer_id) {
                            $temp = $essen->wochentag->name;
                            $teilnehmer->$temp = $essen->essen->bezeichnung;
                        }
                    }
                }
                else {
                    $tempobj = new \stdClass();
                    $tempobj->Name = $essen->teilnehmer->name;
                    $tempobj->Montag = ''; 
                    $tempobj->Dienstag = ''; 
                    $tempobj->Mittwoch = ''; 
                    $tempobj->Donnerstag = ''; 
                    $tempobj->Freitag = '';
                    $tempobj->Teilnehmer_id = $essen->teilnehmer->id;
                    $temp = $essen->wochentag->name;
                    //$tempobj->essen[$essen->wochentag->name] = $essen->essen->bezeichnung;
                    $tempobj->$temp = $essen->essen->bezeichnung;
                    $spezialEssenArray[] = $tempobj;
                }
                $essenAnzahl[$essen->wochentag_id-1][$essen->essen_id-1]++;
            }
            $essenAnzahl[0][4] += $wochen->montag_normal ;
            $essenAnzahl[1][4] += $wochen->dienstag_normal ; 
            $essenAnzahl[2][4] += $wochen->mittwoch_normal ;
            $essenAnzahl[3][4] += $wochen->donnerstag_normal ;
            $essenAnzahl[4][4] += $wochen->freitag_normal ;
        }
        $test->Normal = [
                            'Name' => 'Normal',
                            'Montag' => $essenAnzahl[0][4], 
                            'Dienstag' => $essenAnzahl[1][4], 
                            'Mittwoch' => $essenAnzahl[2][4], 
                            'Donnerstag' => $essenAnzahl[3][4], 
                            'Freitag' => $essenAnzahl[4][4]
        ];
        $test->Vegetarisch = [
                            'Name' => 'Vegetarisch',
                            'Montag' => $essenAnzahl[0][0], 
                            'Dienstag' => $essenAnzahl[1][0], 
                            'Mittwoch' => $essenAnzahl[2][0], 
                            'Donnerstag' => $essenAnzahl[3][0], 
                            'Freitag' => $essenAnzahl[4][0]
                        ];
        $test->Vegan = [
                            'Name' => 'Vegan',
                            'Montag' => $essenAnzahl[0][1], 
                            'Dienstag' => $essenAnzahl[1][1], 
                            'Mittwoch' => $essenAnzahl[2][1], 
                            'Donnerstag' => $essenAnzahl[3][1], 
                            'Freitag' => $essenAnzahl[4][1]
                        ];
        $test->Glutenfrei = [
                            'Name' => 'Glutenfrei',
                            'Montag' => $essenAnzahl[0][2], 
                            'Dienstag' => $essenAnzahl[1][2], 
                            'Mittwoch' => $essenAnzahl[2][2], 
                            'Donnerstag' => $essenAnzahl[3][2], 
                            'Freitag' => $essenAnzahl[4][2]
                        ];
        $test->Laktosefrei = [
                            'Name' => 'Laktosefrei',
                            'Montag' => $essenAnzahl[0][3], 
                            'Dienstag' => $essenAnzahl[1][3], 
                            'Mittwoch' => $essenAnzahl[2][3], 
                            'Donnerstag' => $essenAnzahl[3][3], 
                            'Freitag' => $essenAnzahl[4][3]
                        ];

        foreach($spezialEssenArray as $teilnehmer) {
            $temp = $teilnehmer;

        }
        $test->teilnehmer = $spezialEssenArray;

        return response()->json([
            'data' => $test,
            'message' => 'Wochenbestellungen der Woche erfolgreich geladen',
            'succes' => true,
        ],200);
    }

    public function returnAktuelleWoche(Request $request) {

        $fields = $request->validate([
            'time' => 'required|int|min:1',
            'offset' => 'required|int',
        ]);
        
        $timezoneOffset = $fields['offset'];
        $time = $fields['time'];
        $time = $time /1000 - $timezoneOffset *60;
        //$time = $time + (3600*24*7);

        $date = date("Y-m-d H:i:s", $time);
        $temp = 'KW ' . date("W o", $time);
        $data = Woche::where('name','=',$temp)->get()[0];

        return response()->json([
            'data' => $data,
            'message' => 'Aktuelle Woche erfolgreich geladen',
            'succes' => true,
        ],200);
    }

    public function returnNaechsteWoche(Request $request) {

        $fields = $request->validate([
            'time' => 'required|int|min:1',
            'offset' => 'required|int',
        ]);
        
        $timezoneOffset = $fields['offset'];
        $time = $fields['time'];
        $time = $time /1000 - $timezoneOffset *60;
        $time = $time + (3600*24*7);

        $date = date("Y-m-d H:i:s", $time);
        $temp = 'KW ' . date("W o", $time);
        $data = Woche::where('name','=',$temp)->get()[0];

        return response()->json([
            'data' => $data,
            'message' => 'Nächste Woche erfolgreich geladen',
            'succes' => true,
        ],200);
    }

    private function istTvohanden($spezial, $essen) {
        $returnValue = false;
        foreach( $spezial as $teilnehmer) {
                if ( $teilnehmer->Teilnehmer_id == $essen->teilnehmer_id) {
                    $returnValue = true;
                }
            }
        return $returnValue;
    }

}
