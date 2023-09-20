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
        Schema::create('follows', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('following_user_id')->constrained('users');
            $table->unique(['user_id', 'following_user_id']); //a user can only follow another user once
            $table->boolean('accepted')->default(false); // a following need accept or not but defaul accepted
            $table->boolean('blocked')->default(false);
            $table->boolean('muted')->default(false);
            $table->boolean('following')->default(false);
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
        Schema::dropIfExists('follows');
    }
};
