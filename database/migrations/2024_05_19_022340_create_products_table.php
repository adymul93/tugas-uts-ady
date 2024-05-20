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
            $table->id()->autoIncrement();
            $table->string('name', 255)->nullable(false);
            $table->text('description');
            $table->integer('price')->nullable(false);
            $table->string('image', 255);
            $table->foreignId("category_id")
                ->nullable(false)
                ->references("id")
                ->on("categories")
                ->cascadeOnDelete();
            $table->date('expired_at')->nullable(false);
            $table->string("modified_by", 255)->nullable(false)->comment('email user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
