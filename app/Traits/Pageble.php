<?php

namespace App\Traits;

use App\Helpers\EloquentHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait Pageble
{
    const LIMIT_DEFAULT  = 10;
    const OFFSET_DEFAULT = 0;
    const SORT_DEFAULT   = 'id';
    const ORDER_DEFAULT  = 'asc';

    public function scopePaginateQuery(Builder $query, Request $request)
    {
        $limit  = $request->query('limit', self::LIMIT_DEFAULT);
        $offset = $request->query('offset', self::OFFSET_DEFAULT);
        $sort   = $request->query('sort', self::SORT_DEFAULT);
        $order  = $request->query('order', self::ORDER_DEFAULT);

        if (strpos($sort, '.') !== false) {
            list($relation, $relatedColumn) = explode('.', $sort);

            $relatedTable = EloquentHelper::resolveRelationJoin($this, $query, $relation);

            if ($relatedTable) {
                $query->orderBy("{$relatedTable}.{$relatedColumn}", $order);
            }
        } else {
            $query->orderBy("{$this->table}.{$sort}", $order);
        }

        $query->limit($limit)->offset($offset);

        $query->select(array_map(function ($column) {
            return "{$this->table}.{$column}";
        }, $this->fillable));

        return $query;
    }
}
