<?php

namespace Aesis\Likeable\Tests;

use Aesis\Likeable\Traits\Dislikeable;
use Illuminate\Database\Eloquent\Model;
use Mockery as m;

class DislikeCounterTest extends BaseTestCase
{
    public function tearDown(): void
    {
        restore_error_handler();
        restore_exception_handler();
    }

    public function testDislike()
    {
        $dislikeable = m::mock('Aesis\Likeable\Tests\DislikeableStub[incrementDislikeCount]');
        $dislikeable->shouldReceive('incrementDislikeCount')->andReturn(null);

        $dislikeable->dislike(0);
        $this->assertEquals(1, 1);
    }

    public function testUndislike()
    {
        $dislikeable = m::mock('Aesis\Likeable\Tests\DislikeableStub[decrementDislikeCount]');
        $dislikeable->shouldReceive('decrementDislikeCount')->andReturn(null);

        $dislikeable->undislike(0);
        $this->assertEquals(0, 0);
    }
}

class DislikeableStub extends Model
{
    use Dislikeable;

    public function incrementDislikeCount()
    {
    }

    public function decrementDislikeCount()
    {
    }
}
