<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait Filterable {
    public function scopeFilter(Builder $query, Request $request) {
        foreach ($request->all() as $column => $value) {
            if ($this->isFilterableColumn($column) && !empty($value)) {
                $this->applyFilter($query, $column, $value);
            }
        }
        return $query;
    }

    protected function isFilterableColumn($column) {
        return in_array($column, $this->getFillable());
    }

    protected function applyFilter(Builder $query, $column, $value) {
        if ($this->isNullValue($value)) {
            $this->applyNullFilter($query, $column);
        } elseif ($this->isNotNullValue($value)) {
            $this->applyNotNullFilter($query, $column);
        } elseif (is_numeric($value)) {
            $this->applyNumberFilter($query, $column, $value);
        } elseif (is_array($value)) {
            $this->applyArrayFilter($query, $column, $value);
        } else {
            $this->applyStringFilter($query, $column, $value);
        }
    }

    protected function isNullValue($value) {
        return mb_convert_case($value, MB_CASE_UPPER) === 'NULL';
    }

    protected function isNotNullValue($value) {
        return mb_convert_case(
            is_array($value) ? implode('_', $value) : str_replace(' ', '_', $value),
            MB_CASE_UPPER
        ) === 'NOT_NULL';
    }

    protected function applyNullFilter(Builder $query, $column) {
        $query->whereNull($column);
    }

    protected function applyNotNullFilter(Builder $query, $column) {
        $query->whereNotNull($column);
    }

    protected function applyArrayFilter(Builder $query, $column, $value) {
        $query->whereIn($column, $value);
    }

    protected function applyNumberFilter(Builder $query, $column, $value) {
        $query->where($column, $value);
    }

    protected function applyStringFilter(Builder $query, $column, $value) {
        $query->whereRaw("unaccent($column) ILIKE unaccent(?)", ["%{$value}%"]);
    }
}
