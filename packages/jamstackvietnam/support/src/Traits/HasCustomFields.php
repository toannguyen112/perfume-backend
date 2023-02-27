<?php

namespace Jamstackvietnam\Support\Traits;

use Illuminate\Support\Arr;

trait HasCustomFields
{
    public static function bootHasCustomFields()
    {
        static::saved(function ($model) {
            if (request()->route() === null) return;

            $model->transformMedia($model);
        });
    }

    private function transformMedia($model)
    {
        if (!isset($model->customFields['media'])) return;

        foreach ($model->customFields['media'] as $field) {
            $medias = request($field, []);

            $model->$field()
                ->whereNotIn('id', Arr::pluck($medias, 'id'))
                ->delete();

            foreach ($medias as $index => $media) {
                $model->$field()->updateOrCreate(['id' => $media['id']], [
                    'image_id' => $media['image_id'] ?? 0,
                    'image_url' => $media['image_url'] ?? null,
                    'position' => $index
                ]);
            }
        }
    }
}
