<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rolle;

class RolleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'message' => 'Rollen erfolgreich geladen',
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

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

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
