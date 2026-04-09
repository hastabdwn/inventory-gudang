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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->restrictOnDelete();
            $table->foreignId('warehouse_id')->constrained()->restrictOnDelete();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->enum('type', ['in', 'out', 'transfer_in', 'transfer_out', 'retur', 'adjustment']);
            $table->integer('quantity');          // selalu positif
            $table->integer('stock_before');      // snapshot stok sebelum
            $table->integer('stock_after');       // snapshot stok sesudah
            $table->string('reference_type')->nullable();  // morphable: PO, distribution, retur, dll
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('moved_at');
            $table->timestamps();

            $table->index(['item_id', 'warehouse_id']);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
