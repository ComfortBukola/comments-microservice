<?php

namespace App\Http\Controllers;

use App\Comment;
use App\User;
use Illuminate\Http\Request;

class TwitterCommentsController extends Controller
{
    /**
     *  controller that handles tweets comment.
     *  @param Request
     * @return json
     */
    public function tweetComments(Request $request)
    {
        //validate request
        $validate = validator([
            'email' => 'required|email|unique',
            'comment_body' => 'required',
            'comment_origin' => 'required',
            'report_id' => 'required',
            'name' => 'required'
        ]);
        if(!$validate){
            return response($content = [
                'message' => 'Invalid Request',
                'response' => 'error',
                ] ,
                $status = 404
            );
        }


        if (!isset($request->comment_body)|| !isset($request->comment_origin) 
        || !isset($request->comment_owner_username) || !isset($request->comment_owner_email) 
        || !isset($request->comment_origin) || !isset($request->report_id) ){
            return response (
                $content = [
                    "message" => "Invalid Request, All fields in the body are required",
                    "response" => "error"
                ],
                $status = 404
            );
        }

        
        $request = json_decode($request->getContent());
        //return $request->comment_owner_email;
        $check_user = User::where('email', $request->comment_owner_email)->get();

        if (count($check_user) > 0){
            //create comments

            $user_id = $check_user[0]->id;

            $tweets = new Comment();
            $tweets->user_id = $user_id;
            $tweets->comment_body = $request->comment_body;
            $tweets->comment_origin = 'Twitter';
            $tweets->report_id = $request->report_id;
            $saveComment = $tweets->save();

            if ($saveComment) {
                return response($content = [
                    'data' => [],
                    'message' => 'Comment created successfully',
                    'response' => 'Ok'
                ],
                $status = 201);
            }else {
                return response($content = [
                    'message' => 'Cannot save comment',
                    'response' => 'error',
                    ] ,
                    $status = 400
                );
            }

            
        }else {
            $user = new User();
            $user->name = $request->comment_owner_username;
            $user->email = $request->comment_owner_email. ' '. time();
            $save = $user->firstOrCreate(['email' => $user->email, 'name' => $user->name]);


            if ($save){
                $tweets = new Comment();
                $tweets->user_id = User::all()->last()->id;
                $tweets->comment_body = $request->comment_body;
                $tweets->comment_origin = 'Twitter';
                $tweets->report_id = $request->report_id;
                $saveComment = $tweets->save();

                if ($saveComment) {
                    return response($content = [
                        'data' => [],
                        'message' => 'Comment created successfully',
                        'response' => 'Ok'
                    ],
                    $status = 201);
                }else {
                    return response($content = [
                        'message' => 'Cannot save comment',
                        'response' => 'error',
                        ] ,
                        $status = 400
                    );
                }

            } 

            return response($content = [
                'message' =>'Error could not save user',
                'response' => 'error',
                ] ,
                $status = 400
            );

            
    }
        
}
        

    //
}
