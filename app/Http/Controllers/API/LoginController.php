<?php

namespace App\Http\Controllers\API;

use App\User;
use App\Http\Controllers\ResponseController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends ResponseController
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password'
        ]);
        
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        
        $user = new User();
        
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        
        $user->save();
        
        return $this->sendResponse($user, 'User created successfully.');  
        
    }
    
    public function login(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);
        
        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }
        
        if (!Auth::attempt($request->only('email', 'password'))) {
            return $this->sendError('Invalid credentials');
        }
        
        Auth::user()->api_token = Str::random(80);
        Auth::user()->save();
        
        //return response()->json(['user' => Auth::user(),'token' => Auth::user()->api_token]);
        return $this->sendResponse(Auth::user(), 'User logged successfully.');  
    }
    
    public function getUserInformation() {
        return $this->sendResponse(Auth::user(), 'User information gotten successfully.');  
    }
}
