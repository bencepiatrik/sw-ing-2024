<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Role extends Model
{
    use HasFactory;

    // Specify the table name if it's different from the default
    protected $table = 'roles';

    // Mass assignable fields
    protected $fillable = ['name'];

    // Relationship: A role can belong to one user
    public function users()
    {
        return $this->hasMany(User::class, 'role_id');
    }
}
