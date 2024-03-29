<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{
    public function returnAllUsers()
    {
        $user=User::with([                            
            'abteilung',
            'rollen'
            ])->get();
        $test = json_encode($user);
        $test = json_decode($test);
        foreach( $test as $data) {
            $data->abteilung = $data->abteilung->name;
            $data->role = $data->rollen->name;
            $data->role_id = $data->rollen->id;
            unset($data->rollen);
        }
        return response()->json([
            'data' => $test,
            'message' => 'Nutzerindex erfolgreich geladen',
            'succes' => true,
        ],200);
    }

    public function storeUser(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string|min:2|max:20',
            'abteilung_id' => 'required',
            'role_id' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|max:20',
        ]);

        $data = [
            'name' => $fields['name'],
            'abteilung_id' => $fields['abteilung_id'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'role_id' => $fields['role_id'],
        ];

        $user = User::create($data);
        
        return response()->json([
            'data' => $user,
            'message' => 'Neuen Nutzer erfolgreich gespeichert',
            'succes' => true,
        ],201);
    }

    public function updateUser(Request $request)
    {
        $fields = $request->validate([
            'id' => 'required',
            'name' => 'required|string|min:2|max:99',
            'abteilung_id' => 'required',
            'role_id' => 'required',
            'email' => 'required|email|unique:users,email,'.$request->id,
        ]);
        $user = User::find($fields['id']);
        $user->name = $fields['name'];
        $user->abteilung_id = $fields['abteilung_id'];
        $user->email = $fields['email'];
        $user->role_id = $fields['role_id'];
        $user->save();
        
        return response()->json([
            'message' => 'Nutzer erfolgreich aktualisiert',
            'succes' => true,
        ],200);
    }

    public function deleteUser($id)
    {
        User::destroy($id);
        return response()->json([
            'message' => 'Nutzer erfolgreich gelöscht',
            'succes' => true,
        ],200);
    }

    public function updatePasswort(Request $request) 
    {
        $fields = $request->validate([
            'id' => 'required',
            'password' => 'required|string|min:8|max:99'
        ]);

        $user = User::find($fields['id']);
        $user->password = bcrypt($fields['password']);
        $user->save();
        
        return response()->json([
            'message' => 'Passwort erfolgreich aktualisiert',
            'succes' => true,
        ],200);
    }
}
