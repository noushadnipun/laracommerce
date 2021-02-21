<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAddressBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_address_books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->unsigned()
                  ->references('id')->on('users')->onDelete('cascade');
            $table->string('name');
            $table->string('address'); 
            $table->string('thana');
            $table->string('postal_code');
            $table->string('city');
            $table->string('country');
            $table->string('phone');
            $table->integer('set_default')->default('1');
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
        Schema::dropIfExists('user_address_books');
    }
}
