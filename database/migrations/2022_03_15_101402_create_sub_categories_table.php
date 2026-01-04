<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('main_category_id');
            $table->string('sub_category', 60)->index()->comment('サブカテゴリー名');
            $table->timestamps(); // created_at と updated_at を自動で追加

            // 外部キー制約の追加（明示的に定義）
            $table->foreign('main_category_id')
              ->references('id')
              ->on('main_categories')
              ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // テーブル削除前に外部キー制約を削除する
    Schema::table('sub_categories', function (Blueprint $table) {
        $table->dropForeign(['main_category_id']);
    });
        Schema::dropIfExists('sub_categories');
    }
}
