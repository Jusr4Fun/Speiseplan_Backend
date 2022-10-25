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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function returnTeilnehmerName($id)
    {
        $data = Abteilung::where('id','=',$id)->with([                            
            'teilnehmer'
            ])->get()[0];
        $name = $data->name;
        $abteilungTeilnehmer = $data->teilnehmer;
        $teilnehmerName = [];
        foreach( $abteilungTeilnehmer as $teilnehmer) {
            $teilnehmerName[] = $teilnehmer->name;
        }
        return response()->json([
            'data' => $teilnehmerName,
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
     * Display the specified resource.
     *
     * @param  \App\Models\Woche  $woche
     * @return \Illuminate\Http\Response
     */
    public function returnBestellungenWocheAbteilung($abteilung,$woche)
    {
        $data = Abteilung::where('id','=',$abteilung)->with(['wocheBestellungen' => function ($query) use ($woche) {
            $query->where('wochen_id', '=',$woche);
        },
        'wocheBestellungen.spezialEssen',
        'wocheBestellungen.spezialEssen.teilnehmer',
        'wocheBestellungen.spezialEssen.wochentag',
        'wocheBestellungen.spezialEssen.essen'])->get()[0];
        $data = json_encode($data);
        $data = json_decode($data);
        $spezialEssenArray = [];
        if(count($data->woche_bestellungen) == 0){
            return response()->json([
                'message' => 'keine Bestellungen vohanden',
                'succes' => false,
            ],
            204);
        } else { 
            foreach($data->woche_bestellungen[0]->spezial_essen as $spezial){
                if ( $this->istTvohanden($spezialEssenArray, $spezial)) {
                    foreach( $spezialEssenArray as $teilnehmer) {
                            if ( $teilnehmer->Teilnehmer_id == $spezial->teilnehmer_id) {
                                $temp = $spezial->wochentag->name;
                                $teilnehmer->$temp = $spezial->essen->bezeichnung;
                            }
                        }
                    }
                else {
                    $tempobj = new \stdClass();
                    $tempobj->Name = $spezial->teilnehmer->name;
                    $tempobj->Montag = ''; 
                    $tempobj->Dienstag = ''; 
                    $tempobj->Mittwoch = ''; 
                    $tempobj->Donnerstag = ''; 
                    $tempobj->Freitag = '';
                    $tempobj->Teilnehmer_id = $spezial->teilnehmer->id;
                    $temp = $spezial->wochentag->name;
                    //$tempobj->essen[$essen->wochentag->name] = $essen->essen->bezeichnung;
                    $tempobj->$temp = $spezial->essen->bezeichnung;
                    $spezialEssenArray[] = $tempobj;
                }
            };
            $data = $data->woche_bestellungen[0];
            $data->spezial_essen = $spezialEssenArray;
        
            return response()->json([
                'data' => $data,
                'message' => 'Bestellungen der Woche der Abteilung geladen',
                'succes' => true,
            ],200);
        };
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
