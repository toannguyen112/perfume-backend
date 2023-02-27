<?php

namespace App\Models\Post;;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Nicolaslopezj\Searchable\SearchableTrait;
use Jamstackvietnam\Support\Models\Sluggable;
use Jamstackvietnam\Support\Models\BaseModel;

class PostCategory extends BaseModel
{
    use HasFactory, SoftDeletes, Sluggable;
    use SearchableTrait;

    protected $slugAttribute = 'title';

    const STATUS_ACTIVE = 'ACTIVE';
    const STATUS_INACTIVE = 'INACTIVE';

    public const STATUS_LIST = [
        self::STATUS_ACTIVE => 'ACTIVE',
        self::STATUS_INACTIVE => 'INACTIVE',
    ];

    protected $searchable = [
        'columns' => [
            'post_categories.title' => 10,
        ],
    ];

    public $fillable = [
        'title',
        'slug',
        'status',
        'parent_id',
        'view',

        'meta_title',
        'custom_slug',
        'meta_description',
    ];

    public $with = ['nodes'];

    protected $appends = ['url'];

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'post_ref_categories');
    }

    public function modelRules()
    {
        return [
            'all' => [
                'title' => 'required|string|max:255',
            ],
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public static function getRoot()
    {
        return static::with('nodes')
            ->orderBy('parent_id')
            ->whereNull('parent_id')
            ->orWhere('parent_id', 0)
            ->get();
    }

    public static function getCategories()
    {
        return PostCategory::active()
            ->where('parent_id', 0)
            ->orderBy('created_at', 'DESC')
            ->get()
            ->map(fn ($item) => $item->transformDetails());
    }

    public function nodes()
    {
        return $this->children()
            ->with('nodes')
            ->orderBy('parent_id')
            ->orderBy('id', 'desc');
    }

    public function parentNode()
    {
        return $this->parent()->with('parentNode');
    }

    public static function transformAsBreadcrumb($category)
    {
        if ($category) {
            return collect(self::flatAncestors($category))
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'slug' => $item->custom_slug ?? $item->slug,
                    ];
                })
                ->reverse()->values();
        }
        return collect([]);
    }

    public static function flatAncestors($model): array
    {
        if (!$model) return [];

        $result[] = $model;
        if (!empty($model->parentNode)) {
            $result = array_merge($result, self::flatAncestors($model->parentNode));
        }

        return $result;
    }

    public function getUrlAttribute(): array
    {
        $urls = [];
        if ($this->status == self::STATUS_ACTIVE) {
            $urls[] = route("post.category-show", [
                'slug' => $this->custom_slug ?? $this->slug
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
            'meta_title' => $this->meta_title ?? $this->title,
            'custom_slug' => $this->custom_slug  ?? $this->slug,
            'meta_description' =>  $this->meta_description ?? $this->summary,
        ];
    }

    public function transformDetails()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->custom_slug ?? $this->slug,
            'nodes' => $this->nodes->map(fn ($item) => $item->transform()),
            'meta_title' => $this->meta_title ?? $this->title,
            'custom_slug' => $this->custom_slug  ?? $this->slug,
            'meta_description' =>  $this->meta_description ?? $this->summary,
        ];
    }
}
