<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubjectUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subject_users', function (Blueprint $table) {
            // $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->comment('ユーザーID');
            $table->unsignedInteger('subject_id')->comment('選択科目ID');
            $table->timestamps(); // created_at と updated_at を自動で追加

            $table->unique(['user_id', 'subject_id']);
            // user_idとsubject_idの組み合わせが、テーブル内で重複することを許さない」というルール

            // 外部キー制約の追加
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subject_users');
    }
}
