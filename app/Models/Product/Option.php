<?php

namespace App\Models\Product;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Jamstackvietnam\Support\Traits\HasMedia;
use Nicolaslopezj\Searchable\SearchableTrait;
use Jamstackvietnam\Support\Models\Sluggable;
use Jamstackvietnam\Support\Models\BaseModel;

class Option extends BaseModel
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

    public $fillable = [
        'title',
        'slug',
        'status',
    ];
}
