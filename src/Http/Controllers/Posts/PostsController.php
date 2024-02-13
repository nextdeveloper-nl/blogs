<?php

namespace NextDeveloper\Blogs\Http\Controllers\Posts;

use Illuminate\Http\Request;
use NextDeveloper\Blogs\Http\Controllers\AbstractController;
use NextDeveloper\Commons\Http\Response\ResponsableFactory;
use NextDeveloper\Blogs\Http\Requests\Posts\PostsUpdateRequest;
use NextDeveloper\Blogs\Database\Filters\PostsQueryFilter;
use NextDeveloper\Blogs\Database\Models\Posts;
use NextDeveloper\Blogs\Services\PostsService;
use NextDeveloper\Blogs\Http\Requests\Posts\PostsCreateRequest;

use NextDeveloper\Commons\Http\Traits\Tags;
use NextDeveloper\Commons\Http\Traits\Addresses;

class PostsController extends AbstractController
{
    private $model = Posts::class;

    use Tags;
    use Addresses;
    /**
     * This method returns the list of posts.
     *
     * optional http params:
     * - paginate: If you set paginate parameter, the result will be returned paginated.
     *
     * @param  PostsQueryFilter $filter  An object that builds search query
     * @param  Request          $request Laravel request object, this holds all data about request. Automatically populated.
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(PostsQueryFilter $filter, Request $request)
    {
        $data = PostsService::get($filter, $request->all());

        return ResponsableFactory::makeResponse($this, $data);
    }

    /**
     * This method receives ID for the related model and returns the item to the client.
     *
     * @param  $postsId
     * @return mixed|null
     * @throws \Laravel\Octane\Exceptions\DdException
     */
    public function show($ref)
    {
        //  Here we are not using Laravel Route Model Binding. Please check routeBinding.md file
        //  in NextDeveloper Platform Project
        $model = PostsService::getByRef($ref);

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
        $objects = PostsService::relatedObjects($ref, $subObject);

        return ResponsableFactory::makeResponse($this, $objects);
    }

    /**
     * This method created Posts object on database.
     *
     * @param  PostsCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function store(PostsCreateRequest $request)
    {
        $model = PostsService::create($request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates Posts object on database.
     *
     * @param  $postsId
     * @param  CountryCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function update($postsId, PostsUpdateRequest $request)
    {
        $model = PostsService::update($postsId, $request->validated());

        return ResponsableFactory::makeResponse($this, $model);
    }

    /**
     * This method updates Posts object on database.
     *
     * @param  $postsId
     * @param  CountryCreateRequest $request
     * @return mixed|null
     * @throws \NextDeveloper\Commons\Exceptions\CannotCreateModelException
     */
    public function destroy($postsId)
    {
        $model = PostsService::delete($postsId);

        return $this->noContent();
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE

}
