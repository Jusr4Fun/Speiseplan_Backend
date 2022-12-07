<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teilnehmer;


class TeilnehmerController extends Controller
{
    public function storeTeilnehmer(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string|min:2|max:20',
            'abteilungs_id' => 'required',
        ]);

        $data = [
            'name' => $fields['name'],
            'abteilungs_id' => $fields['abteilungs_id'],
        ];

        $teilnehmer = Teilnehmer::create($data);
        
        return response()->json([
            'data' => $teilnehmer,
            'message' => 'Neuen Teilnehmer erfolgreich gespeichert',
            'succes' => true,
        ],201);
    }

    public function updateTeilnehmer(Request $request)
    {
        $fields = $request->validate([
            'id' => 'required',
            'name' => 'required|string|min:2|max:99',
            'abteilungs_id' => 'required',
        ]);
        $teilnehmer = Teilnehmer::find($fields['id']);
        $teilnehmer->name = $fields['name'];
        $teilnehmer->abteilungs_id = $fields['abteilungs_id'];
        $teilnehmer->save();
        
        return response()->json([
            'message' => 'Teilnehmer erfolgreich aktualisiert',
            'succes' => true,
        ],200);
    }

    public function deleteTeilnehmer($id)
    {
        Teilnehmer::destroy($id);
        return response()->json([
            'message' => 'Teilnehmer erfolgreich gelöscht',
            'succes' => true,
        ],200);
    }
}
