<?php

namespace App\Http\Controllers;

use App\Models\WochenBestellung;
use App\Models\Spezial_Essen;
use Illuminate\Http\Request;

class WochenBestellungController extends Controller
{
    public function updateOrCreateWochenBestellungSpezialBestellungen(Request $request) {
        $daysArr =array('Montag' , 'Dienstag' , 'Mittwoch' , 'Donnerstag' , 'Freitag');
        $fields = $request->validate([
            'abteilung_id'  => 'required|int',
            'bestellungs_id'  => 'sometimes',
            'normal'    => 'required|array|min:1|max:1',
            'normal.0.*'  => 'required|int',
            'spezial'    => 'sometimes|array',
            'woche_id'  => 'required|int',
        ]);
        if($fields['bestellungs_id']  || (count(WochenBestellung::where([['wochen_id','=',$fields['woche_id']],['abteilung_id','=',$fields['abteilung_id']]])->get()) > 0) ) {
            $data = WochenBestellung::where([['wochen_id','=',$fields['woche_id']],['abteilung_id','=',$fields['abteilung_id']]])->first();
            $this->saveHelper($data, $fields);
            if($fields['spezial'] && $fields['bestellungs_id']) {
                foreach($fields['spezial'] as $sentBestellung) {
                    $tempBestArr = [];
                    $tempBestObj = [];
                    $tempDayArr = [];
                    $tempBestObj['teilnehmer_id'] = $sentBestellung['Teilnehmer_id'];
                    $tempBestObj['wochen_bestellung_id'] = $fields['bestellungs_id'];
                    $tempDayArr = array_intersect( $daysArr , array_keys($sentBestellung));
                    foreach($tempDayArr as $day) {
                        $this->ConvertSentBestellungToFormat($tempBestObj, $sentBestellung[$day], $tempBestArr, $this->convertDayTextID($day));
                    }
                    $savedBestellungen = Spezial_Essen::where('wochen_bestellung_id','=',$fields['bestellungs_id'])->get();
                    foreach($tempBestArr as $bestellung) {
                        if ($bestellung['essen_id']) {
                            if(!$this->ExistsEAndChangeIfNotEqual($bestellung, $savedBestellungen)) {
                                Spezial_Essen::create($bestellung);
                            }
                        }
                        else {
                            $this->IfEExistsDelete($bestellung, $savedBestellungen);
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

    private function IfEExistsDelete($bestellung, &$savedBestellungen) {
        foreach( $savedBestellungen as $saved) {
            if ( $saved['teilnehmer_id'] == $bestellung['teilnehmer_id'] &&  $saved['wochentag_id'] == $bestellung['wochentag_id']) {
                if($saved['essen_id'] != $bestellung['essen_id'] && $bestellung['essen_id'] == null) {
                    $saved->delete();
                }
            }
        }
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

    private function convertDayTextID($text) {
        switch($text) {
            case 'Montag':
                return 1;
            case 'Dienstag':
                return 2;
            case 'Mittwoch':
                return 3;
            case 'Donnerstag':
                return 4;
            case 'Freitag':
                return 5;
            default:
                return null;
        }
    }

    private function ConvertSentBestellungToFormat(&$obj, $bestellung, &$list, $tag_id) {
        
        if($bestellung && ($bestellung != "")) {
            $obj['wochentag_id'] = $tag_id;
            $obj['essen_id'] = $this->convertEssenTextID($bestellung);
            $list[] = $obj;
        } 
        elseif ($bestellung == "") {
            $obj['wochentag_id'] = $tag_id;
            $obj['essen_id'] = null;
            $list[] = $obj;
        }
    }
}
