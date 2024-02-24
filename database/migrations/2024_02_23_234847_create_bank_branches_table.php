<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_branches', function (Blueprint $table) {
            $table->id();
            $table->string('name', 128)->nullable(false);
            $table->string('address_line_1', 64)->nullable();
            $table->string('address_line_2', 64)->nullable();
            $table->string('state', 32)->nullable();
            $table->string('country', 3)->nullable();
            $table->bigInteger('bank_id')->unsigned()->index()->nullable(false);
            $table->foreign('bank_id')->references('id')->on('banks')->onDelete('restrict')->onUpdate('cascade');
            $table->timestamps();

            $table->unique(['name', 'bank_id'], 'bank_branches_name_branch_id_unique_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_branches');
    }
}
