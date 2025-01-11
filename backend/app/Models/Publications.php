<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'conference_id',
        'title',
        'state',
        'content',
    ];

    /**
     * Get the conference that this publication belongs to.
     */
    public function conference()
    {
        return $this->belongsTo(Conference::class, 'conference_id');
    }
}
