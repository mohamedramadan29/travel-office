<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class Admin extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'status'
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function Role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function hasAccess($config_permission)
    {
        $role = $this->Role;
        if (!$role) {
            return false;
        }
        foreach ($role->permission as $permission) {
            if ($permission == $config_permission ?? false) {
                return true;
            }
        }
    }

    ///////// Use Acceessories
    public function getStatusAttribute($value){
        return $value == 1 ? 'نشط' : 'غير نشط';
    }

}
