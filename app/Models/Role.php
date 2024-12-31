<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use \Spatie\Permission\Models\Role as BaseRole;

class Role extends BaseRole {
    const ADMIN = 'admin';

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
