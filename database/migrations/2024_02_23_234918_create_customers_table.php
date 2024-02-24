<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 64)->nullable(false);
            $table->string('middle_name', 64)->nullable();
            $table->string('last_name', 64)->nullable(false);
            $table->enum('sex', ['M', 'F'])->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('place_of_birth', 64)->nullable();
            $table->string('nationality', 3)->nullable(false);
            $table->string('country_of_origin', 3)->nullable(false);
            $table->string('address_line_1', 64)->nullable();
            $table->string('address_line_2', 64)->nullable();
            $table->string('phone', 32)->nullable();
            $table->string('email', 64)->nullable();
            $table->bigInteger('created_at_branch')->unsigned()->index()->nullable(false);
            $table->foreign('created_at_branch')->references('id')->on('bank_branches')->onDelete('restrict')->onUpdate('cascade');
            $table->bigInteger('created_by')->unsigned()->index()->nullable(false);
            $table->foreign('created_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
            $table->bigInteger('lastly_updated_by')->unsigned()->index()->nullable();
            $table->foreign('lastly_updated_by')->references('id')->on('users')->onDelete('restrict')->onUpdate('cascade');
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
        Schema::dropIfExists('customers');
    }
}
