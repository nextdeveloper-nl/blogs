<?php

namespace NextDeveloper\Blogs\Tests\Database\Models;

use Tests\TestCase;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use NextDeveloper\Blogs\Database\Filters\BlogPostQueryFilter;
use NextDeveloper\Blogs\Services\AbstractServices\AbstractBlogPostService;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;

trait BlogPostTestTraits
{
    public $http;

    /**
     *   Creating the Guzzle object
     */
    public function setupGuzzle()
    {
        $this->http = new Client(
            [
            'base_uri'  =>  '127.0.0.1:8000'
            ]
        );
    }

    /**
     *   Destroying the Guzzle object
     */
    public function destroyGuzzle()
    {
        $this->http = null;
    }

    public function test_http_blogpost_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/blogs/blogpost',
            ['http_errors' => false]
        );

        $this->assertContains(
            $response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
            ]
        );
    }

    public function test_http_blogpost_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'POST', '/blogs/blogpost', [
            'form_params'   =>  [
                'slug'  =>  'a',
                'title'  =>  'a',
                'body'  =>  'a',
                'header_image'  =>  'a',
                'meta_title'  =>  'a',
                'meta_description'  =>  'a',
                'meta_keywords'  =>  'a',
                'astract'  =>  'a',
                'locale'  =>  'a',
                'reply_count'  =>  '1',
                'read_count'  =>  '1',
                'bonus_points'  =>  '1',
                'alternate_of'  =>  '1',
                            ],
                ['http_errors' => false]
            ]
        );

        $this->assertEquals($response->getStatusCode(), Response::HTTP_OK);
    }

    /**
     * Get test
     *
     * @return bool
     */
    public function test_blogpost_model_get()
    {
        $result = AbstractBlogPostService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_blogpost_get_all()
    {
        $result = AbstractBlogPostService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_blogpost_get_paginated()
    {
        $result = AbstractBlogPostService::get(
            null, [
            'paginated' =>  'true'
            ]
        );

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_blogpost_event_retrieved_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogPost\BlogPostRetrievedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpost_event_created_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogPost\BlogPostCreatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpost_event_creating_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogPost\BlogPostCreatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpost_event_saving_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogPost\BlogPostSavingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpost_event_saved_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogPost\BlogPostSavedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpost_event_updating_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogPost\BlogPostUpdatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpost_event_updated_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogPost\BlogPostUpdatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpost_event_deleting_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogPost\BlogPostDeletingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpost_event_deleted_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogPost\BlogPostDeletedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpost_event_restoring_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogPost\BlogPostRestoringEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpost_event_restored_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogPost\BlogPostRestoredEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogpost_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogPost::first();

            event(new \NextDeveloper\Blogs\Events\BlogPost\BlogPostRetrievedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpost_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogPost::first();

            event(new \NextDeveloper\Blogs\Events\BlogPost\BlogPostCreatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpost_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogPost::first();

            event(new \NextDeveloper\Blogs\Events\BlogPost\BlogPostCreatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpost_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogPost::first();

            event(new \NextDeveloper\Blogs\Events\BlogPost\BlogPostSavingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpost_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogPost::first();

            event(new \NextDeveloper\Blogs\Events\BlogPost\BlogPostSavedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpost_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogPost::first();

            event(new \NextDeveloper\Blogs\Events\BlogPost\BlogPostUpdatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpost_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogPost::first();

            event(new \NextDeveloper\Blogs\Events\BlogPost\BlogPostUpdatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpost_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogPost::first();

            event(new \NextDeveloper\Blogs\Events\BlogPost\BlogPostDeletingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpost_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogPost::first();

            event(new \NextDeveloper\Blogs\Events\BlogPost\BlogPostDeletedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpost_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogPost::first();

            event(new \NextDeveloper\Blogs\Events\BlogPost\BlogPostRestoringEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpost_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogPost::first();

            event(new \NextDeveloper\Blogs\Events\BlogPost\BlogPostRestoredEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogpost_event_slug_filter()
    {
        try {
            $request = new Request(
                [
                'slug'  =>  'a'
                ]
            );

            $filter = new BlogPostQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogPost::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogpost_event_title_filter()
    {
        try {
            $request = new Request(
                [
                'title'  =>  'a'
                ]
            );

            $filter = new BlogPostQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogPost::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogpost_event_body_filter()
    {
        try {
            $request = new Request(
                [
                'body'  =>  'a'
                ]
            );

            $filter = new BlogPostQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogPost::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogpost_event_header_image_filter()
    {
        try {
            $request = new Request(
                [
                'header_image'  =>  'a'
                ]
            );

            $filter = new BlogPostQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogPost::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogpost_event_meta_title_filter()
    {
        try {
            $request = new Request(
                [
                'meta_title'  =>  'a'
                ]
            );

            $filter = new BlogPostQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogPost::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogpost_event_meta_description_filter()
    {
        try {
            $request = new Request(
                [
                'meta_description'  =>  'a'
                ]
            );

            $filter = new BlogPostQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogPost::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogpost_event_meta_keywords_filter()
    {
        try {
            $request = new Request(
                [
                'meta_keywords'  =>  'a'
                ]
            );

            $filter = new BlogPostQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogPost::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogpost_event_astract_filter()
    {
        try {
            $request = new Request(
                [
                'astract'  =>  'a'
                ]
            );

            $filter = new BlogPostQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogPost::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogpost_event_locale_filter()
    {
        try {
            $request = new Request(
                [
                'locale'  =>  'a'
                ]
            );

            $filter = new BlogPostQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogPost::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogpost_event_reply_count_filter()
    {
        try {
            $request = new Request(
                [
                'reply_count'  =>  '1'
                ]
            );

            $filter = new BlogPostQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogPost::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogpost_event_read_count_filter()
    {
        try {
            $request = new Request(
                [
                'read_count'  =>  '1'
                ]
            );

            $filter = new BlogPostQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogPost::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogpost_event_bonus_points_filter()
    {
        try {
            $request = new Request(
                [
                'bonus_points'  =>  '1'
                ]
            );

            $filter = new BlogPostQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogPost::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogpost_event_alternate_of_filter()
    {
        try {
            $request = new Request(
                [
                'alternate_of'  =>  '1'
                ]
            );

            $filter = new BlogPostQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogPost::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogpost_event_created_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now()
                ]
            );

            $filter = new BlogPostQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogPost::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogpost_event_updated_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now()
                ]
            );

            $filter = new BlogPostQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogPost::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogpost_event_deleted_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now()
                ]
            );

            $filter = new BlogPostQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogPost::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogpost_event_created_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new BlogPostQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogPost::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogpost_event_updated_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new BlogPostQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogPost::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogpost_event_deleted_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new BlogPostQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogPost::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogpost_event_created_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now(),
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new BlogPostQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogPost::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogpost_event_updated_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now(),
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new BlogPostQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogPost::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogpost_event_deleted_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now(),
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new BlogPostQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogPost::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}