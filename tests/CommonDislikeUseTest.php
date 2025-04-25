<?php

namespace Aesis\Likeable\Tests;

use Aesis\Likeable\Models\Dislike;
use Aesis\Likeable\Models\DislikeCounter;
use Aesis\Likeable\Traits\Dislikeable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Schema;

class CommonDislikeUseTest extends BaseTestCase
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

    public function test_basic_dislike()
    {
        /** @var DislikeStub $stub */
        $stub = DislikeStub::create(['name' => 123]);

        $stub->dislike();

        $this->assertEquals(1, $stub->dislikeCount);
    }

    public function test_multiple_dislikes()
    {
        $stub = DislikeStub::create(['name' => 123]);

        $stub->dislike(1);
        $stub->dislike(2);
        $stub->dislike(3);
        $stub->dislike(4);

        $this->assertEquals(4, $stub->dislikeCount);
    }

    public function test_undislike()
    {
        /** @var DislikeStub $stub */
        $stub = DislikeStub::create(['name' => 123]);

        $stub->undislike(1);

        $this->assertEquals(0, $stub->dislikeCount);
    }

    public function test_where_disliked_by()
    {
        DislikeStub::create(['name' => 'A'])->dislike(1);
        DislikeStub::create(['name' => 'B'])->dislike(1);
        DislikeStub::create(['name' => 'C'])->dislike(1);

        $stubs = DislikeStub::whereDislikedBy(1)->get();
        $shouldBeEmpty = DislikeStub::whereDislikedBy(2)->get();

        $this->assertEquals(3, $stubs->count());
        $this->assertEmpty($shouldBeEmpty);
    }

    public function test_deleteModel_deletesDislikes()
    {
        /** @var DislikeStub $stub1 */
        $stub1 = DislikeStub::create(['name' => 456]);
        /** @var DislikeStub $stub2 */
        $stub2 = DislikeStub::create(['name' => 123]);
        /** @var DislikeStub $stub3 */
        $stub3 = DislikeStub::create(['name' => 888]);

        $stub1->dislike(1);
        $stub1->dislike(7);
        $stub1->dislike(8);
        $stub2->dislike(1);
        $stub2->dislike(2);
        $stub2->dislike(3);
        $stub2->dislike(4);

        $stub3->delete();
        $this->assertEquals(7, Dislike::count());
        $this->assertEquals(2, DislikeCounter::count());

        $stub1->delete();
        $this->assertEquals(4, Dislike::count());
        $this->assertEquals(1, DislikeCounter::count());

        $stub2->delete();
        $this->assertEquals(0, Dislike::count());
        $this->assertEquals(0, DislikeCounter::count());
    }

    public function test_rebuild_test()
    {
        $stub1 = DislikeStub::create(['name' => 456]);
        $stub2 = DislikeStub::create(['name' => 123]);

        $stub1->dislike(1);
        $stub1->dislike(7);
        $stub1->dislike(8);
        $stub2->dislike(1);
        $stub2->dislike(2);
        $stub2->dislike(3);
        $stub2->dislike(4);

        DislikeCounter::truncate();

        DislikeCounter::rebuild(DislikeStub::class);

        $this->assertEquals(2, DislikeCounter::count());
    }
}

/**
 * @mixin Eloquent
 */
class DislikeStub extends Model
{
    use Dislikeable;

    public $table = 'books';
}
