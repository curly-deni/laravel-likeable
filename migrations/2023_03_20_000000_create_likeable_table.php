<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('likes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuidMorphs('likeable');
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->unique(['likeable_id', 'likeable_type', 'user_id'], 'likes_unique');
        });

        Schema::create('like_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->uuidMorphs('likeable');
            $table->unsignedBigInteger('count')->default(0);
            $table->unique(['likeable_id', 'likeable_type'], 'like_history_unique');
        });
    }

    public function down()
    {
        Schema::drop('likes');
        Schema::drop('like_history');
    }
};