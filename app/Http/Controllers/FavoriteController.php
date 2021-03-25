<?php

namespace App\Http\Controllers;

use App\Doctor;
use App\Favorite;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FavoriteController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),
            [
                'doctor_id' => 'required',
            ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }

        $user = auth()->user();

        $user_id = $user->id;
        $doctor_id = $request->doctor_id;

        if (User::query()->find($user_id) && Doctor::query()->find($doctor_id))
        {

            $favorite = new Favorite();
            $favorite->user_id = $user_id;
            $favorite->doctor_id = $doctor_id;
            $favorite->save();

            return response()->json([
                'message' => 'successful' ,
                'favorite' => $favorite
            ] , 200);

        }else{
            return response()->json(['message' => 'user_id or doctor_id or both of them are not valid']);
        }

    }

    public function delete(Request $request){
        $validator = Validator::make($request->all(),
            [
                'favorite_id' => 'required',
            ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $favorite_id = $request->favorite_id;
        $favorite = Favorite::query()->find($favorite_id);

        $favorite->delete();
        return response()->json([
            'status' => 'successfully deleted' ,
            'favorite' => $favorite
        ] , 200 );
    }


    public function get_user_list(){
        $user = auth()->user();
        $favorites = $user->favorites;

        return response()->json([
            'message' => 'ok' ,
            'favorites' => $favorites

        ] , 200);
    }






}
