<?php

namespace App\Http\Controllers;

use App\Models\Spezial_Essen;
use Illuminate\Http\Request;

class SpezialEssenController extends Controller
{
    public function deleteSpezialEssen(Request $request) {
        $daysArr =array('Montag' , 'Dienstag' , 'Mittwoch' , 'Donnerstag' , 'Freitag');
        $fields = $request->validate([
            'bestellungs_id'  => 'required|int',
            'spezialBestellung' => 'required',

        ]);

        $tempDayArr = array_intersect( $daysArr , array_keys($fields['spezialBestellung']));
        $temp = new \stdClass();
        foreach($tempDayArr as $day) {
            if ($fields['spezialBestellung'][$day]) {
                $temp = Spezial_Essen::where([['wochen_bestellung_id','=',$fields['bestellungs_id']]
                                     ,['essen_id','=',$this->convertEssenTextID($fields['spezialBestellung'][$day])]
                                     ,['wochentag_id','=',$this->convertDayTextID($day)]
                                     ,['teilnehmer_id','=',$fields['spezialBestellung']['Teilnehmer_id']]
            ])->delete();
            }
        } 
        
        return response()->json([
            'message' => 'erfolgreich',
            'succes' => true,
        ],200);

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
                return 4;
            default:
                return null;
        }
    }
}
