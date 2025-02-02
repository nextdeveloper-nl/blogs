<?php

namespace NextDeveloper\Blogs\Tests\Database\Models;

use Tests\TestCase;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use NextDeveloper\Blogs\Database\Filters\BlogAccountQueryFilter;
use NextDeveloper\Blogs\Services\AbstractServices\AbstractBlogAccountService;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;

trait BlogAccountTestTraits
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

    public function test_http_blogaccount_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/blogs/blogaccount',
            ['http_errors' => false]
        );

        $this->assertContains(
            $response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
            ]
        );
    }

    public function test_http_blogaccount_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'POST', '/blogs/blogaccount', [
            'form_params'   =>  [
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
    public function test_blogaccount_model_get()
    {
        $result = AbstractBlogAccountService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_blogaccount_get_all()
    {
        $result = AbstractBlogAccountService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_blogaccount_get_paginated()
    {
        $result = AbstractBlogAccountService::get(
            null, [
            'paginated' =>  'true'
            ]
        );

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_blogaccount_event_retrieved_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogAccount\BlogAccountRetrievedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogaccount_event_created_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogAccount\BlogAccountCreatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogaccount_event_creating_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogAccount\BlogAccountCreatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogaccount_event_saving_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogAccount\BlogAccountSavingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogaccount_event_saved_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogAccount\BlogAccountSavedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogaccount_event_updating_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogAccount\BlogAccountUpdatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogaccount_event_updated_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogAccount\BlogAccountUpdatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogaccount_event_deleting_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogAccount\BlogAccountDeletingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogaccount_event_deleted_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogAccount\BlogAccountDeletedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogaccount_event_restoring_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogAccount\BlogAccountRestoringEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogaccount_event_restored_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogAccount\BlogAccountRestoredEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogaccount_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogAccount::first();

            event(new \NextDeveloper\Blogs\Events\BlogAccount\BlogAccountRetrievedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogaccount_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogAccount::first();

            event(new \NextDeveloper\Blogs\Events\BlogAccount\BlogAccountCreatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogaccount_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogAccount::first();

            event(new \NextDeveloper\Blogs\Events\BlogAccount\BlogAccountCreatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogaccount_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogAccount::first();

            event(new \NextDeveloper\Blogs\Events\BlogAccount\BlogAccountSavingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogaccount_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogAccount::first();

            event(new \NextDeveloper\Blogs\Events\BlogAccount\BlogAccountSavedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogaccount_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogAccount::first();

            event(new \NextDeveloper\Blogs\Events\BlogAccount\BlogAccountUpdatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogaccount_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogAccount::first();

            event(new \NextDeveloper\Blogs\Events\BlogAccount\BlogAccountUpdatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogaccount_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogAccount::first();

            event(new \NextDeveloper\Blogs\Events\BlogAccount\BlogAccountDeletingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogaccount_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogAccount::first();

            event(new \NextDeveloper\Blogs\Events\BlogAccount\BlogAccountDeletedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogaccount_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogAccount::first();

            event(new \NextDeveloper\Blogs\Events\BlogAccount\BlogAccountRestoringEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogaccount_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogAccount::first();

            event(new \NextDeveloper\Blogs\Events\BlogAccount\BlogAccountRestoredEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogaccount_event_created_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now()
                ]
            );

            $filter = new BlogAccountQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogAccount::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogaccount_event_updated_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now()
                ]
            );

            $filter = new BlogAccountQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogAccount::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogaccount_event_deleted_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now()
                ]
            );

            $filter = new BlogAccountQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogAccount::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogaccount_event_created_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new BlogAccountQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogAccount::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogaccount_event_updated_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new BlogAccountQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogAccount::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogaccount_event_deleted_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new BlogAccountQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogAccount::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogaccount_event_created_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now(),
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new BlogAccountQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogAccount::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogaccount_event_updated_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now(),
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new BlogAccountQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogAccount::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogaccount_event_deleted_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now(),
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new BlogAccountQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogAccount::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}