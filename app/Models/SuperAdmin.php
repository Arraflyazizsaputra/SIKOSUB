<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class SuperAdmin extends Authenticatable
{
    use Notifiable;
    protected $table = 'super_admin';
    protected $fillable = ['name', 'email', 'phone', 'password'];
    protected $hidden = ['password', 'remember_token'];
}