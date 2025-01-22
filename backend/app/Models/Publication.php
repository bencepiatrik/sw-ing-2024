<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'publications';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'conference_id',
        'title',
        'abstract',
        'keywords',
        'user_id',
        'files',
        'status',
    ];

    /**
     * Get the conference that this publication belongs to.
     */
    public function conference()
    {
        return $this->belongsTo(Conference::class, 'conference_id');
    }

    /**
     * Get the user (author) of this publication.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
