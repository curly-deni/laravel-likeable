<?php

namespace Zeroday\Likeable;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Like extends Eloquent
{
    protected $table = 'likes';
    public $timestamps = true;
    protected $fillable = ['likeable_id', 'likeable_type', 'user_id'];

    /**
     * @access private
     */
    public function likeable()
    {
        return $this->morphTo();
    }
}