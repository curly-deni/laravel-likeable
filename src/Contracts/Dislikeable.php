<?php

namespace Aesis\Likeable\Contracts;

interface Dislikeable
{
    /**
     * Add a dislike for this record by the given user.
     * @param $userId mixed - If null will use currently logged in user.
     */
    public function dislike($userId = null);

    /**
     * Remove a dislike from this record for the given user.
     * @param $userId mixed - If null will use currently logged in user.
     */
    public function undislike($userId = null);

    /**
     * Has the currently logged in user already "disliked" the current object
     *
     * @param string $userId
     * @return boolean
     */
    public function disliked($userId = null);

    /**
     * Delete dislikes related to the current record
     */
    public function removeDislikes();


    /**
     * Collection of the dislikes on this record
     * @access private
     */
    public function dislikes();

    /**
     * Fetch records that are disliked by a given user.
     * Ex: Book::whereDislikedBy(123)->get();
     * @access private
     */
    public function scopeWhereDislikedBy($query, $userId = null);
}
