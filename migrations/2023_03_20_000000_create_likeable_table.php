<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        $morphType = config('likeable.tables.morph_type', 'biginteger');
        Schema::create(config('likeable.tables.likes', 'likes'), function (Blueprint $table) use ($morphType) {
            $userIdType = config('likeable.tables.user_id_type', 'id');

            $table->bigIncrements('id');

            if ($morphType == 'uuid') {
                $table->uuidMorphs('likeable');
            } elseif ($morphType == 'ulid') {
                $table->ulidMorphs('likeable');
            } elseif ($morphType == 'varchar') {
                $table->morphs('likeable');
            } else {
                $table->numericMorphs('likeable');
            }

            if ($userIdType == 'ulid') {
                $table->ulid('user_id');
            } elseif ($userIdType == 'uuid') {
                $table->uuid('user_id');
            } else {
                $table->unsignedBigInteger('user_id');
            }

            $table->timestamps();
            $table->unique(['likeable_id', 'likeable_type', 'user_id'], 'likes_unique');
        });

        Schema::create(config('likeable.tables.count', 'likes_count'), function (Blueprint $table) use ($morphType) {
            $table->bigIncrements('id');
            if ($morphType == 'uuid') {
                $table->uuidMorphs('likeable');
            } elseif ($morphType == 'ulid') {
                $table->ulidMorphs('likeable');
            } elseif ($morphType == 'varchar') {
                $table->morphs('likeable');
            } else {
                $table->numericMorphs('likeable');
            }
            $table->unsignedBigInteger('count')->default(0);
            $table->unique(['likeable_id', 'likeable_type'], 'like_history_unique');
        });
    }

    public function down()
    {
        Schema::drop(config('likeable.tables.likes', 'likes'));
        Schema::drop(config('likeable.tables.count', 'likes_count'));
    }
};
