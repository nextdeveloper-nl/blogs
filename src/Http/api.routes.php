<?php

Route::prefix('blogs')->group(
    function () {
        Route::prefix('accounts')->group(
            function () {
                Route::get('/', 'Accounts\AccountsController@index');
                Route::get('/actions', 'Accounts\AccountsController@getActions');

                Route::get('{blog_accounts}/tags ', 'Accounts\AccountsController@tags');
                Route::post('{blog_accounts}/tags ', 'Accounts\AccountsController@saveTags');
                Route::get('{blog_accounts}/addresses ', 'Accounts\AccountsController@addresses');
                Route::post('{blog_accounts}/addresses ', 'Accounts\AccountsController@saveAddresses');

                Route::get('/{blog_accounts}/{subObjects}', 'Accounts\AccountsController@relatedObjects');
                Route::get('/{blog_accounts}', 'Accounts\AccountsController@show');

                Route::post('/', 'Accounts\AccountsController@store');
                Route::post('/{blog_accounts}/do/{action}', 'Accounts\AccountsController@doAction');

                Route::patch('/{blog_accounts}', 'Accounts\AccountsController@update');
                Route::delete('/{blog_accounts}', 'Accounts\AccountsController@destroy');
            }
        );

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

        Route::prefix('accounts-perspective')->group(
            function () {
                Route::get('/', 'AccountsPerspective\AccountsPerspectiveController@index');
                Route::get('/actions', 'AccountsPerspective\AccountsPerspectiveController@getActions');

                Route::get('{blog_accounts_perspective}/tags ', 'AccountsPerspective\AccountsPerspectiveController@tags');
                Route::post('{blog_accounts_perspective}/tags ', 'AccountsPerspective\AccountsPerspectiveController@saveTags');
                Route::get('{blog_accounts_perspective}/addresses ', 'AccountsPerspective\AccountsPerspectiveController@addresses');
                Route::post('{blog_accounts_perspective}/addresses ', 'AccountsPerspective\AccountsPerspectiveController@saveAddresses');

                Route::get('/{blog_accounts_perspective}/{subObjects}', 'AccountsPerspective\AccountsPerspectiveController@relatedObjects');
                Route::get('/{blog_accounts_perspective}', 'AccountsPerspective\AccountsPerspectiveController@show');

                Route::post('/', 'AccountsPerspective\AccountsPerspectiveController@store');
                Route::post('/{blog_accounts_perspective}/do/{action}', 'AccountsPerspective\AccountsPerspectiveController@doAction');

                Route::patch('/{blog_accounts_perspective}', 'AccountsPerspective\AccountsPerspectiveController@update');
                Route::delete('/{blog_accounts_perspective}', 'AccountsPerspective\AccountsPerspectiveController@destroy');
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







































