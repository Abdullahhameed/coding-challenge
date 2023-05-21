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
        Schema::create('network_connections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id')->unsigned()->index();
            $table->unsignedBigInteger('receiver_id')->unsigned()->index();
            $table->unsignedBigInteger('status')->comment('[1 => Requested, 2 => Accepted]')->unsigned()->index();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('status')->references('id')->on('connection_types')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('network_connections');
    }
};
