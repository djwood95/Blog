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

use Firebase\JWT\JWT;

$router->get('/', function () use ($router) {
   
    return $router->app->version();

});

/** Post-related routes **/
$router->get('/posts', function () use ($router) {
    $results = DB::select("SELECT * FROM posts WHERE deleted='0' ORDER BY created_at DESC");

    return $results;
});

$router->get('/post/{postId}', function ($postId) use ($router) {
    $results = DB::select("SELECT * FROM posts WHERE id=:postId", ['postId' => $postId]);

    return $results;
});

/** Comment-related routes **/
$router->get('/comments/{postId}', function ($postId) use ($router) {
    $results = DB::select("SELECT * FROM comments WHERE postId=:postId", ['postId' => $postId]);

    return $results;
});

$router->post('/comments/new', function (Illuminate\Http\Request $request) use ($router) {
    $name = $request->input('name');
    $comment = $request->input('comment');
    $postId = $request->input('postId');

    DB::insert("INSERT INTO comments (postId, created_by, created_at, comment) 
                VALUES (:postId, :created_by, :created_at, :comment)", 
                [
                    'postId' => $postId,
                    'created_by' => $name,
                    'created_at' => date("Y-m-d H:i:s"),
                    'comment' => $comment
                ]);

    // return a new comment list
    $results = DB::select("SELECT * FROM comments WHERE postId=:postId", ['postId' => $postId]);
    return $results;
});

$router->post('/checkLogin', function (Illuminate\Http\Request $request) use ($router) {
    $username = $request->input('username');
    $password = $request->input('password');

    if($username == 'user' && $password == 'password') {
        $payload = [
            'iss' => 'blog',
            'sub' => $username,
            'iat' => time(),
            'exp' => time() + 60*60 // expires in 1 hour
        ];

        $token = JWT::encode($payload, $_ENV['JWT_secret']);
        $status = true;
    } else {
        $status = false;
        $token = null;
    }

    return [
        'status' => $status,
        'userId' => 0,
        'token' => $token
    ];

});



/****** BEGIN PROTECTED ROUTES **********/
$router->group( ['middleware' => 'auth'], function() use ($router) {

    $router->post('/posts/new', function (Illuminate\Http\Request $request) use ($router) {
        $title = $request->input('title');
        $content = $request->input('contents');
        DB::insert("INSERT INTO posts (created_at, created_by, title, content)
                    VALUES (:created_at, :created_by, :title, :content)",
                    [
                        'created_at' => date("Y-m-d H:i:s"),
                        'created_by' => $request->username,
                        'title' => $title,
                        'content' => $content
                    ]);
        
        return ['status', true];
    });

    $router->post('/posts/edit', function (Illuminate\Http\Request $request) use ($router) {
        $postId = $request->input('postId');
        $title = $request->input('title');
        $content = $request->input('contents');
        DB::update("UPDATE posts SET title=:title, content=:content WHERE id=:postId",
                    [
                        'title' => $title,
                        'content' => $content,
                        'postId' => $postId
                    ]);
        
        return ['status', true];
    });

    $router->get('/test', function() use ($router) {
        return "It Worked!";
    });
});
