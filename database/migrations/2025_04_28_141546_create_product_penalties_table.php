<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Models\Product;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_penalties', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Product::class)
                ->constrained()
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->string('name');
            $table->text('penalty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_penalties');
    }
};
