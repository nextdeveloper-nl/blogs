<?php

namespace NextDeveloper\Blogs\Database\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use NextDeveloper\Commons\Database\Traits\HasStates;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use NextDeveloper\Commons\Database\Traits\Filterable;
use NextDeveloper\Blogs\Database\Observers\ContentRequestItemsObserver;
use NextDeveloper\Commons\Database\Traits\UuidId;
use NextDeveloper\Commons\Database\Traits\HasObject;
use NextDeveloper\Commons\Common\Cache\Traits\CleanCache;
use NextDeveloper\Commons\Database\Traits\Taggable;
use NextDeveloper\Commons\Database\Traits\RunAsAdministrator;

/**
 * ContentRequestItems model.
 *
 * @package  NextDeveloper\Blogs\Database\Models
 * @property integer $id
 * @property string $uuid
 * @property integer $blog_content_request_id
 * @property $status
 * @property integer $position
 * @property integer $blog_post_id
 * @property string $find_text
 * @property string $replace_html
 * @property $target_url
 * @property $anchor_text
 * @property string $extra_sentence
 * @property $rel
 * @property integer $match_count
 * @property $submitted_title
 * @property string $submitted_body
 * @property string $submitted_abstract
 * @property $submitted_meta_title
 * @property string $submitted_meta_description
 * @property $submitted_author
 * @property boolean $is_markdown
 * @property integer $common_category_id
 * @property integer $common_domain_id
 * @property $locale
 * @property $brief_title
 * @property string $brief
 * @property $brief_keywords
 * @property $brief_audience
 * @property $brief_outline
 * @property $required_links
 * @property $writer_article_ref
 * @property $writer_stage
 * @property string $delivered_body
 * @property $delivered_format
 * @property \Carbon\Carbon $delivered_at
 * @property string $original_snapshot
 * @property $result
 * @property \Carbon\Carbon $applied_at
 * @property \Carbon\Carbon $reverted_at
 * @property string $error_message
 * @property integer $iam_account_id
 * @property integer $iam_user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 */
class ContentRequestItems extends Model
{
    use Filterable, UuidId, CleanCache, Taggable, HasStates, RunAsAdministrator, HasObject;
    use SoftDeletes;

    public $timestamps = true;

    protected $table = 'blog_content_request_items';


    /**
     @var array
     */
    protected $guarded = [];

    protected $fillable = [
            'blog_content_request_id',
            'status',
            'position',
            'blog_post_id',
            'find_text',
            'replace_html',
            'target_url',
            'anchor_text',
            'extra_sentence',
            'rel',
            'match_count',
            'submitted_title',
            'submitted_body',
            'submitted_abstract',
            'submitted_meta_title',
            'submitted_meta_description',
            'submitted_author',
            'is_markdown',
            'common_category_id',
            'common_domain_id',
            'locale',
            'brief_title',
            'brief',
            'brief_keywords',
            'brief_audience',
            'brief_outline',
            'required_links',
            'writer_article_ref',
            'writer_stage',
            'delivered_body',
            'delivered_format',
            'delivered_at',
            'original_snapshot',
            'result',
            'applied_at',
            'reverted_at',
            'error_message',
            'iam_account_id',
            'iam_user_id',
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
    'blog_content_request_id' => 'integer',
    'position' => 'integer',
    'blog_post_id' => 'integer',
    'find_text' => 'string',
    'replace_html' => 'string',
    'extra_sentence' => 'string',
    'match_count' => 'integer',
    'submitted_body' => 'string',
    'submitted_abstract' => 'string',
    'submitted_meta_description' => 'string',
    'is_markdown' => 'boolean',
    'common_category_id' => 'integer',
    'common_domain_id' => 'integer',
    'brief' => 'string',
    'brief_keywords' => 'array',
    'brief_outline' => 'array',
    'required_links' => 'array',
    'delivered_body' => 'string',
    'delivered_at' => 'datetime',
    'original_snapshot' => 'string',
    'result' => 'array',
    'applied_at' => 'datetime',
    'reverted_at' => 'datetime',
    'error_message' => 'string',
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
    'delivered_at',
    'applied_at',
    'reverted_at',
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
        parent::observe(ContentRequestItemsObserver::class);

        self::registerScopes();
    }

    public static function registerScopes()
    {
        $globalScopes = config('blogs.scopes.global');
        $modelScopes = config('blogs.scopes.blog_content_request_items');

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
