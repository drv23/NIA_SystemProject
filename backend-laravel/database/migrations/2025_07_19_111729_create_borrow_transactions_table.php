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
        Schema::create('borrow_transactions', function (Blueprint $table) {
           $table->id();
            $table->unsignedBigInteger('item_id');
$table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
        $table->integer('quantity');
        $table->string('borrowed_by');
        $table->string('status')->default('borrowed');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrow_transactions');
    }
};
