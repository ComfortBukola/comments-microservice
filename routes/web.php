<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use Illuminate\Support\Facades\Route;

$router->get('/', function () use ($router) {
    return $router->app->version();
});

Route::post('/tweet/comment', ['uses' => "TwitterCommentsController@tweetComments"]);

//edit comment
Route::patch('reports/comment/edit/{id}', 'CommentsController@update');

// delete comment
Route::delete('report/comment/{comment_id}', 'CommentsController@delete');

// upvote/downvotes
Route::patch('/reports/comment/vote/{comment_id}', ['uses' => 'CommentsController@vote']);

//generate dummy users and comments
Route::get('/dummy-data','CommentsController@generateDummyData');
Route::get('/tweets/comment', ['uses' => "TwitterCommentsController@tweetComments"]);

