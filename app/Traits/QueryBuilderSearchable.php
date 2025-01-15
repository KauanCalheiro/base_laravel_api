<?php

namespace App\Traits;

use App\Helpers\EloquentHelper;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Laravel\Scout\Searchable;

trait QueryBuilderSearchable {
    use Searchable;

    public function scopeWhereScout(Builder $query, string $search): Builder {
        return $query->whereIn('id', self::search($search)->get()->pluck('id'));
    }
}
