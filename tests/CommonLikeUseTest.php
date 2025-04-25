<?php

namespace Aesis\Likeable\Tests;

use Aesis\Likeable\Models\Like;
use Aesis\Likeable\Models\LikeCounter;
use Aesis\Likeable\Traits\Likeable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Schema;

class CommonLikeUseTest extends BaseTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Model::unguard();
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        Schema::create('books', function ($table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
        });
    }

    public function tearDown(): void
    {
        Schema::drop('books');

        restore_error_handler();
        restore_exception_handler();
    }

    public function test_basic_like()
    {
        /** @var LikeStub $stub */
        $stub = LikeStub::create(['name' => 123]);

        $stub->like();

        $this->assertEquals(1, $stub->likeCount);
    }

    public function test_multiple_likes()
    {
        $stub = LikeStub::create(['name' => 123]);

        $stub->like(1);
        $stub->like(2);
        $stub->like(3);
        $stub->like(4);

        $this->assertEquals(4, $stub->likeCount);
    }

    public function test_unlike()
    {
        /** @var LikeStub $stub */
        $stub = LikeStub::create(['name' => 123]);

        $stub->unlike(1);

        $this->assertEquals(0, $stub->likeCount);
    }

    public function test_where_liked_by()
    {
        LikeStub::create(['name' => 'A'])->like(1);
        LikeStub::create(['name' => 'B'])->like(1);
        LikeStub::create(['name' => 'C'])->like(1);

        $stubs = LikeStub::whereLikedBy(1)->get();
        $shouldBeEmpty = LikeStub::whereLikedBy(2)->get();

        $this->assertEquals(3, $stubs->count());
        $this->assertEmpty($shouldBeEmpty);
    }

    public function test_deleteModel_deletesLikes()
    {
        /** @var LikeStub $stub1 */
        $stub1 = LikeStub::create(['name' => 456]);
        /** @var LikeStub $stub2 */
        $stub2 = LikeStub::create(['name' => 123]);
        /** @var LikeStub $stub3 */
        $stub3 = LikeStub::create(['name' => 888]);

        $stub1->like(1);
        $stub1->like(7);
        $stub1->like(8);
        $stub2->like(1);
        $stub2->like(2);
        $stub2->like(3);
        $stub2->like(4);

        $stub3->delete();
        $this->assertEquals(7, Like::count());
        $this->assertEquals(2, LikeCounter::count());

        $stub1->delete();
        $this->assertEquals(4, Like::count());
        $this->assertEquals(1, LikeCounter::count());

        $stub2->delete();
        $this->assertEquals(0, Like::count());
        $this->assertEquals(0, LikeCounter::count());
    }

    public function test_rebuild_test()
    {
        $stub1 = LikeStub::create(['name' => 456]);
        $stub2 = LikeStub::create(['name' => 123]);

        $stub1->like(1);
        $stub1->like(7);
        $stub1->like(8);
        $stub2->like(1);
        $stub2->like(2);
        $stub2->like(3);
        $stub2->like(4);

        LikeCounter::truncate();

        LikeCounter::rebuild(LikeStub::class);

        $this->assertEquals(2, LikeCounter::count());
    }
}

/**
 * @mixin Eloquent
 */
class LikeStub extends Model
{
    use Likeable;

    public $table = 'books';
}
