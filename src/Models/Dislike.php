<?php

namespace Aesis\Likeable\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Dislike extends Eloquent
{
    public $timestamps = true;
    protected $fillable = ['dislikeable_id', 'dislikeable_type', 'user_id'];

    /**
     * @access private
     */
    public function dislikeable()
    {
        return $this->morphTo();
    }

    public function getTable()
    {
        return config('likeable.tables.dislike.entity_table', 'dislikes');
    }
}
