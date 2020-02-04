<?php

namespace App\Http\Controllers\APIIMAGE;

use App\Http\Controllers\ResponseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ImageController extends ResponseController
{
    
    public function store(Request $request)
    {
        $input = $request->all();
        
        //Validations
        $validator = Validator::make($input, [
            'path' => 'required'
        ]);
        
        if($validator->fails()){
            return $this->sendError($validator->errors()->first());
        }
        
        if(!$request->hasFile('image'))
        {
            return $this->sendError('No file found');
        }
        
        $path = $request->path;
        $imageFile = $request->file('image');
        
        $filename = Storage::disk('images')->put($path, $imageFile);
        //$filename = Storage::disk('images')->;
        
        $fullPath = public_path().'/images/' . $filename ;
        
        return $this->sendResponse(['name' => basename($filename), 'fullPath' => $fullPath ], 'Image created succesfuly.');
    }

}
