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
        Schema::create('replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_reply_to')
                ->constrained('comments');
            $table->foreignId('id_post')
                ->constrained('posts')
                ->onDelete('cascade');
            $table->foreignId('id_user')
                ->nullable()
                ->default(null)
                ->constrained('users');
            $table->string('user_name');
            $table->string('email');
            $table->text('body');
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
        Schema::dropIfExists('replies');
    }
};
