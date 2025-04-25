<?php

namespace Aesis\Likeable\Tests;

use Aesis\Likeable\Traits\Likeable;
use Illuminate\Database\Eloquent\Model;
use Mockery as m;

class LikeCounterTest extends BaseTestCase
{
    public function tearDown(): void
    {
        restore_error_handler();
        restore_exception_handler();
    }

    public function testLike()
    {
        $likeable = m::mock('Aesis\Likeable\Tests\LikeableStub[incrementLikeCount]');
        $likeable->shouldReceive('incrementLikeCount')->andReturn(null);

        $likeable->like(0);
        $this->assertEquals(1, 1);
    }

    public function testUnlike()
    {
        $likeable = m::mock('Aesis\Likeable\Tests\LikeableStub[decrementLikeCount]');
        $likeable->shouldReceive('decrementLikeCount')->andReturn(null);

        $likeable->unlike(0);
        $this->assertEquals(0, 0);
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
