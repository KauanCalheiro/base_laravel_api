<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;

class EloquentHelper
{
    public static function isTableJoined(Builder $query, string $table): bool
    {
        $joins = $query->getQuery()->joins ?? [];
        foreach ($joins as $join) {
            if ($join->table === $table) {
                return true;
            }
        }
        return false;
    }

    public static function addJoinIfNotExists(Builder $query, string $relatedTable, string $foreignKey, string $localKey)
    {
        if (!self::isTableJoined($query, $relatedTable)) {
            $query->join($relatedTable, "{$foreignKey}", '=', "{$localKey}");
        }
    }

    /**
     * Resolve o relacionamento e adiciona o join no query builder, se necessário.
     *
     * @param object $model
     * @param Builder $query
     * @param string $relation
     * @return string|null Retorna a tabela relacionada, ou null se o relacionamento não existir
     */
    public static function resolveRelationJoin(object $model, Builder $query, string $relation): ?string
    {
        if (!method_exists($model, $relation)) {
            return null;
        }

        $relationInstance = $model->$relation();
        $relatedTable = $relationInstance->getRelated()->getTable();
        $foreignKey = $relationInstance->getQualifiedForeignKeyName();
        $localKey = $relationInstance->getQualifiedParentKeyName();

        self::addJoinIfNotExists($query, $relatedTable, $foreignKey, $localKey);

        return $relatedTable;
    }
}
