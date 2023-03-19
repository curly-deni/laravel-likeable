<?php

namespace Zeroday\Likeable\Tests;

use Illuminate\Database\Eloquent\Model;
use Mockery as m;
use Zeroday\Likeable\Likeable;

class CounterTest extends BaseTestCase
{
    public function testLike()
    {
        $likeable = m::mock('Zeroday\Likeable\Tests\LikeableStub[incrementLikeCount]');
        $likeable->shouldReceive('incrementLikeCount')->andReturn(null);

        $likeable->like(0);
    }

    public function testUnlike()
    {
        $likeable = m::mock('Zeroday\Likeable\Tests\LikeableStub[decrementLikeCount]');
        $likeable->shouldReceive('decrementLikeCount')->andReturn(null);

        $likeable->unlike(0);
    }
}

class LikeableStub extends Model
{
    use Likeable;

    public function incrementLikeCount()
    {
    }

    public function decrementLikeCount()
    {
    }
}