<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Login api
     *
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){ 
            $user = Auth::user(); 
            $success['token'] =  $user->createToken('Native-Instruments')->plainTextToken; 
            $success['user']['name'] =  $user->name;
   
            return $this->sendResponse($success, 'User logged in successfully.', 200);
        } 
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Your email or password is incorrect'], Response::HTTP_UNAUTHORIZED);
        } 
    }

    /**
     * Auth user details
     * 
     * @return \Illuminate\Http\Response
     */
    public function authDetails() 
    {
        return $this->sendResponse(auth()->user()->toArray(), 'Retrieved successfully', Response::HTTP_OK);
    }
}
