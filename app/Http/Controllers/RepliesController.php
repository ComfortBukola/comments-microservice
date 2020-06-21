<?php

namespace App\Http\Controllers;


use App\Reply;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RepliesController extends Controller
{
    public function vote(Request $request, $reply_id)
    {
        //validate the request
        $this->validate($request, [
            'vote_type' => 'required',
        ]); //only vote type and email is required

        //get vote_type from request
        //anybody can upvote or downvote
        $voteType = $request['vote_type'];

        //get the reply connected to a particular comment from database
        $reply = DB::table('reply')->select('reply_body', 'user_id')->where('id', $reply_id)->get()->first();
        //check if reply exist
        if (!$reply) {
            $msg = [
                'message' => 'Reply Not Found',
                'response' => 'error'
            ];
            return response()->json($msg, 404);
        }


        $this->validate($request, [
            'vote_type' => 'required|string'
        ], [
            'required' => [
                'message' => 'vote_type is required',
                'response' => 'error',
            ],
            'string' => [
                'message' => 'vote_type must be either upvote or downvote',
                'response' => 'error',
            ]
        ]);

        if (!$request->has('vote_type')) {
            return response()->json([
                'message' => 'vote_type is required',
                'response' => 'error',
            ], 400);
        }

        if (!is_numeric($reply_id)) {
            return response()->json([
                'message' => 'Reply not found',
                'response' => 'error',
            ], 400);
        }
        
        $reply = Reply::find($reply_id);

        // check if comment is found
        if (!$reply) {
            return response()->json([
                'message' => 'Reply Not Found',
                'response' => 'error',
            ], 400);
        }

        $voteType = $request->input('vote_type');

        if ($voteType === 'upvote') {
            $reply->upvote += 1;
            $reply->vote   += 1;
            $save = $reply->save();
        } elseif ($voteType === 'downvote') {
            $reply->downvote  += 1;
            $reply->vote      += 1;
            $save = $reply->save();
        } else {
            return response()->json([
                'message' => 'invalid vote_type',
                'response' => 'error',
            ], 400);
        }

        if ($save) {
            return response()->json([
                'data' => [$reply],
                'message' => 'Reply voted successfully',
                'response' => 'Ok'
            ], 200);
        }
    }
}

