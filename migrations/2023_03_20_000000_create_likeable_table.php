<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create(config('likeable.tables.likes', 'likes'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuidMorphs('likeable');

            $userIdType = config('likeable.tables.user_id_type', 'id');

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

        Schema::create(config('likeable.tables.count', 'likes_count'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuidMorphs('likeable');
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
