<?php

namespace NextDeveloper\Blogs\Database\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use NextDeveloper\Commons\Database\Traits\Filterable;
use NextDeveloper\Blogs\Database\Observers\PostsObserver;
use NextDeveloper\Commons\Database\Traits\UuidId;
use NextDeveloper\Commons\Common\Cache\Traits\CleanCache;
use NextDeveloper\Commons\Database\Traits\Taggable;

/**
 * Posts model.
 *
 * @package  NextDeveloper\Blogs\Database\Models
 * @property integer $id
 * @property string $uuid
 * @property string $slug
 * @property string $title
 * @property string $body
 * @property string $header_image
 * @property string $meta_title
 * @property string $meta_description
 * @property string $meta_keywords
 * @property integer $reply_count
 * @property integer $read_count
 * @property integer $bonus_points
 * @property boolean $is_active
 * @property boolean $is_locked
 * @property boolean $is_pinned
 * @property boolean $is_draft
 * @property boolean $is_markdown
 * @property array $tags
 * @property integer $iam_account_id
 * @property integer $iam_user_id
 * @property integer $common_category_id
 * @property integer $common_domain_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 */
class Posts extends Model
{
    use Filterable, UuidId, CleanCache, Taggable;
    use SoftDeletes;


    public $timestamps = true;

    protected $table = 'blog_posts';


    /**
     @var array
     */
    protected $guarded = [];

    protected $fillable = [
            'slug',
            'title',
            'body',
            'header_image',
            'meta_title',
            'meta_description',
            'meta_keywords',
            'reply_count',
            'read_count',
            'bonus_points',
            'is_active',
            'is_locked',
            'is_pinned',
            'is_draft',
            'is_markdown',
            'tags',
            'iam_account_id',
            'iam_user_id',
            'common_category_id',
            'common_domain_id',
    ];

    /**
      Here we have the fulltext fields. We can use these for fulltext search if enabled.
     */
    protected $fullTextFields = [

    ];

    /**
     @var array
     */
    protected $appends = [

    ];

    /**
     We are casting fields to objects so that we can work on them better
     *
     @var array
     */
    protected $casts = [
    'id' => 'integer',
    'slug' => 'string',
    'title' => 'string',
    'body' => 'string',
    'header_image' => 'string',
    'meta_title' => 'string',
    'meta_description' => 'string',
    'meta_keywords' => 'string',
    'reply_count' => 'integer',
    'read_count' => 'integer',
    'bonus_points' => 'integer',
    'is_active' => 'boolean',
    'is_locked' => 'boolean',
    'is_pinned' => 'boolean',
    'is_draft' => 'boolean',
    'is_markdown' => 'boolean',
    'tags' => \NextDeveloper\Commons\Database\Casts\TextArray::class,
    'common_category_id' => 'integer',
    'common_domain_id' => 'integer',
    'created_at' => 'datetime',
    'updated_at' => 'datetime',
    'deleted_at' => 'datetime',
    ];

    /**
     We are casting data fields.
     *
     @var array
     */
    protected $dates = [
    'created_at',
    'updated_at',
    'deleted_at',
    ];

    /**
     @var array
     */
    protected $with = [

    ];

    /**
     @var int
     */
    protected $perPage = 20;

    /**
     @return void
     */
    public static function boot()
    {
        parent::boot();

        //  We create and add Observer even if we wont use it.
        parent::observe(PostsObserver::class);

        self::registerScopes();
    }

    public static function registerScopes()
    {
        $globalScopes = config('blogs.scopes.global');
        $modelScopes = config('blogs.scopes.blog_posts');

        if(!$modelScopes) { $modelScopes = [];
        }
        if (!$globalScopes) { $globalScopes = [];
        }

        $scopes = array_merge(
            $globalScopes,
            $modelScopes
        );

        if($scopes) {
            foreach ($scopes as $scope) {
                static::addGlobalScope(app($scope));
            }
        }
    }

    // EDIT AFTER HERE - WARNING: ABOVE THIS LINE MAY BE REGENERATED AND YOU MAY LOSE CODE











}
