<?php

namespace Aesis\Likeable\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Like extends Eloquent
{
    public $timestamps = true;
    protected $fillable = ['likeable_id', 'likeable_type', 'user_id'];

    /**
     * @access private
     */
    public function likeable()
    {
        return $this->morphTo();
    }

    public function getTable()
    {
        return config('likeable.tables.like.entity_table', 'likes');
    }
}
