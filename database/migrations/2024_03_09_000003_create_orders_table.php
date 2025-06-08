<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('uid')->unique();
            $table->foreignId('user_id')->constrained();
            $table->string('number')->unique();
            $table->string('status');
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->text('address');
            $table->decimal('bonus_used', 10, 2)->default(0);
            $table->decimal('bonus_earned', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
} 