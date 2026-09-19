<?php

namespace NextDeveloper\Blogs\Tests\Database\Models;

use Tests\TestCase;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use NextDeveloper\Blogs\Database\Filters\BlogContentRequestQueryFilter;
use NextDeveloper\Blogs\Services\AbstractServices\AbstractBlogContentRequestService;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;

trait BlogContentRequestTestTraits
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

    public function test_http_blogcontentrequest_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/blogs/blogcontentrequest',
            ['http_errors' => false]
        );

        $this->assertContains(
            $response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
            ]
        );
    }

    public function test_http_blogcontentrequest_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'POST', '/blogs/blogcontentrequest', [
            'form_params'   =>  [
                'notes'  =>  'a',
                'rejection_reason'  =>  'a',
                'quote_amount'  =>  '1',
                    'requested_at'  =>  now(),
                    'submitted_at'  =>  now(),
                    'reviewed_at'  =>  now(),
                    'completed_at'  =>  now(),
                    'quoted_at'  =>  now(),
                    'accepted_at'  =>  now(),
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
    public function test_blogcontentrequest_model_get()
    {
        $result = AbstractBlogContentRequestService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_blogcontentrequest_get_all()
    {
        $result = AbstractBlogContentRequestService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_blogcontentrequest_get_paginated()
    {
        $result = AbstractBlogContentRequestService::get(
            null, [
            'paginated' =>  'true'
            ]
        );

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_blogcontentrequest_event_retrieved_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogContentRequest\BlogContentRequestRetrievedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequest_event_created_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogContentRequest\BlogContentRequestCreatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequest_event_creating_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogContentRequest\BlogContentRequestCreatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequest_event_saving_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogContentRequest\BlogContentRequestSavingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequest_event_saved_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogContentRequest\BlogContentRequestSavedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequest_event_updating_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogContentRequest\BlogContentRequestUpdatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequest_event_updated_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogContentRequest\BlogContentRequestUpdatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequest_event_deleting_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogContentRequest\BlogContentRequestDeletingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequest_event_deleted_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogContentRequest\BlogContentRequestDeletedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequest_event_restoring_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogContentRequest\BlogContentRequestRestoringEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequest_event_restored_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogContentRequest\BlogContentRequestRestoredEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequest_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::first();

            event(new \NextDeveloper\Blogs\Events\BlogContentRequest\BlogContentRequestRetrievedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequest_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::first();

            event(new \NextDeveloper\Blogs\Events\BlogContentRequest\BlogContentRequestCreatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequest_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::first();

            event(new \NextDeveloper\Blogs\Events\BlogContentRequest\BlogContentRequestCreatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequest_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::first();

            event(new \NextDeveloper\Blogs\Events\BlogContentRequest\BlogContentRequestSavingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequest_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::first();

            event(new \NextDeveloper\Blogs\Events\BlogContentRequest\BlogContentRequestSavedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequest_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::first();

            event(new \NextDeveloper\Blogs\Events\BlogContentRequest\BlogContentRequestUpdatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequest_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::first();

            event(new \NextDeveloper\Blogs\Events\BlogContentRequest\BlogContentRequestUpdatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequest_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::first();

            event(new \NextDeveloper\Blogs\Events\BlogContentRequest\BlogContentRequestDeletingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequest_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::first();

            event(new \NextDeveloper\Blogs\Events\BlogContentRequest\BlogContentRequestDeletedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequest_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::first();

            event(new \NextDeveloper\Blogs\Events\BlogContentRequest\BlogContentRequestRestoringEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequest_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::first();

            event(new \NextDeveloper\Blogs\Events\BlogContentRequest\BlogContentRequestRestoredEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequest_event_notes_filter()
    {
        try {
            $request = new Request(
                [
                'notes'  =>  'a'
                ]
            );

            $filter = new BlogContentRequestQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequest_event_rejection_reason_filter()
    {
        try {
            $request = new Request(
                [
                'rejection_reason'  =>  'a'
                ]
            );

            $filter = new BlogContentRequestQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequest_event_quote_amount_filter()
    {
        try {
            $request = new Request(
                [
                'quote_amount'  =>  '1'
                ]
            );

            $filter = new BlogContentRequestQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequest_event_requested_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'requested_atStart'  =>  now()
                ]
            );

            $filter = new BlogContentRequestQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequest_event_submitted_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'submitted_atStart'  =>  now()
                ]
            );

            $filter = new BlogContentRequestQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequest_event_reviewed_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'reviewed_atStart'  =>  now()
                ]
            );

            $filter = new BlogContentRequestQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequest_event_completed_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'completed_atStart'  =>  now()
                ]
            );

            $filter = new BlogContentRequestQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequest_event_quoted_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'quoted_atStart'  =>  now()
                ]
            );

            $filter = new BlogContentRequestQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequest_event_accepted_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'accepted_atStart'  =>  now()
                ]
            );

            $filter = new BlogContentRequestQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequest_event_created_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now()
                ]
            );

            $filter = new BlogContentRequestQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequest_event_updated_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now()
                ]
            );

            $filter = new BlogContentRequestQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequest_event_deleted_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now()
                ]
            );

            $filter = new BlogContentRequestQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequest_event_requested_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'requested_atEnd'  =>  now()
                ]
            );

            $filter = new BlogContentRequestQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequest_event_submitted_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'submitted_atEnd'  =>  now()
                ]
            );

            $filter = new BlogContentRequestQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequest_event_reviewed_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'reviewed_atEnd'  =>  now()
                ]
            );

            $filter = new BlogContentRequestQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequest_event_completed_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'completed_atEnd'  =>  now()
                ]
            );

            $filter = new BlogContentRequestQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequest_event_quoted_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'quoted_atEnd'  =>  now()
                ]
            );

            $filter = new BlogContentRequestQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequest_event_accepted_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'accepted_atEnd'  =>  now()
                ]
            );

            $filter = new BlogContentRequestQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequest_event_created_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new BlogContentRequestQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequest_event_updated_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new BlogContentRequestQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequest_event_deleted_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new BlogContentRequestQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequest_event_requested_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'requested_atStart'  =>  now(),
                'requested_atEnd'  =>  now()
                ]
            );

            $filter = new BlogContentRequestQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequest_event_submitted_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'submitted_atStart'  =>  now(),
                'submitted_atEnd'  =>  now()
                ]
            );

            $filter = new BlogContentRequestQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequest_event_reviewed_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'reviewed_atStart'  =>  now(),
                'reviewed_atEnd'  =>  now()
                ]
            );

            $filter = new BlogContentRequestQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequest_event_completed_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'completed_atStart'  =>  now(),
                'completed_atEnd'  =>  now()
                ]
            );

            $filter = new BlogContentRequestQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequest_event_quoted_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'quoted_atStart'  =>  now(),
                'quoted_atEnd'  =>  now()
                ]
            );

            $filter = new BlogContentRequestQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequest_event_accepted_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'accepted_atStart'  =>  now(),
                'accepted_atEnd'  =>  now()
                ]
            );

            $filter = new BlogContentRequestQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequest_event_created_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now(),
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new BlogContentRequestQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequest_event_updated_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now(),
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new BlogContentRequestQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequest_event_deleted_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now(),
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new BlogContentRequestQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequest::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}