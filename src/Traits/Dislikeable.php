<?php

namespace Aesis\Likeable\Traits;

use Aesis\Likeable\Models\Dislike;
use Aesis\Likeable\Models\DislikeCounter;

trait Dislikeable
{
    public static function bootDislikeable()
    {
        if (static::removeDislikesOnDelete()) {
            static::deleting(function ($model) {
                /** @var Dislikeable $model */
                $model->removeDislikes();
            });
        }
    }

    /**
     * Populate the $model->dislikes attribute
     */
    public function getDislikeCountAttribute()
    {
        return $this->dislikeCounter ? $this->dislikeCounter->count : 0;
    }

    /**
     * Add a dislike for this record by the given user.
     * @param $userId mixed - If null will use currently logged in user.
     */
    public function dislike($userId = null)
    {
        if (is_null($userId)) {
            $userId = auth()->id();
        }

        if ($userId) {
            $dislike = $this->dislikes()
                ->where('user_id', '=', $userId)
                ->first();

            if ($dislike) {
                return;
            }

            $dislike = new Dislike();
            $dislike->user_id = $userId;
            $this->dislikes()->save($dislike);
        }

        $this->incrementDislikeCount();
    }

    /**
     * Remove a dislike from this record for the given user.
     * @param $userId mixed - If null will use currently logged in user.
     */
    public function undislike($userId = null)
    {
        if (is_null($userId)) {
            $userId = auth()->id();
        }

        if ($userId) {
            $dislike = $this->dislikes()
                ->where('user_id', '=', $userId)
                ->first();

            if (!$dislike) {
                return;
            }

            $dislike->delete();
        }

        $this->decrementDislikeCount();
    }

    /**
     * Has the currently logged in user already "disliked" the current object
     *
     * @param string $userId
     * @return boolean
     */
    public function disliked($userId = null)
    {
        if (is_null($userId)) {
            $userId = auth()->id();
        }

        return (bool)$this->dislikes()
            ->where('user_id', '=', $userId)
            ->count();
    }

    /**
     * Should remove dislikes on model row delete (defaults to true)
     * public static removeDislikesOnDelete = false;
     */
    public static function removeDislikesOnDelete()
    {
        return isset(static::$removeDislikesOnDelete)
            ? static::$removeDislikesOnDelete
            : true;
    }

    /**
     * Delete dislikes related to the current record
     */
    public function removeDislikes()
    {
        $this->dislikes()->delete();
        $this->dislikeCounter()->delete();
    }


    /**
     * Collection of the dislikes on this record
     * @access private
     */
    public function dislikes()
    {
        return $this->morphMany(Dislike::class, 'dislikeable');
    }

    /**
     * Did the currently logged in user dislike this model
     * Example : if($book->disliked) { }
     * @return boolean
     * @access private
     */
    public function getDislikedAttribute()
    {
        return $this->disliked();
    }

    /**
     * Counter is a record that stores the total dislikes for the
     * morphed record
     * @access private
     */
    public function dislikeCounter()
    {
        return $this->morphOne(DislikeCounter::class, 'dislikeable');
    }

    /**
     * Private. Increment the total dislike count stored in the counter
     */
    private function incrementDislikeCount()
    {
        $counter = $this->dislikeCounter()->first();

        if ($counter) {
            $counter->count++;
            $counter->save();
        } else {
            $counter = new DislikeCounter();
            $counter->count = 1;
            $this->dislikeCounter()->save($counter);
        }
    }

    /**
     * Private. Decrement the total dislike count stored in the counter
     */
    private function decrementDislikeCount()
    {
        $counter = $this->dislikeCounter()->first();

        if ($counter) {
            $counter->count--;
            if ($counter->count) {
                $counter->save();
            } else {
                $counter->delete();
            }
        }
    }

    /**
     * Fetch records that are disliked by a given user.
     * Ex: Book::whereDislikedBy(123)->get();
     * @access private
     */
    public function scopeWhereDislikedBy($query, $userId = null)
    {
        if (is_null($userId)) {
            $userId = auth()->id();
        }

        return $query->whereHas('dislikes', function ($q) use ($userId) {
            $q->where('user_id', '=', $userId);
        });
    }
}
