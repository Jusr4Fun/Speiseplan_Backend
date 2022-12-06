<?php

namespace App\Http\Controllers;

use App\Models\Speiseplan_File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function uploadImage(Request $request)
    {
        $fields = $request->validate([
            'image' => 'required|image|mimes:jpg,jpeg|max:4096',
            'woche' => 'required',
        ]);
        $woche = json_decode($fields['woche']);
        $file = $fields['image'];
        $filename = uniqid() . '.jpg';
        $filepath = 'images\\' . $filename;
        if (count(Speiseplan_File::where('wochen_id','=',$woche->id)->get()) > 0){
            $loadedFile = Speiseplan_File::where('wochen_id','=',$woche->id)->get()[0];
            Storage::disk('img')->delete($loadedFile->file_name);
            $loadedFile->file_path = $filepath;
            $loadedFile->file_name = $filename;
            $loadedFile->save();
        }
        else {
            $speiseplanfile = [];
            $speiseplanfile['wochen_id'] = $woche->id;
            $speiseplanfile['file_path'] = $filepath;
            $speiseplanfile['file_name'] = $filename;
            Speiseplan_File::create($speiseplanfile);
        }
        Storage::disk('img')->putFileAs('',$file, $filename);
        return response()->json([
            'message' => 'Bild erfolgreich Hochgeladen',
            'succes' => true,
        ],200);
    }

    public function getImageWoche($id) {
        $loadedFile = Speiseplan_File::where('wochen_id','=',$id)->get()[0];
        $header = [];
        $header['Content-Type'] = 'image/jpeg'; 
        $header['Content-Disposition'] = 'form-data; name="image"; filename='.$loadedFile->file_name;
        $file = Storage::disk('img')->get($loadedFile->file_name);
        $filepath = Storage::disk('img')->path($loadedFile->file_name);
        /* var_dump(storage_path($loadedFile->file_path)); 
        return response()->json([
            'data' => $filepath,
            'message' => 'Bild erfolgreich Hochgeladen',
            'succes' => true,
        ],200); */
        return response()->file($filepath);
    }
}