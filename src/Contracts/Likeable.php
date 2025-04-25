<?php

namespace Aesis\Likeable\Contracts;

interface Likeable
{
    /**
     * Add a like for this record by the given user.
     * @param $userId mixed - If null will use currently logged in user.
     */
    public function like($userId = null);

    /**
     * Remove a like from this record for the given user.
     * @param $userId mixed - If null will use currently logged in user.
     */
    public function unlike($userId = null);

    /**
     * Has the currently logged in user already "liked" the current object
     *
     * @param string $userId
     * @return boolean
     */
    public function liked($userId = null);

    /**
     * Delete likes related to the current record
     */
    public function removeLikes();


    /**
     * Collection of the likes on this record
     * @access private
     */
    public function likes();

    /**
     * Fetch records that are liked by a given user.
     * Ex: Book::whereLikedBy(123)->get();
     * @access private
     */
    public function scopeWhereLikedBy($query, $userId = null);
}
