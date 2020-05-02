<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function register(Request $request)
    {

        $validateData = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'confirm_password'=> 'required|same:password'
        ]);
        if($validateData->fails())
        {
            return response(['error'=>$validateData->errors()->toArray()], 400);
        }
        $input = $request->all();
        // $input['password']= Hash::make($input['password']);
        $input['password']= bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] = $user->createToken('myapp')->accessToken;
        $success['name'] = $user->name;
        return response()->json(['success' => $success], 201);
    }

    public function userDetails()
    {

        if(!Auth::user())
        {
            return response()->json(['Error'=> 'You need to login to access this resoures'], 401);
        }
        $userDetails = User::all();
        if($userDetails)
        {
            return response()->json(['user'=>$userDetails],200);
        }
        else {
            return response()->json(['Error'=> 'User not found'], 404);
        }
    }

    public function login(Request $request)
    {
        $validateData = Validator::make($request->all(), [

            'email' => 'required',
            'password' => 'required',
        ]);
        if($validateData->fails())
        {
            return response(['error'=>$validateData->errors()], 400);
        }
        $email = $request->email;
        $password = $request->password;
        if(Auth::attempt(['email' => $email, 'password' => $password]))
        {
            $user = Auth::user();
            $token = $user->createToken('myapp')->accessToken;
            return response(['user'=>$user, 'token'=>$token],200);
        }
        else
        {
            return response(['error'=>"Invalid credentials, check your inputs"], 400);
        }
    }

    public function getUser($id)
    {
        $user = User::whereId($id)->firstOrFail();
        return response()->json($user,200);
    }

    public function logout()
    {
        $user = Auth::user()->token();
            $user->revoke();
            $response['msg']="Successfully logout";
            return response()->json($response)->header('Content-Type', 'application/json');
            // To logout of all devices
            // DB::table('oauth_access_tokens')
            // ->where('user_id', Auth::user()->id)
            // ->update([
            //     'revoked' => true
            // ]);
    }
}
