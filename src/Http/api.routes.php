<?php

Route::prefix('blogs')->group(
    function () {
        Route::prefix('posts')->group(
            function () {
                Route::get('/', 'Posts\PostsController@index');
                Route::get('/actions', 'Posts\PostsController@getActions');

                Route::get('{blog_posts}/tags ', 'Posts\PostsController@tags');
                Route::post('{blog_posts}/tags ', 'Posts\PostsController@saveTags');
                Route::get('{blog_posts}/addresses ', 'Posts\PostsController@addresses');
                Route::post('{blog_posts}/addresses ', 'Posts\PostsController@saveAddresses');

                Route::get('/{blog_posts}/{subObjects}', 'Posts\PostsController@relatedObjects');
                Route::get('/{blog_posts}', 'Posts\PostsController@show');

                Route::post('/', 'Posts\PostsController@store');
                Route::post('/{blog_posts}/do/{action}', 'Posts\PostsController@doAction');

                Route::patch('/{blog_posts}', 'Posts\PostsController@update');
                Route::delete('/{blog_posts}', 'Posts\PostsController@destroy');
            }
        );

        Route::prefix('posts-perspective')->group(
            function () {
                Route::get('/', 'PostsPerspective\PostsPerspectiveController@index');
                Route::get('/actions', 'PostsPerspective\PostsPerspectiveController@getActions');

                Route::get('{blog_posts_perspective}/tags ', 'PostsPerspective\PostsPerspectiveController@tags');
                Route::post('{blog_posts_perspective}/tags ', 'PostsPerspective\PostsPerspectiveController@saveTags');
                Route::get('{blog_posts_perspective}/addresses ', 'PostsPerspective\PostsPerspectiveController@addresses');
                Route::post('{blog_posts_perspective}/addresses ', 'PostsPerspective\PostsPerspectiveController@saveAddresses');

                Route::get('/{blog_posts_perspective}/{subObjects}', 'PostsPerspective\PostsPerspectiveController@relatedObjects');
                Route::get('/{blog_posts_perspective}', 'PostsPerspective\PostsPerspectiveController@show');

                Route::post('/', 'PostsPerspective\PostsPerspectiveController@store');
                Route::post('/{blog_posts_perspective}/do/{action}', 'PostsPerspective\PostsPerspectiveController@doAction');

                Route::patch('/{blog_posts_perspective}', 'PostsPerspective\PostsPerspectiveController@update');
                Route::delete('/{blog_posts_perspective}', 'PostsPerspective\PostsPerspectiveController@destroy');
            }
        );

        // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE










    }
);
























