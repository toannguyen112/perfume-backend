<?php

namespace App\Models\Post;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jamstackvietnam\Support\Traits\HasMedia;
use Nicolaslopezj\Searchable\SearchableTrait;
use Jamstackvietnam\Support\Models\Sluggable;
use Jamstackvietnam\Support\Models\BaseModel;

class Post extends BaseModel
{
    use HasFactory, SoftDeletes, Sluggable;
    use SearchableTrait;
    use HasMedia;

    protected $slugAttribute = 'title';

    public const STATUS_ACTIVE = 'ACTIVE';
    public const STATUS_INACTIVE = 'INACTIVE';

    public const STATUS_LIST = [
        self::STATUS_ACTIVE => 'ACTIVE',
        self::STATUS_INACTIVE => 'INACTIVE',
    ];

    public $with = ['files'];

    public $fillable = [
        'title',
        'slug',
        'status',
        'description',
        'summary',
        'content',
        'author',
        'posted_at',
        'view',
        'is_home',
        'is_featured',

        'meta_title',
        'custom_slug',
        'meta_description',
    ];

    protected $mediaFields = [
        'thumbnail'
    ];

    protected $searchable = [
        'columns' => [
            'posts.title' => 10,
            'posts.id' => 5,
            'posts.slug' => 2,
        ]
    ];

    protected $appends = ['thumbnail_path', 'url'];

    protected $casts = ['posted_at' => 'date'];

    public function modelRules()
    {
        return [
            'all' => [
                'title' => 'required|string|max:255',
            ],
        ];
    }

    protected static function booted()
    {
        static::saving(function (self $model) {
            if (request()->route() === null) return;
            if (request()->input('view') === null) $model->view = 0;
        });

        static::saved(function (self $model) {
            if (request()->route() === null) return;
            $model->saveCategories($model);
            $model->saveRelatedPosts($model);
        });
    }

    public function saveRelatedPosts($model)
    {
        $relatedPostIds = array_column(request()->input('post_related_ids', []), 'id');
        $model->relatedPosts()->sync($relatedPostIds, 'id');
    }

    public function relatedPosts()
    {
        return $this->belongsToMany(
            self::class,
            'post_related',
            'post_id',
            'related_post_id'
        );
    }

    public function getPostRelatedIdsAttribute()
    {
        return $this->relatedPosts;
    }

    public function scopeOrdered($query)
    {
        return $query->orderByRaw("is_featured DESC, view DESC, posted_at DESC");
    }

    public function getFormattedPostedAtAttribute(): string
    {
        return datetime_format($this->attributes['posted_at'], 'd/m/Y');
    }

    public function getUrlAttribute(): array
    {
        $urls = [];
        if ($this->status == self::STATUS_ACTIVE) {
            $urls[] = route("post.show", [
                'slug' => $this->custom_slug ?? $this->slug,
                'id' => $this->id
            ]);
        }
        return $urls;
    }

    public function transform()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->custom_slug ?? $this->slug,
            'thumbnail' => $this->thumbnail_path,
            'description' => $this->description,
            'author' => $this->author,
            'formatted_posted_at' => $this->formatted_posted_at,
        ];
    }

    public function transformDetails()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->custom_slug ?? $this->slug,
            'description' => $this->description,
            'summary' => $this->summary,
            'author' => $this->author,
            'thumbnail' => $this->thumbnail_path,
            'content' => transform_richtext($this->content),
            'formatted_posted_at' => $this->formatted_posted_at,
            'meta_title' => $this->meta_title ?? $this->name,
            'custom_slug' => $this->custom_slug  ?? $this->slug,
            'meta_description' =>  $this->meta_description ?? $this->summary,
            'related_posts' => $this->postRelated(),
        ];
    }

    public function postRelated()
    {
        $relatedPosts = $this->relatedPosts->take(8);

        $relatedPostIds = [];

        if ($relatedPosts->count() > 0) {
            $relatedPostIds = $relatedPosts->pluck('id');
        }

        if (count($relatedPosts) < 8) {
            $addPosts = Post::query()
                ->active()
                ->where('id', '<>', $this->id)
                ->whereNotIn('id', $relatedPostIds)
                ->search($this->title)
                ->take(8 - count($relatedPosts))
                ->get();

            if (count($addPosts) > 0) {
                $relatedPosts = $relatedPosts->concat($addPosts);
            }
        }

        return $relatedPosts->map(fn ($item) => $item->transform());
    }
}
