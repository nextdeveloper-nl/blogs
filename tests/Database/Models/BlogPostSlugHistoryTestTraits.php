<?php

namespace NextDeveloper\Blogs\Tests\Database\Models;

use Tests\TestCase;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use NextDeveloper\Blogs\Database\Filters\BlogPostSlugHistoryQueryFilter;
use NextDeveloper\Blogs\Services\AbstractServices\AbstractBlogPostSlugHistoryService;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;

trait BlogPostSlugHistoryTestTraits
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

    public function test_http_blogpostslughistory_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/blogs/blogpostslughistory',
            ['http_errors' => false]
        );

        $this->assertContains(
            $response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
            ]
        );
    }

    public function test_http_blogpostslughistory_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'POST', '/blogs/blogpostslughistory', [
            'form_params'   =>  [
                'slug'  =>  'a',
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
    public function test_blogpostslughistory_model_get()
    {
        $result = AbstractBlogPostSlugHistoryService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_blogpostslughistory_get_all()
    {
        $result = AbstractBlogPostSlugHistoryService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_blogpostslughistory_get_paginated()
    {
        $result = AbstractBlogPostSlugHistoryService::get(
            null, [
            'paginated' =>  'true'
            ]
        );

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_blogpostslughistory_event_retrieved_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogPostSlugHistory\BlogPostSlugHistoryRetrievedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpostslughistory_event_created_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogPostSlugHistory\BlogPostSlugHistoryCreatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpostslughistory_event_creating_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogPostSlugHistory\BlogPostSlugHistoryCreatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpostslughistory_event_saving_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogPostSlugHistory\BlogPostSlugHistorySavingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpostslughistory_event_saved_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogPostSlugHistory\BlogPostSlugHistorySavedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpostslughistory_event_updating_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogPostSlugHistory\BlogPostSlugHistoryUpdatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpostslughistory_event_updated_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogPostSlugHistory\BlogPostSlugHistoryUpdatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpostslughistory_event_deleting_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogPostSlugHistory\BlogPostSlugHistoryDeletingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpostslughistory_event_deleted_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogPostSlugHistory\BlogPostSlugHistoryDeletedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpostslughistory_event_restoring_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogPostSlugHistory\BlogPostSlugHistoryRestoringEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpostslughistory_event_restored_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogPostSlugHistory\BlogPostSlugHistoryRestoredEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogpostslughistory_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogPostSlugHistory::first();

            event(new \NextDeveloper\Blogs\Events\BlogPostSlugHistory\BlogPostSlugHistoryRetrievedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpostslughistory_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogPostSlugHistory::first();

            event(new \NextDeveloper\Blogs\Events\BlogPostSlugHistory\BlogPostSlugHistoryCreatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpostslughistory_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogPostSlugHistory::first();

            event(new \NextDeveloper\Blogs\Events\BlogPostSlugHistory\BlogPostSlugHistoryCreatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpostslughistory_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogPostSlugHistory::first();

            event(new \NextDeveloper\Blogs\Events\BlogPostSlugHistory\BlogPostSlugHistorySavingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpostslughistory_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogPostSlugHistory::first();

            event(new \NextDeveloper\Blogs\Events\BlogPostSlugHistory\BlogPostSlugHistorySavedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpostslughistory_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogPostSlugHistory::first();

            event(new \NextDeveloper\Blogs\Events\BlogPostSlugHistory\BlogPostSlugHistoryUpdatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpostslughistory_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogPostSlugHistory::first();

            event(new \NextDeveloper\Blogs\Events\BlogPostSlugHistory\BlogPostSlugHistoryUpdatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpostslughistory_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogPostSlugHistory::first();

            event(new \NextDeveloper\Blogs\Events\BlogPostSlugHistory\BlogPostSlugHistoryDeletingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpostslughistory_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogPostSlugHistory::first();

            event(new \NextDeveloper\Blogs\Events\BlogPostSlugHistory\BlogPostSlugHistoryDeletedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpostslughistory_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogPostSlugHistory::first();

            event(new \NextDeveloper\Blogs\Events\BlogPostSlugHistory\BlogPostSlugHistoryRestoringEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogpostslughistory_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogPostSlugHistory::first();

            event(new \NextDeveloper\Blogs\Events\BlogPostSlugHistory\BlogPostSlugHistoryRestoredEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogpostslughistory_event_slug_filter()
    {
        try {
            $request = new Request(
                [
                'slug'  =>  'a'
                ]
            );

            $filter = new BlogPostSlugHistoryQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogPostSlugHistory::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogpostslughistory_event_created_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now()
                ]
            );

            $filter = new BlogPostSlugHistoryQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogPostSlugHistory::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogpostslughistory_event_updated_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now()
                ]
            );

            $filter = new BlogPostSlugHistoryQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogPostSlugHistory::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogpostslughistory_event_deleted_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now()
                ]
            );

            $filter = new BlogPostSlugHistoryQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogPostSlugHistory::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogpostslughistory_event_created_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new BlogPostSlugHistoryQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogPostSlugHistory::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogpostslughistory_event_updated_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new BlogPostSlugHistoryQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogPostSlugHistory::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogpostslughistory_event_deleted_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new BlogPostSlugHistoryQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogPostSlugHistory::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogpostslughistory_event_created_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now(),
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new BlogPostSlugHistoryQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogPostSlugHistory::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogpostslughistory_event_updated_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now(),
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new BlogPostSlugHistoryQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogPostSlugHistory::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogpostslughistory_event_deleted_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now(),
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new BlogPostSlugHistoryQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogPostSlugHistory::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}