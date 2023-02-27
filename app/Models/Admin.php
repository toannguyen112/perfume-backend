<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;

class Admin extends Authenticatable
{
    use SoftDeletes;
    use HasRoles;

    public $fillable = [
        'email',
        'password',
        'name'
    ];

    protected $guard_name = 'admin';

    public $with =  ['roles'];

    public function modelRules()
    {
        return [
            'update' => [
                'name' => 'string|required',
                'email' => 'required|email|unique:admins,email,' . request('id', 0) . '|max:255'
            ],
            'create' => [
                'name' => 'string|required',
                'new_password' => 'string|required|confirmed|min:8|max:50',
                'new_password_confirmation' => 'string|required',
                'email' => 'required|email|unique:admins,email|max:255'
            ],
        ];
    }

    protected static function booted()
    {
        static::saving(function ($model) {
            if (request()->getRequestUri() !== '/admin/admins/store') return;

            if ($newPassword = request()->input('new_password')) {
                $model->password = Hash::make($newPassword);
            }
        });
        static::saved(function ($user) {
            if (request()->getRequestUri() !== '/admin/admins/store') return;

            $user->saveRelations(request()->input('roles'));
        });
    }

    public function saveRelations($attributes)
    {
        $roles = Arr::pluck($attributes, 'name');
        $this->syncRoles($roles);
    }

    public function getFormattedUpdatedAtAttribute(): string
    {
        if (!empty($this->updated_at)) {
            return datetime_format($this->updated_at);
        }
        return '';
    }

    public function getFormattedCreatedAtAttribute(): string
    {
        if (!empty($this->created_at)) {
            return datetime_format($this->created_at);
        }
        return '';
    }

    public function getRoleNameAttribute()
    {
        return $this->getRoleNames();
    }
}
