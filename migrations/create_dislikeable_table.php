<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        $morphType = config('likeable.tables.morph_type', 'biginteger');
        Schema::create(config('likeable.tables.dislike.entity_table', 'dislikes'), function (Blueprint $table) use ($morphType) {
            $userIdType = config('likeable.tables.user_id_type', 'id');

            $table->bigIncrements('id');

            if ($morphType == 'uuid') {
                $table->uuidMorphs('dislikeable');
            } elseif ($morphType == 'ulid') {
                $table->ulidMorphs('dislikeable');
            } elseif ($morphType == 'varchar') {
                $table->morphs('dislikeable');
            } else {
                $table->numericMorphs('dislikeable');
            }

            if ($userIdType == 'ulid') {
                $table->ulid('user_id');
            } elseif ($userIdType == 'uuid') {
                $table->uuid('user_id');
            } else {
                $table->unsignedBigInteger('user_id');
            }

            $table->timestamps();
            $table->unique(['dislikeable_id', 'dislikeable_type', 'user_id'], 'dislikes_unique');
        });

        Schema::create(config('likeable.tables.dislike.count_table', 'dislikes_count'), function (Blueprint $table) use ($morphType) {
            $table->bigIncrements('id');
            if ($morphType == 'uuid') {
                $table->uuidMorphs('dislikeable');
            } elseif ($morphType == 'ulid') {
                $table->ulidMorphs('dislikeable');
            } elseif ($morphType == 'varchar') {
                $table->morphs('dislikeable');
            } else {
                $table->numericMorphs('dislikeable');
            }
            $table->unsignedBigInteger('count')->default(0);
            $table->unique(['dislikeable_id', 'dislikeable_type'], 'dislike_history_unique');
        });
    }

    public function down()
    {
        Schema::drop(config('likeable.tables.dislike.entity_table', 'dislikes'));
        Schema::drop(config('likeable.tables.dislike.count_table', 'dislikes_count'));
    }
};
