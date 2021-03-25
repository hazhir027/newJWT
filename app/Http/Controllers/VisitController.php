<?php

namespace App\Http\Controllers;

use App\Doctor;
use App\User;
use App\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VisitController extends Controller
{

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'doctor_id' => 'required',
                'year' => 'required' ,
                'month' => 'required' ,
                'day' => 'required' ,
                'hour' => 'required' ,
                'minute' => 'required' ,
            ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $user = auth()->user();

        $user_id = $user->id;
        $doctor_id = $request->doctor_id;
        $hour = $request->hour;
        $minute = $request->minute;
        $year = $request->year;
        $month = $request->month;
        $day = $request->day;

        if (Doctor::query()->find($doctor_id))
        {

            $saved_visit = new Visit();
            $saved_visit->user_id = $user_id;
            $saved_visit->doctor_id = $doctor_id;
            $saved_visit->year = $year;
            $saved_visit->month = $month;
            $saved_visit->day = $day;
            $saved_visit->hour = $hour;
            $saved_visit->minute = $minute;
            $saved_visit->save();

            return response()->json([
                'message' => 'successful' ,
                'favorite' => $saved_visit
            ] , 200);

        }else{
            return response()->json(['message' => 'doctor_id is not valid']);
        }

    }


    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'visit_id' => 'required'
            ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $visit_id = $request->visit_id;
        if (Visit::query()->find($visit_id)){
            $visit = Visit::query()->find($visit_id);
            $visit->delete();
            return Response()->json([
                'message' => 'successfully deleted' ,
                'comment' => $visit
            ] , 200);
        }else{
            return response()->json([
                'message' => 'visit is not available'
            ], 401);
        }
    }


    public function get_visits(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'doctor_id' => 'required',
            ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $doctor_id = $request->doctor_id;
        $visits = Visit::query()->where('doctor_id' , $doctor_id)->get();

        return \response()->json([
            'message' => 'ok' ,
            'visits' => $visits
        ] , 200);

    }




}
