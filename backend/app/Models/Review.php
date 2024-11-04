<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $primaryKey = 'review_id';
    protected $fillable = ['work_id', 'user_id', 'comment', 'rating'];

    public function work()
    {
        return $this->belongsTo(Work::class, 'work_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
