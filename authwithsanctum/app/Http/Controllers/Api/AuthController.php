<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Auth;


class AuthController extends Controller
{
   public function register(Request $request)
   {
    	$request->validate([
    		'name' => ['required', 'string'],
    		'email' => ['required', 'email', 'unique:users'],
    		'password' => ['required', 'min:8','confirmed']
    	]);

    	User::create([
    		'name' => $request->name,
    		'email' => $request->email,
    		'password' => Hash::make($request->password)
    	]);

    	return response()->json(["message" => 'Account created successfully.'], 201);
    }

    public function login(Request $request)
    {
    	$request->validate([
    		'email' => ['required', 'email'],
    		'password' => ['required']
    	]);

    	if(Auth::attempt($request->only('email', 'password'))){
    		return response()->json(Auth::user(), 200);
    	}

    	throw ValidationException::withMessages([
    		'email' => ['Email or password is incorrect, please try again !']
    	]);
    }

    public function logout(){
    	Auth::logout();
    }
}
