<?php

namespace Aesis\Likeable\Models;

use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Database\Eloquent\Model as Eloquent;

class DislikeCounter extends Eloquent
{
    public $timestamps = false;
    protected $fillable = ['dislikeable_id', 'dislikeable_type', 'count'];

    /**
     * @access private
     */
    public function dislikeable()
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

        $builder = Dislike::query()
            ->select(DB::raw('count(*) as count, dislikeable_type, dislikeable_id'))
            ->where('dislikeable_type', $modelClass)
            ->groupBy('dislikeable_id');

        $results = $builder->get();
        $inserts = $results->toArray();

        $tableName = (new static())->getTable();
        DB::table($tableName)->insert($inserts);
    }

    public function getTable()
    {
        return config('likeable.tables.dislike.count_table', 'dislikes_count');
    }
}
