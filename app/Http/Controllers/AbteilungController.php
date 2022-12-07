<?php

namespace App\Http\Controllers;

use App\Models\Abteilung;
use App\Models\WochenBestellung;
use Illuminate\Http\Request;

class AbteilungController extends Controller
{
    public function returnAlleAbteilungen()
    {
        $abteilung=Abteilung::get();
        $returnAbteilung = [];
        foreach($abteilung as $abt) {
            $temp = new \stdClass();
            $temp->text = $abt->name;
            $temp->value = $abt->name;
            $temp->id = $abt->id;
            $returnAbteilung[]  = $temp;
        }
        return response()->json([
            'data' => $returnAbteilung,
            'message' => 'Alle Abteilungen erfolgreich übergeben',
            'succes' => true,
        ],200);
    }

    public function returnTeilnehmerAbteilung($id)
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

    public function returnTeilnehmerNameBestellung($id,$bestellungid)
    {
        $data = Abteilung::where('id','=',$id)->with([                            
            'teilnehmer'
            ])->get()[0];
        $bestellungen = WochenBestellung::find($bestellungid)->spezialEssen()->get();
        $idList = [];
        foreach($bestellungen as $bestellung) {
            if(!in_array($bestellung['teilnehmer_id'], $idList)) {
                $idList[]=$bestellung['teilnehmer_id'];
            }
        }
        $name = $data->name;
        $abteilungTeilnehmer = $data->teilnehmer;
        $teilnehmerlist = [];
        foreach( $abteilungTeilnehmer as $teilnehmer) {
            if(!in_array($teilnehmer->id , $idList)) {
                $teilnehmer->Teilnehmer_id = $teilnehmer->id;
                unset($teilnehmer->id);
                $teilnehmerlist[] = $teilnehmer;
            }
        }
        return response()->json([
            'data' => $teilnehmerlist,
            'message' => 'Alle Teilnehmer der Abteilung erfolgreich übergeben',
            'succes' => true,
        ],200);
    }

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
        $normalEssenArray = [];
        if(count($data->woche_bestellungen) == 0){
            return response()->json(
            'keine Bestellungen vohanden',
            204);
        } else { 
            foreach($data->woche_bestellungen[0]->spezial_essen as $spezial){
                if ( $this->TAvailable($spezialEssenArray, $spezial)) {
                    foreach( $spezialEssenArray as $teilnehmer) {
                            if ( $teilnehmer->Teilnehmer_id == $spezial->teilnehmer_id) {
                                $temp = $spezial->wochentag->name;
                                $teilnehmer->$temp = $spezial->essen->bezeichnung;
                            }
                        }
                    }
                else {
                    $tempobj = new \stdClass();
                    $tempobj->name = $spezial->teilnehmer->name;
                    $tempobj->Montag = ''; 
                    $tempobj->Dienstag = ''; 
                    $tempobj->Mittwoch = ''; 
                    $tempobj->Donnerstag = ''; 
                    $tempobj->Freitag = '';
                    $tempobj->Teilnehmer_id = $spezial->teilnehmer->id;
                    $temp = $spezial->wochentag->name;
                    $tempobj->$temp = $spezial->essen->bezeichnung;
                    $spezialEssenArray[] = $tempobj;
                }
            };
            $tempobj = new \stdClass();
            $tempobj->Montag = $data->woche_bestellungen[0]->montag_normal; 
            $tempobj->Dienstag = $data->woche_bestellungen[0]->dienstag_normal; 
            $tempobj->Mittwoch = $data->woche_bestellungen[0]->mittwoch_normal;
            $tempobj->Donnerstag = $data->woche_bestellungen[0]->donnerstag_normal;
            $tempobj->Freitag = $data->woche_bestellungen[0]->freitag_normal;
            $normalEssenArray[] = $tempobj;

            $tempData = new \stdClass();
            $tempData->spezial_essen = $spezialEssenArray;
            $tempData->abteilung_id = $data->woche_bestellungen[0]->abteilung_id;
            $tempData->id = $data->woche_bestellungen[0]->id;
            $tempData->wochen_id = $data->woche_bestellungen[0]->wochen_id;
            $tempData->normal = $normalEssenArray;
        
            return response()->json([
                'data' => $tempData,
                'message' => 'Bestellungen der Woche der Abteilung geladen',
                'succes' => true,
            ],200);
        };
    }

    private function TAvailable($spezial, $essen) {
        $returnValue = false;
        foreach( $spezial as $teilnehmer) {
                if ( $teilnehmer->Teilnehmer_id == $essen->teilnehmer_id) {
                    $returnValue = true;
                }
            }
        return $returnValue;
    }
}
