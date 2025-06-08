<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('price');
            $table->string('img');
            $table->integer('product_type')->unsigned();
            $table->foreign('product_type')->references('id')->on('categories');
            $table->string('country');
            $table->string('color');
            $table->string('qty');
            $table->string('weight');
            $table->string('obiem');
            $table->string('osnova');
            $table->integer('time');
            $table->string('tempa');
            $table->integer('srok_godnosti');
            $table->text('dop_info');
            $table->string('rashod');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
