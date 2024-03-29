<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Models\Woche;
use App\Models\Abteilung;
use Illuminate\Http\Request;
use \Datetime;
use \DatetimeZone;

class WocheController extends Controller
{
    public function returnSpezifischeWoche($id)
    {
        $woche=Woche::find($id);
        return response()->json([
            'data' => $woche,
            'message' => 'Einzelne Woche erfolgreich geladen',
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
        $dataAbteilungen = Abteilung::get();;
        $test = json_encode($data);
        $test = json_decode($test);
        $abteilungenBestellt = [];
        $spezialEssenArray = [];
        $essenAnzahl = array(array(0,0,0,0,0),array(0,0,0,0,0),array(0,0,0,0,0),array(0,0,0,0,0),array(0,0,0,0,0));
        foreach( $test->wochen_bestellungen as $wochen) {
            $abteilungenBestellt[] = $wochen->abteilung_id;
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
        $gesamtBestellungen = [];
        $gesamtBestellungen[0] = [
                            'Name' => 'Normal',
                            'Montag' => $essenAnzahl[0][4], 
                            'Dienstag' => $essenAnzahl[1][4], 
                            'Mittwoch' => $essenAnzahl[2][4], 
                            'Donnerstag' => $essenAnzahl[3][4], 
                            'Freitag' => $essenAnzahl[4][4],
                            'Gesamt' => $essenAnzahl[0][4] 
                                      + $essenAnzahl[1][4] 
                                      + $essenAnzahl[2][4] 
                                      + $essenAnzahl[3][4] 
                                      + $essenAnzahl[4][4],
        ];
        $gesamtBestellungen[1]  = [
                            'Name' => 'Vegetarisch',
                            'Montag' => $essenAnzahl[0][0], 
                            'Dienstag' => $essenAnzahl[1][0], 
                            'Mittwoch' => $essenAnzahl[2][0], 
                            'Donnerstag' => $essenAnzahl[3][0], 
                            'Freitag' => $essenAnzahl[4][0],
                            'Gesamt' => $essenAnzahl[0][0] 
                                      + $essenAnzahl[1][0] 
                                      + $essenAnzahl[2][0] 
                                      + $essenAnzahl[3][0] 
                                      + $essenAnzahl[4][0],
                        ];
        $gesamtBestellungen[2]  = [
                            'Name' => 'Vegan',
                            'Montag' => $essenAnzahl[0][1], 
                            'Dienstag' => $essenAnzahl[1][1], 
                            'Mittwoch' => $essenAnzahl[2][1], 
                            'Donnerstag' => $essenAnzahl[3][1], 
                            'Freitag' => $essenAnzahl[4][1],
                            'Gesamt' => $essenAnzahl[0][1] 
                                      + $essenAnzahl[1][1] 
                                      + $essenAnzahl[2][1] 
                                      + $essenAnzahl[3][1] 
                                      + $essenAnzahl[4][1],
                        ];
        $gesamtBestellungen[3]  = [
                            'Name' => 'Glutenfrei',
                            'Montag' => $essenAnzahl[0][2], 
                            'Dienstag' => $essenAnzahl[1][2], 
                            'Mittwoch' => $essenAnzahl[2][2], 
                            'Donnerstag' => $essenAnzahl[3][2], 
                            'Freitag' => $essenAnzahl[4][2],
                            'Gesamt' => $essenAnzahl[0][2] 
                                      + $essenAnzahl[1][2] 
                                      + $essenAnzahl[2][2] 
                                      + $essenAnzahl[3][2] 
                                      + $essenAnzahl[4][2],
                        ];
        $gesamtBestellungen[4]  = [
                            'Name' => 'Laktosefrei',
                            'Montag' => $essenAnzahl[0][3], 
                            'Dienstag' => $essenAnzahl[1][3], 
                            'Mittwoch' => $essenAnzahl[2][3], 
                            'Donnerstag' => $essenAnzahl[3][3], 
                            'Freitag' => $essenAnzahl[4][3],
                            'Gesamt' => $essenAnzahl[0][3] 
                                      + $essenAnzahl[1][3] 
                                      + $essenAnzahl[2][3] 
                                      + $essenAnzahl[3][3] 
                                      + $essenAnzahl[4][3],
                        ];
        $test->gesamtBestellungen = $gesamtBestellungen;
        $abteilungen = [];
        
        foreach($dataAbteilungen as $dataAbteilung) {
            if ($this->istAvohanden($abteilungenBestellt,$dataAbteilung)) {
                $temp = [];
                $temp['name'] = $dataAbteilung->name;
                $temp['Bestellt'] = 'JA';
                $abteilung[] = $temp;
            } else {
                $temp = [];
                $temp['name'] = $dataAbteilung->name;
                $temp['Bestellt'] = 'NEIN';
                $abteilung[] = $temp;
            }
        }
        $test->teilnehmer = $spezialEssenArray;
        $test->abteilungen = $abteilung;
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

    private function istAvohanden($bestellungAbteilungen, $abteilung) {
        $returnValue = false;
        foreach( $bestellungAbteilungen as $Bestellung) {
            if ( $Bestellung == $abteilung->id) {
                $returnValue = true;
            }
        }
        return $returnValue;
    }

}
