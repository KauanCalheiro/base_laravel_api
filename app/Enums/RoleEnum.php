<?php

namespace App\Enums;

enum RoleEnum: string {
    case ADMIN = 'admin';

    public static function asArray(): array {
        return [
            self::ADMIN->name => self::ADMIN->value,
        ];
    }
}
