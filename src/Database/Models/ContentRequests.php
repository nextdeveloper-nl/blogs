<?php

namespace NextDeveloper\Blogs\Database\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use NextDeveloper\Commons\Database\Traits\HasStates;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use NextDeveloper\Commons\Database\Traits\Filterable;
use NextDeveloper\Blogs\Database\Observers\ContentRequestsObserver;
use NextDeveloper\Commons\Database\Traits\UuidId;
use NextDeveloper\Commons\Database\Traits\HasObject;
use NextDeveloper\Commons\Common\Cache\Traits\CleanCache;
use NextDeveloper\Commons\Database\Traits\Taggable;
use NextDeveloper\Commons\Database\Traits\RunAsAdministrator;

/**
 * ContentRequests model.
 *
 * @package  NextDeveloper\Blogs\Database\Models
 * @property integer $id
 * @property string $uuid
 * @property $type
 * @property $status
 * @property $title
 * @property $partner_name
 * @property $partner_email
 * @property $partner_domain
 * @property integer $crm_account_id
 * @property string $notes
 * @property $source_reference
 * @property \Carbon\Carbon $requested_at
 * @property \Carbon\Carbon $submitted_at
 * @property \Carbon\Carbon $reviewed_at
 * @property integer $reviewer_user_id
 * @property \Carbon\Carbon $completed_at
 * @property string $rejection_reason
 * @property $quote_amount
 * @property $quote_currency_code
 * @property \Carbon\Carbon $quoted_at
 * @property \Carbon\Carbon $accepted_at
 * @property integer $iam_account_id
 * @property integer $iam_user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 */
class ContentRequests extends Model
{
    use Filterable, UuidId, CleanCache, Taggable, HasStates, RunAsAdministrator, HasObject;
    use SoftDeletes;

    public $timestamps = true;

    protected $table = 'blog_content_requests';


    /**
     @var array
     */
    protected $guarded = [];

    protected $fillable = [
            'type',
            'status',
            'title',
            'partner_name',
            'partner_email',
            'partner_domain',
            'crm_account_id',
            'notes',
            'source_reference',
            'requested_at',
            'submitted_at',
            'reviewed_at',
            'reviewer_user_id',
            'completed_at',
            'rejection_reason',
            'quote_amount',
            'quote_currency_code',
            'quoted_at',
            'accepted_at',
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
    'crm_account_id' => 'integer',
    'notes' => 'string',
    'requested_at' => 'datetime',
    'submitted_at' => 'datetime',
    'reviewed_at' => 'datetime',
    'reviewer_user_id' => 'integer',
    'completed_at' => 'datetime',
    'rejection_reason' => 'string',
    'quote_amount' => 'double',
    'quoted_at' => 'datetime',
    'accepted_at' => 'datetime',
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
    'requested_at',
    'submitted_at',
    'reviewed_at',
    'completed_at',
    'quoted_at',
    'accepted_at',
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
        parent::observe(ContentRequestsObserver::class);

        self::registerScopes();
    }

    public static function registerScopes()
    {
        $globalScopes = config('blogs.scopes.global');
        $modelScopes = config('blogs.scopes.blog_content_requests');

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
