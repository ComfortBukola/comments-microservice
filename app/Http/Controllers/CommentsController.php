<?php

namespace App\Http\Controllers;

use App\Comment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class CommentsController extends Controller
{
    /**
     * Edit comment
     * @param array $request email, comment
     * @param int $id comment id
     * @return json result of opperation
     */
    public function update(Request $request, $id)
    {
        //validate the request
        $this->validate($request, [
            'email' => 'required|email',
            'comment_body' => 'required'
        ]);

        //get email and comment_body from request
        $email = $request['email'];
        $comment_body = $request['comment_body'];

        //get the user id from database
        $user = DB::table('users')->select('id')->where('email', $email)->get()->first();
        //check if user exist
        if (!$user) {
            $msg = [
                'message' => 'User not found',
                'response' => 'error'
            ];
            return response()->json($msg, 404);
        }

        //get the comment body from database
        $comment = DB::table('comments')->select('comment_body', 'user_id')->where('id', $id)->get()->first();
        //check if comment exist
        if (!$comment) {
            $msg = [
                'message' => 'Comment Not Found',
                'response' => 'error'
            ];
            return response()->json($msg, 404);
        }

        //check if user is authorized or not
        if ($user->id != $comment->user_id) {
            $msg = [
                'message' => 'Unauthorized User',
                'response' => 'error'
            ];
            return response()->json($msg, 401);
        }

        //update the comment
        $update = DB::table('comments')->where('id', $id)->update(['comment_body' => $comment_body]);
        $msg = [
            'message' => 'Comment Updated',
            'response' => 'success'
        ];
        return response()->json($msg, 200);
    }

    /**
     * Delete comment from database
     * @param array $request email,
     * @param int $id comment id
     * @return json result of opperation
     */
    public function delete(Request $request, $comment_id)
    {
        //ensure email is passed
        $this->validate($request, [
            'email' => 'required|email'
        ], [
            'required' => [
                'message' => 'email is required',
                'response' => 'error',
            ],
            'required' => [
                'message' => 'email must be valid',
                'response' => 'error',
            ]
        ]);

        // get user id
        $User = User::select('id')->where('email', $request['email'])->first();

        // check if user id is returned
        if ($User) {

            // search for comment
            $comment = Comment::find($comment_id);

            // check if comment is found
            if (!$comment) {
                return response()->json([
                    'message' => 'Comment Not Found',
                    'response' => 'error',
                ], 400);
            }

            // check if user owns comment
            if ($comment['user_id'] != $User['id']) {
                return response()->json([
                    'message' => 'Unathorized User',
                    'response' => 'error',
                ], 401);
            }

            // delete comment
            $comment->delete();
            return response()->json([
                'data' => ['comment' => $comment_id],
                'message' => 'Comment deleted successfully',
                'response' => 'Ok'
            ], 200);
        }

        return response()->json([
            'message' => 'Invalid User',
            'response' => 'error',
        ], 400);
    }

    /**
     * vote a comment
     * @param array $request,
     * @param int $comment_id comment id
     * @return json result of opperation
     */
    public function vote(Request $request, $comment_id)
    {
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

        if (!is_numeric($comment_id)) {
            return response()->json([
                'message' => 'Comment not found',
                'response' => 'error',
            ], 400);
        }

        $comment = Comment::find($comment_id);

        // check if comment is found
        if (!$comment) {
            return response()->json([
                'message' => 'Comment Not Found',
                'response' => 'error',
            ], 400);
        }

        $voteType = $request->input('vote_type');

        if ($voteType === 'upvote') {
            $comment->upvote += 1;
            $comment->vote   += 1;
            $save = $comment->save();
        } elseif ($voteType === 'downvote') {
            $comment->downvote  += 1;
            $comment->vote      += 1;
            $save = $comment->save();
        } else {
            return response()->json([
                'message' => 'invalid vote_type',
                'response' => 'error',
            ], 400);
        }

        if ($save) {
            return response()->json([
                'data' => [$comment],
                'message' => 'Comment voted successfully',
                'response' => 'Ok'
            ], 200);
        }
    }

    public function generateDummyData()
    {
        //create user for tweeter user
        $user1 = new User();
        $user1->name = 'anonymoous User';
        $user1->email = 'no email' . time();
        $user1->save();

        $user2 = new User();
        $user2->name = 'Registered User';
        $user2->email = 'demo@email.com';
        $user2->save();

        $comment1 = new Comment();
        $comment1->report_id = rand(1, 200);
        $comment1->user_id = $user1->id;
        $comment1->comment_body = 'The money is small';
        $comment1->comment_origin = 'Twitter';
        $comment1->save();

        $comment2 = new Comment();
        $comment2->report_id = rand(1, 200);
        $comment2->user_id = $user2->id;
        $comment2->comment_body = 'This is a welcome development ...';
        $comment2->comment_origin = 'Twitter';
        $comment2->save();

        return response()->json(['job completed'], 200);
    }
}
