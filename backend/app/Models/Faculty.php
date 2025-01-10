<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['uni_id', 'name'];

    /**
     * Get the university that owns the faculty.
     */
    public function university()
    {
        return $this->belongsTo(University::class, 'uni_id');
    }

    /**
     * Get the departments under the faculty.
     */
    public function departments()
    {
        return $this->hasMany(Department::class, 'faculty_id');
    }
}
