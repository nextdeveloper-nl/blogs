<?php

namespace NextDeveloper\Blogs\Http\Controllers\PostSlugHistories;

use Illuminate\Http\Request;
use NextDeveloper\Blogs\Http\Controllers\AbstractController;
use NextDeveloper\Commons\Http\Response\ResponsableFactory;
use NextDeveloper\Blogs\Http\Requests\PostSlugHistories\PostSlugHistoriesUpdateRequest;
use NextDeveloper\Blogs\Database\Filters\PostSlugHistoriesQueryFilter;
use NextDeveloper\Blogs\Database\Models\PostSlugHistories;
use NextDeveloper\Blogs\Services\PostSlugHistoriesService;
use NextDeveloper\Blogs\Http\Requests\PostSlugHistories\PostSlugHistoriesCreateRequest;
use NextDeveloper\Commons\Http\Traits\Tags as TagsTrait;use NextDeveloper\Commons\Http\Traits\Addresses as AddressesTrait;
class PostSlugHistoriesController extends AbstractController
{
    private $model = PostSlugHistories::class;

    use TagsTrait;
    use AddressesTrait;
    /**
     * This method returns the list of postslughistories.
     *
     * optional http params:
     * - paginate: If you set paginate parameter, the result will be returned paginated.
     *
     * @param  PostSlugHistoriesQueryFilter $filter  An object that builds search query
     * @param  Request                      $request Laravel request object, this holds all data about request. Automatically populated.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(PostSlugHistoriesQueryFilter $filter, Request $request)
    {
        $data = PostSlugHistoriesService::get($filter, $request->all());

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
     * This function returns the list of actions that can be performed on this object.
     *
     * @return void
     */
    public function getActions()
    {
        $data = PostSlugHistoriesService::getActions();

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
     * Makes the related action to the object
     *
     * @param  $objectId
     * @param  $action
     * @return array
     */
    public function doAction($objectId, $action)
    {
        $actionId = PostSlugHistoriesService::doAction($objectId, $action, request()->all());

        return $this->withArray(
            [
            'action_id' =>  $actionId
            ]
        );
    }

    /**
     * This method receives ID for the related model and returns the item to the client.
     *
     * @param  $postSlugHistoriesId
     * @return mixed|null
     * @throws \Laravel\Octane\Exceptions\DdException
     */
    public function show($ref)
    {
        //  Here we are not using Laravel Route Model Binding. Please check routeBinding.md file
        //  in NextDeveloper Platform Project
        $model = PostSlugHistoriesService::getByRef($ref);

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method returns the list of sub objects the related object. Sub object means an object which is preowned by
     * this object.
     *
     * It can be tags, addresses, states etc.
     *
     * @param  $ref
     * @param  $subObject
     * @return void
     */
    public function relatedObjects($ref, $subObject)
    {
        $objects = PostSlugHistoriesService::relatedObjects($ref, $subObject);

        return ResponsableFactory::makeResponse($this, $objects);
    }

    /**
     * This method created PostSlugHistories object on database.
     *
     * @param  PostSlugHistoriesCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function store(PostSlugHistoriesCreateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = PostSlugHistoriesService::create($request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates PostSlugHistories object on database.
     *
     * @param  $postSlugHistoriesId
     * @param  PostSlugHistoriesUpdateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function update($postSlugHistoriesId, PostSlugHistoriesUpdateRequest $request)
    {
        if($request->has('validateOnly') && $request->get('validateOnly') == true) {
            return [
                'validation'    =>  'success'
            ];
        }

        $model = PostSlugHistoriesService::update($postSlugHistoriesId, $request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates PostSlugHistories object on database.
     *
     * @param  $postSlugHistoriesId
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function destroy($postSlugHistoriesId)
    {
        $model = PostSlugHistoriesService::delete($postSlugHistoriesId);

        return $this->noContent();
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
