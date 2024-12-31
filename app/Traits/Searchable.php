<?php

namespace App\Traits;

use App\Helpers\EloquentHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait Searchable
{
    public function scopeSearch(Builder $query, Request $request)
    {
        if (empty($request->input('search'))) {
            return $query;
        }

        $search = $request->input('search');
        $columns = $this->searchableColumns ?? $this->getFillable();

        foreach ($columns as $column) {
            if (strpos($column, '.') !== false) {
                list($relation, $relatedColumn) = explode('.', $column);
                $relatedTable = EloquentHelper::resolveRelationJoin($this, $query, $relation);
            }
        }

        foreach ($columns as $column) {
            if (strpos($column, '.') !== false) {
                list($relation, $relatedColumn) = explode('.', $column);

                $relatedTable = EloquentHelper::resolveRelationJoin($this, $query, $relation);

                if ($relatedTable) {
                    $query->orWhereRaw("unaccent({$relatedTable}.{$relatedColumn}::text) ILIKE unaccent(?)", ["%{$search}%"]);
                }
            } else {
                $query->orWhereRaw("unaccent({$this->table}.{$column}::text) ILIKE unaccent(?)", ["%{$search}%"]);
            }
        }

        $query->select(array_map(function ($column) {
            return "{$this->table}.{$column}";
        }, $this->fillable));

        return $query;
    }
}
