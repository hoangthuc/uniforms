<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('posts')) {
            Schema::create('posts', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('user_id');
                $table->integer('post_category')->nullable();
                $table->string('slug')->nullable();
                $table->string('post_title');
                $table->text('post_content');
                $table->string('post_type');
                $table->string('post_image')->nullable();
                $table->datetime('post_date');
                $table->string('post_notes')->nullable();
                $table->string('time_spent')->nullable();
                $table->integer('status')->nullable();
                $table->timestamp('ts')->nullable();
                $table->rememberToken()->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
