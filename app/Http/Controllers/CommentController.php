<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Doctor;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api' ,['except' => ['get_comments'] ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'doctor_id' => 'required',
                'comment' => 'required'
            ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $user = auth()->user();

        $user_id = $user->id;
        $doctor_id = $request->doctor_id;
        $comment = $request->comment;

        if (User::query()->find($user_id) && Doctor::query()->find($doctor_id))
        {

            $saved_comment = new Comment();
            $saved_comment->user_id = $user_id;
            $saved_comment->doctor_id = $doctor_id;
            $saved_comment->comment = $comment;
            $saved_comment->save();

            return response()->json([
                'message' => 'successful' ,
                'favorite' => $saved_comment
            ] , 200);

        }else{
            return response()->json(['message' => 'user_id or doctor_id or both of them are not valid']);
        }

    }


    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'comment_id' => 'required'
            ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $comment_id = $request->comment_id;
        if (Comment::query()->find($comment_id)){
            $comment = Comment::query()->find($comment_id);
            $comment->delete();
            return Response()->json([
                'message' => 'successfully deleted' ,
                'comment' => $comment
            ] , 200);
        }else{
            return response()->json([
                'message' => 'this comment is not available'
            ], 401);
        }
    }


    public function get_comments(Request $request)
    {
        $validator = Validator::make($request->all(),
            [
                'doctor_id' => 'required',
            ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }
        $doctor_id = $request->doctor_id;
        $comments = Comment::query()->where('doctor_id' , $doctor_id)->get();

        return \response()->json([
            'message' => 'comments are ready' ,
            'comments' => $comments
        ] , 200);

    }



}
