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
        Schema::create('distributions', function (Blueprint $table) {
            $table->id();
            $table->string('dist_number', 50)->unique();
            $table->foreignId('warehouse_id')->constrained()->restrictOnDelete();  // gudang asal
            $table->foreignId('issued_by')->constrained('users')->restrictOnDelete();
            $table->string('destination');          // nama divisi/tujuan
            $table->string('recipient')->nullable(); // nama penerima
            $table->date('dist_date');
            $table->enum('status', ['draft', 'issued', 'cancelled'])->default('draft');
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distributions');
    }
};
