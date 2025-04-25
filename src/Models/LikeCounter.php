<?php

namespace Aesis\Likeable\Models;

use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Database\Eloquent\Model as Eloquent;

class LikeCounter extends Eloquent
{
    public $timestamps = false;
    protected $fillable = ['likeable_id', 'likeable_type', 'count'];

    /**
     * @access private
     */
    public function likeable()
    {
        return $this->morphTo();
    }

    /**
     * Delete all counts of the given model, and recount them and insert new counts
     *
     * @param $modelClass
     */
    public static function rebuild($modelClass)
    {
        if (empty($modelClass)) {
            throw new Exception('$modelClass cannot be empty/null. Maybe set the $morphClass variable on your model.');
        }

        $builder = Like::query()
            ->select(DB::raw('count(*) as count, likeable_type, likeable_id'))
            ->where('likeable_type', $modelClass)
            ->groupBy('likeable_id');

        $results = $builder->get();
        $inserts = $results->toArray();

        $tableName = (new static())->getTable();
        DB::table($tableName)->insert($inserts);
    }

    public function getTable()
    {
        return config('likeable.tables.like.count_table', 'likes_count');
    }
}
