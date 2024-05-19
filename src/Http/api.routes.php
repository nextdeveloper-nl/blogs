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

        // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE





    }
);




















