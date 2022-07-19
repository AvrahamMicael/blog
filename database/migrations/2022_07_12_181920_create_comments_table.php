<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_post')
                ->constrained('posts')
                ->onDelete('cascade');
            $table->foreignId('id_user')
                ->nullable()
                ->default(null)
                ->constrained('users');
            $table->bigInteger('id_reply_to')->unsigned()->nullable()->default(null);
            $table->string('user_name');
            $table->string('email');
            $table->text('body')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
};
