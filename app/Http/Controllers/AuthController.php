<?php

namespace App\Http\Controllers;

use App\User;
use http\Env\Response;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login' , 'register']]);
    }


    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }


    public function register(Request $request )
    {
        $validator = Validator::make($request->all(),
            [
                'name' => 'required',
                'email' => 'required|email',
                'password' => 'required',
                'melli_code' => 'required',
            ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $email = $request->email;
        if (User::query()->where('email' , $email)->exists()){
            return \response()->json(['status' => 'already user is available']);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->melli_code = $request->melli_code;
        $user->save();

        return response()->json([
            'success' => true,
            'data' => $user
        ], \Illuminate\Http\Response::HTTP_OK);
    }

    public function me()
    {
        $user = response()->json(auth()->user());
        return $user;

    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'user_id' => 'required',
            ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $id= $request->user_id;
        $user = auth()->user();
        if ($user->is_admin){
            $user = User::query()->find($id);
            $user->delete();
            return \response()->json([
                'message' => 'user has been deleted'
                , 'user' => $user->name
                ], 200);
        }else{
            return \response()->json(['message' => 'you have no permission to delete user']);
        }

    }


    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
    
    
    public function hi(){
        
}
