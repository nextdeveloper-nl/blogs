<?php

namespace NextDeveloper\Blogs\Tests\Database\Models;

use Tests\TestCase;
use GuzzleHttp\Client;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use NextDeveloper\Blogs\Database\Filters\BlogContentRequestItemQueryFilter;
use NextDeveloper\Blogs\Services\AbstractServices\AbstractBlogContentRequestItemService;
use Illuminate\Pagination\LengthAwarePaginator;
use League\Fractal\Resource\Collection;

trait BlogContentRequestItemTestTraits
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

    public function test_http_blogcontentrequestitem_get()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'GET',
            '/blogs/blogcontentrequestitem',
            ['http_errors' => false]
        );

        $this->assertContains(
            $response->getStatusCode(), [
            Response::HTTP_OK,
            Response::HTTP_NOT_FOUND
            ]
        );
    }

    public function test_http_blogcontentrequestitem_post()
    {
        $this->setupGuzzle();
        $response = $this->http->request(
            'POST', '/blogs/blogcontentrequestitem', [
            'form_params'   =>  [
                'find_text'  =>  'a',
                'replace_html'  =>  'a',
                'extra_sentence'  =>  'a',
                'submitted_body'  =>  'a',
                'submitted_abstract'  =>  'a',
                'submitted_meta_description'  =>  'a',
                'brief'  =>  'a',
                'delivered_body'  =>  'a',
                'original_snapshot'  =>  'a',
                'error_message'  =>  'a',
                'position'  =>  '1',
                'match_count'  =>  '1',
                    'delivered_at'  =>  now(),
                    'applied_at'  =>  now(),
                    'reverted_at'  =>  now(),
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
    public function test_blogcontentrequestitem_model_get()
    {
        $result = AbstractBlogContentRequestItemService::get();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_blogcontentrequestitem_get_all()
    {
        $result = AbstractBlogContentRequestItemService::getAll();

        $this->assertIsObject($result, Collection::class);
    }

    public function test_blogcontentrequestitem_get_paginated()
    {
        $result = AbstractBlogContentRequestItemService::get(
            null, [
            'paginated' =>  'true'
            ]
        );

        $this->assertIsObject($result, LengthAwarePaginator::class);
    }

    public function test_blogcontentrequestitem_event_retrieved_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogContentRequestItem\BlogContentRequestItemRetrievedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequestitem_event_created_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogContentRequestItem\BlogContentRequestItemCreatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequestitem_event_creating_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogContentRequestItem\BlogContentRequestItemCreatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequestitem_event_saving_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogContentRequestItem\BlogContentRequestItemSavingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequestitem_event_saved_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogContentRequestItem\BlogContentRequestItemSavedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequestitem_event_updating_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogContentRequestItem\BlogContentRequestItemUpdatingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequestitem_event_updated_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogContentRequestItem\BlogContentRequestItemUpdatedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequestitem_event_deleting_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogContentRequestItem\BlogContentRequestItemDeletingEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequestitem_event_deleted_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogContentRequestItem\BlogContentRequestItemDeletedEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequestitem_event_restoring_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogContentRequestItem\BlogContentRequestItemRestoringEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequestitem_event_restored_without_object()
    {
        try {
            event(new \NextDeveloper\Blogs\Events\BlogContentRequestItem\BlogContentRequestItemRestoredEvent());
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequestitem_event_retrieved_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::first();

            event(new \NextDeveloper\Blogs\Events\BlogContentRequestItem\BlogContentRequestItemRetrievedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequestitem_event_created_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::first();

            event(new \NextDeveloper\Blogs\Events\BlogContentRequestItem\BlogContentRequestItemCreatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequestitem_event_creating_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::first();

            event(new \NextDeveloper\Blogs\Events\BlogContentRequestItem\BlogContentRequestItemCreatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequestitem_event_saving_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::first();

            event(new \NextDeveloper\Blogs\Events\BlogContentRequestItem\BlogContentRequestItemSavingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequestitem_event_saved_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::first();

            event(new \NextDeveloper\Blogs\Events\BlogContentRequestItem\BlogContentRequestItemSavedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequestitem_event_updating_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::first();

            event(new \NextDeveloper\Blogs\Events\BlogContentRequestItem\BlogContentRequestItemUpdatingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequestitem_event_updated_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::first();

            event(new \NextDeveloper\Blogs\Events\BlogContentRequestItem\BlogContentRequestItemUpdatedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequestitem_event_deleting_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::first();

            event(new \NextDeveloper\Blogs\Events\BlogContentRequestItem\BlogContentRequestItemDeletingEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequestitem_event_deleted_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::first();

            event(new \NextDeveloper\Blogs\Events\BlogContentRequestItem\BlogContentRequestItemDeletedEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequestitem_event_restoring_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::first();

            event(new \NextDeveloper\Blogs\Events\BlogContentRequestItem\BlogContentRequestItemRestoringEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    public function test_blogcontentrequestitem_event_restored_with_object()
    {
        try {
            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::first();

            event(new \NextDeveloper\Blogs\Events\BlogContentRequestItem\BlogContentRequestItemRestoredEvent($model));
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequestitem_event_find_text_filter()
    {
        try {
            $request = new Request(
                [
                'find_text'  =>  'a'
                ]
            );

            $filter = new BlogContentRequestItemQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequestitem_event_replace_html_filter()
    {
        try {
            $request = new Request(
                [
                'replace_html'  =>  'a'
                ]
            );

            $filter = new BlogContentRequestItemQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequestitem_event_extra_sentence_filter()
    {
        try {
            $request = new Request(
                [
                'extra_sentence'  =>  'a'
                ]
            );

            $filter = new BlogContentRequestItemQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequestitem_event_submitted_body_filter()
    {
        try {
            $request = new Request(
                [
                'submitted_body'  =>  'a'
                ]
            );

            $filter = new BlogContentRequestItemQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequestitem_event_submitted_abstract_filter()
    {
        try {
            $request = new Request(
                [
                'submitted_abstract'  =>  'a'
                ]
            );

            $filter = new BlogContentRequestItemQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequestitem_event_submitted_meta_description_filter()
    {
        try {
            $request = new Request(
                [
                'submitted_meta_description'  =>  'a'
                ]
            );

            $filter = new BlogContentRequestItemQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequestitem_event_brief_filter()
    {
        try {
            $request = new Request(
                [
                'brief'  =>  'a'
                ]
            );

            $filter = new BlogContentRequestItemQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequestitem_event_delivered_body_filter()
    {
        try {
            $request = new Request(
                [
                'delivered_body'  =>  'a'
                ]
            );

            $filter = new BlogContentRequestItemQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequestitem_event_original_snapshot_filter()
    {
        try {
            $request = new Request(
                [
                'original_snapshot'  =>  'a'
                ]
            );

            $filter = new BlogContentRequestItemQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequestitem_event_error_message_filter()
    {
        try {
            $request = new Request(
                [
                'error_message'  =>  'a'
                ]
            );

            $filter = new BlogContentRequestItemQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequestitem_event_position_filter()
    {
        try {
            $request = new Request(
                [
                'position'  =>  '1'
                ]
            );

            $filter = new BlogContentRequestItemQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequestitem_event_match_count_filter()
    {
        try {
            $request = new Request(
                [
                'match_count'  =>  '1'
                ]
            );

            $filter = new BlogContentRequestItemQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequestitem_event_delivered_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'delivered_atStart'  =>  now()
                ]
            );

            $filter = new BlogContentRequestItemQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequestitem_event_applied_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'applied_atStart'  =>  now()
                ]
            );

            $filter = new BlogContentRequestItemQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequestitem_event_reverted_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'reverted_atStart'  =>  now()
                ]
            );

            $filter = new BlogContentRequestItemQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequestitem_event_created_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now()
                ]
            );

            $filter = new BlogContentRequestItemQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequestitem_event_updated_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now()
                ]
            );

            $filter = new BlogContentRequestItemQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequestitem_event_deleted_at_filter_start()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now()
                ]
            );

            $filter = new BlogContentRequestItemQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequestitem_event_delivered_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'delivered_atEnd'  =>  now()
                ]
            );

            $filter = new BlogContentRequestItemQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequestitem_event_applied_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'applied_atEnd'  =>  now()
                ]
            );

            $filter = new BlogContentRequestItemQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequestitem_event_reverted_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'reverted_atEnd'  =>  now()
                ]
            );

            $filter = new BlogContentRequestItemQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequestitem_event_created_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new BlogContentRequestItemQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequestitem_event_updated_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new BlogContentRequestItemQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequestitem_event_deleted_at_filter_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new BlogContentRequestItemQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequestitem_event_delivered_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'delivered_atStart'  =>  now(),
                'delivered_atEnd'  =>  now()
                ]
            );

            $filter = new BlogContentRequestItemQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequestitem_event_applied_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'applied_atStart'  =>  now(),
                'applied_atEnd'  =>  now()
                ]
            );

            $filter = new BlogContentRequestItemQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequestitem_event_reverted_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'reverted_atStart'  =>  now(),
                'reverted_atEnd'  =>  now()
                ]
            );

            $filter = new BlogContentRequestItemQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequestitem_event_created_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'created_atStart'  =>  now(),
                'created_atEnd'  =>  now()
                ]
            );

            $filter = new BlogContentRequestItemQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequestitem_event_updated_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'updated_atStart'  =>  now(),
                'updated_atEnd'  =>  now()
                ]
            );

            $filter = new BlogContentRequestItemQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }

    public function test_blogcontentrequestitem_event_deleted_at_filter_start_and_end()
    {
        try {
            $request = new Request(
                [
                'deleted_atStart'  =>  now(),
                'deleted_atEnd'  =>  now()
                ]
            );

            $filter = new BlogContentRequestItemQueryFilter($request);

            $model = \NextDeveloper\Blogs\Database\Models\BlogContentRequestItem::filter($filter)->first();
        } catch (\Exception $e) {
            $this->assertFalse(false, $e->getMessage());
        }

        $this->assertTrue(true);
    }
    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE
}