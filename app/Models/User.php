<?php

namespace App\Models;

use App\Traits\Filterable;
use App\Traits\Pageble;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable, HasRoles, Searchable, Filterable, Pageble, SoftDeletes;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
        'sex',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's sex.
     *
     * @return string
     */
    public function getSexAttribute($value) {
        $sexes = [
            'm' => __('male'),
            'f' => __('female'),
            'o' => __('other'),
        ];
        return $sexes[$value] ?? $sexes['o'];
    }

    /**
     * Accessor for the user's roles.
     */
    public function getRolesListAttribute(): array {
        return $this->roles->pluck('name')->toArray();
    }

    /**
     * Accessor for the user's permissions.
     */
    public function getPermissionsListAttribute(): array {
        return $this->getAllPermissions()->pluck('name')->toArray();
    }

    /**
     * Override the toArray method to include roles and permissions.
     */
    public function toArray(): array {
        return array_merge(parent::toArray(), [
            'roles' => $this->roles_list,
            'permissions' => $this->permissions_list,
        ]);
    }
}
