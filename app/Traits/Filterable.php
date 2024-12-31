<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait Filterable
{
    public function scopeFilter(Builder $query, Request $request)
    {
        foreach ($request->all() as $column => $value) {
            if (in_array($column, $this->getFillable()) && !empty($value)) {
                if (is_numeric($value)) {
                    $query->where($column, $value);
                } elseif (is_array($value)) {
                    $query->whereIn($column, $value);
                } else {
                    $query->whereRaw("unaccent($column) ILIKE unaccent(?)", ["%{$value}%"]);
                }
            }
        }

        return $query;
    }
}
