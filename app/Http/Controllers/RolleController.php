<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rolle;

class RolleController extends Controller
{
    public function index()
    {
        return response()->json([
            'message' => 'Rollen erfolgreich geladen',
            'succes' => true,
        ],200);
    }

    public function returnRollenRoleID() 
    {
        $data=Rolle::get();
        $rollenArray = [];
        $test = json_encode($data);
        $test = json_decode($test);
        foreach( $test as $data) {
            $tempobj = new \stdClass();
            $tempobj->text = $data->name;
            $tempobj->value = $data->name;
            $tempobj->id = $data->id;
            $rollenArray[] = $tempobj;
        }
        return response()->json([
            'data' => $rollenArray,
            'message' => 'Rollen und IDs erfolgreich geladen',
            'succes' => true,
        ],200);
    }
}
