<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

trait Relationable
{
    public function scopeWithRelations(Builder $query, Request $request)
    {
        $relations = $request->query('with', []);

        if (!is_array($relations)) {
            $relations = explode(',', $relations);
        }

        $parsedRelations = $this->parseRelations($relations);

        foreach ($parsedRelations as $relation => $nested) {
            if (method_exists($this, $relation)) {
                if (is_array($nested)) {
                    $query->with([$relation => function ($query) use ($nested) {
                        $query->with($nested);
                    }]);
                } else {
                    $query->with($relation);
                }
            }
        }

        return $query;
    }

    private function parseRelations(array $relations)
    {
        $parsed = [];

        foreach ($relations as $relation) {
            $parts = explode('.', $relation);
            $this->buildNestedRelations($parsed, $parts);
        }

        return $parsed;
    }

    private function buildNestedRelations(array &$parsed, array $parts)
    {
        if (count($parts) == 1) {
            $parsed[$parts[0]] = [];
        } else {
            $first = array_shift($parts);
            if (!isset($parsed[$first])) {
                $parsed[$first] = [];
            }
            $this->buildNestedRelations($parsed[$first], $parts);
        }
    }
}
