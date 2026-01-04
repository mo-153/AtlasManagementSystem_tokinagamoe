<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostSubCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_sub_categories', function (Blueprint $table) {
        // post_id
        $table->foreignId('post_id')
              ->constrained('posts')
              ->onDelete('cascade');

        // sub_category_id
        $table->foreignId('sub_category_id')
              ->constrained('sub_categories')
              ->onDelete('cascade');

        // 複合主キーの定義
        $table->primary(['post_id','sub_category_id']);
    });    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('post_sub_categories', function (Blueprint $table) {
        $table->dropForeign(['post_id']);
        $table->dropForeign(['sub_category_id']);
    });
         Schema::dropIfExists('post_sub_categories');
  }
}
