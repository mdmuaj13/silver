<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
  
    public function register(Request $request) {
        $rule = [
            'name'      => 'required',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:6'
        ];
        $message = [
            'email.unique'  => 'The email address you provided is already associated with another account.',
            'email.required' => 'Email field is required'
        ];
        $this->validate($request, $rule, $message);
 

        // $validator = Validator::make($request->all(),[
        //     'name' => 'required|min:2',
        //     'email' => 'required|email',
        //     'password' => 'required|min:4',
        // ])->validate();
        
        // if($validator->fails()){
        //     return $validator;
        // }

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ];

        $user = User::create($data);
        $accessToken = $user->createToken('authToken')->accessToken;

        $res = new Collection();
        $res['user'] = $user;
        $res['access_token'] = $accessToken;

        return $this->showAllWithoutPagination($res,201);

    }


    public function login(Request $request) {
        $data = $request->validate([
            'email'     => ['required','email'],
            'password'  => ['required']
        ]);
        // $data = Validator::make($request->all(), [
        //     'email'     => ['required','email'],
        //     'password'  => ['required'],
        // ]);

        // $data['email'] = $request->email;
        // $data['password'] = $request->password;

        if( !auth()->attempt($data)){
            return response(['status' => '403', 'message' => 'Invalid credential!! 😫']);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        $res = new Collection();
        $res['user'] = auth()->user();
        $res['access_token'] = $accessToken;

        return $this->showAllWithoutPagination($res);
 
    }

    public function logout (Request $request) {
        $token = $request->user()->token();
        $token->revoke();
    
        $response = 'You have been succesfully logged out!';
        return response($response, 200);
    
    }
}
