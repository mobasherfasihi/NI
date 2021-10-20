<?php

namespace App\Http\Controllers\API;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\PurchaseRequest;
use App\Models\Product;
use App\Models\User;
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
        return $this->sendResponse(auth()->user(), 'Retrieved successfully', Response::HTTP_OK);
    }

    /**
     * User products
     * 
     * @return \Illuminate\Http\Response
     */
    public function products() 
    {
        return $this->sendResponse(auth()->user()->products()->paginate(Helper::constants('PER_PAGE')), 'Products retrieved successfully', Response::HTTP_OK);
    }

    /**
     * Purchase product
     * 
     * @return \Illuminate\Http\Response
     */
    public function purchase(PurchaseRequest $request) 
    {
        $data = $request->validated();
        auth()->user()->products()->attach(['product_sku' => $data['product_sku']]);

        return $this->sendResponse(null, 'Purchase completed', Response::HTTP_CREATED);
    }

    /**
     * Purchase product
     * 
     * @return \Illuminate\Http\Response
     */
    public function deleteProduct(Product $product) 
    {
        auth()->user()->products()->detach($product);

        return $this->sendResponse(null, 'Purchased item is removed', 201);
    }
}
