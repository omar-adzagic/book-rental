<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookRentalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_rental', function (Blueprint $table) {
            $table->id();
            $table->date('from');
            $table->date('to');

            $table->unsignedBigInteger('tenant_id');
            $table->unsignedBigInteger('book_id');
            $table->timestamps();

            $table->foreign('tenant_id')->references('id')->on('users');
            $table->foreign('book_id')->references('id')->on('books');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('book_rental');
    }
}
