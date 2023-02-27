<?php

namespace Jamstackvietnam\Support\Models;

use Jamstackvietnam\Support\Models\BaseModel;

class MetaData extends BaseModel
{
    public $fillable = [
        'entity_id', 'entity_type',
        'key', 'value'
    ];

    public function getMetaKeywordsAttribute($keywords)
    {
        return is_array($keywords) ? $keywords : [];
    }
}
