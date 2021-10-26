<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title'); //컬럼추가\

            $table->mediumText('content'); //컬럼추가
            $table->string('image')->nullable(); //컬럼추가//널허용
            $table->foreignID('user_id') //참조하는것//관례적으로 user_id를 하면 user테이블에id라는 속성을 참조한다
                ->constrained()
                ->onDelete('cascade');
            // $table->foreign('user_id')->references('id')->on('user')->onDelete('set null');

            $table->timestamps(); //CREATEDAT 나머지도 자동생성
        });
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
