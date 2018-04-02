<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
        'user_id', 'date', 'year', 'month', 'day', 'title', 'descr', 'done'
    ];

    /**
     * Get the user that owns the note.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
