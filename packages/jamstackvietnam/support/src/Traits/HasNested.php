<?php

namespace Jamstackvietnam\Support\Traits;

use TypiCMS\NestableTrait;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

trait HasNested
{
    use NestableTrait;

    protected static function updateTree($tree)
    {
        $tree = static::flatten($tree);

        list($ids, $parentIdCases, $positionCases, $params) = static::getDataForQuery($tree);

        $sql = "UPDATE `categories` SET
            `parent_id` = CASE `id` {$parentIdCases} END,
            `position` = CASE `id` {$positionCases} END,
            `updated_at` = ?
        WHERE `id` IN ({$ids})";

        return DB::update($sql, $params);
    }

    private static function flatten($element, $parent_id = null, $childkey = 'items')
    {
        $flatArray = array();
        foreach ($element as $key => $node) {
            if (array_key_exists($childkey, $node)) {
                $flatArray = array_merge($flatArray, static::flatten($node[$childkey], $node['id']));
                unset($node[$childkey]);

                $node['parent_id'] = $parent_id;
                $flatArray[] = $node;
            } else {
                $flatArray[] = $node;
            }
        }
        return $flatArray;
    }

    private static function getDataForQuery(array $tree)
    {
        $params = [];
        $ids = [];

        foreach (static::getAttributesList($tree) as $id => $values) {
            foreach ($values as $column => $value) {
                $cases[$column][] = "WHEN {$id} THEN ?";
                $params[$column][] = $value;
            }

            $ids[] = $id;
        }

        return static::prepareData($ids, $cases, $params);
    }

    private static function getAttributesList(array $tree)
    {
        $attributes = [];

        foreach ($tree as $position => $collection) {
            $attributes[$collection['id']] = [
                'parent_id' => $collection['parent_id'],
                'position' => $position,
            ];
        }

        return $attributes;
    }

    private static function prepareData(array $ids, array $cases, array $params)
    {
        $ids = implode(',', $ids);
        $parentIdCases = implode(' ', $cases['parent_id']);
        $positionCases = implode(' ', $cases['position']);

        $params = Arr::flatten($params);
        $params[] = Carbon::now();

        return [$ids, $parentIdCases, $positionCases, $params];
    }
}
