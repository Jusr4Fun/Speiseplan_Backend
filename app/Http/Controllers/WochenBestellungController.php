<?php

namespace App\Http\Controllers;

use App\Models\WochenBestellung;
use App\Models\Spezial_Essen;
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

    /**
     * Übergibt alle Wochenbestellungen der spezifizierten ID
     *
     * @return \Illuminate\Http\Response
     */
    public function returnSpezialEssen($id) {
        $data = WochenBestellung::find($id)->spezialEssen()->get();

        return response()->json([
            'data' => $data,
            'message' => 'Spezialessen der Wochenbestellung erfolgreich geladen',
            'succes' => true,
        ],200);
    }

    public function updateOrCreateWochenBestellungSpezialBestellungen(Request $request) {
        $fields = $request->validate([
            'abteilung_id'  => 'required|int',
            'bestellungs_id'  => 'sometimes',
            'normal'    => 'required|array|min:1|max:1',
            'normal.0.*'  => 'required|int',
            'spezial'    => 'sometimes|array',
            'woche_id'  => 'required|int',
        ]);
        $tempARRR = [];
        if($fields['bestellungs_id']  || (count(WochenBestellung::where([['wochen_id','=',$fields['woche_id']],['abteilung_id','=',$fields['abteilung_id']]])->get()) > 0) ) {
            $data = WochenBestellung::where([['wochen_id','=',$fields['woche_id']],['abteilung_id','=',$fields['abteilung_id']]])->first();
            $this->saveHelper($data, $fields);
            if($fields['spezial'] && $fields['bestellungs_id']) {
                foreach($fields['spezial'] as $sentBestellung) {
                    $tempBestArr = [];
                    $tempBestObj = [];
                    $tempBestObj['teilnehmer_id'] = $sentBestellung['Teilnehmer_id'];
                    $tempBestObj['wochen_bestellung_id'] = $fields['bestellungs_id'];
                    $this->CheckIfEssenExistSaveIf($tempBestObj, $sentBestellung['Montag'], $tempBestArr, 1);
                    $this->CheckIfEssenExistSaveIf($tempBestObj, $sentBestellung['Dienstag'], $tempBestArr, 2);
                    $this->CheckIfEssenExistSaveIf($tempBestObj, $sentBestellung['Mittwoch'], $tempBestArr, 3);
                    $this->CheckIfEssenExistSaveIf($tempBestObj, $sentBestellung['Donnerstag'], $tempBestArr, 4);
                    $this->CheckIfEssenExistSaveIf($tempBestObj, $sentBestellung['Freitag'], $tempBestArr, 5);
                    $tempARRR[] = $tempBestArr;
                    $savedBestellungen = Spezial_Essen::where('wochen_bestellung_id','=',$fields['bestellungs_id'])->get();
                    foreach($tempBestArr as $bestellung) {
                        if(!$this->ExistsEAndChangeIfNotEqual($bestellung, $savedBestellungen)) {
                            var_dump(Spezial_Essen::create($bestellung));
                        }
                    }
                }
            }
        } else {
            $tempArr = [];
            $tempArr['wochen_id'] = $fields['woche_id'];
            $tempArr['abteilung_id'] = $fields['abteilung_id'];
            $tempArr['anzahl_essen_normal'] = $fields['normal'][0]['Montag']
                                            + $fields['normal'][0]['Dienstag']
                                            + $fields['normal'][0]['Mittwoch']
                                            + $fields['normal'][0]['Donnerstag']
                                            + $fields['normal'][0]['Freitag'];
            $tempArr['montag_normal'] = $fields['normal'][0]['Montag'];
            $tempArr['dienstag_normal'] = $fields['normal'][0]['Dienstag'];
            $tempArr['mittwoch_normal'] = $fields['normal'][0]['Mittwoch'];
            $tempArr['donnerstag_normal'] = $fields['normal'][0]['Donnerstag'];
            $tempArr['freitag_normal'] = $fields['normal'][0]['Freitag'];
            $data = WochenBestellung::create($tempArr);
            $this->saveHelper($data, $fields);
        }; 

        return response()->json([
            'data' => $tempARRR,
            'message' => 'erfolgreich',
            'succes' => true,
        ],200);
    }

    private function saveHelper($data, $fields) {
        $data->anzahl_essen_normal = $fields['normal'][0]['Montag']
                                   + $fields['normal'][0]['Dienstag']
                                   + $fields['normal'][0]['Mittwoch']
                                   + $fields['normal'][0]['Donnerstag']
                                   + $fields['normal'][0]['Freitag'];
        $data->montag_normal = $fields['normal'][0]['Montag'];
        $data->dienstag_normal = $fields['normal'][0]['Dienstag'];
        $data->mittwoch_normal = $fields['normal'][0]['Mittwoch'];
        $data->donnerstag_normal = $fields['normal'][0]['Donnerstag'];
        $data->freitag_normal = $fields['normal'][0]['Freitag'];
        $data->save();
    }

    private function ExistsEAndChangeIfNotEqual($bestellung, &$savedBestellungen) {
        $returnValue = false;
        foreach( $savedBestellungen as $saved) {
                if ( $saved['teilnehmer_id'] == $bestellung['teilnehmer_id'] &&  $saved['wochentag_id'] == $bestellung['wochentag_id']) {
                    if($saved['essen_id'] != $bestellung['essen_id']) {
                        $saved['essen_id'] = $bestellung['essen_id'];
                        $saved->save();
                    }
                    $returnValue = true;
                }
            }
        return $returnValue;
    }

    private function convertEssenTextID($text) {
        switch($text) {
            case 'Vegetarisch':
                return 1;
            case 'Vegan':
                return 2;
            case 'Glutenfrei':
                return 3;
            case 'Laktosefrei':
                return 4;
            default:
                return null;
        }
    }

    private function CheckIfEssenExistSaveIf($obj, $bestellung, &$list, $tag_id) {
        if($bestellung && ($bestellung != "")) {
            $obj['wochentag_id'] = $tag_id;
            $obj['essen_id'] = $this->convertEssenTextID($bestellung);
            $list[] = $obj;
        }
    }
}
