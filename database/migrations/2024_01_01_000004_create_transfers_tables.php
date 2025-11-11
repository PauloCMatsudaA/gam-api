<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_a_id')->constrained('users');
            $table->foreignId('user_b_id')->constrained('users');
            $table->foreignId('initiated_by_user_id')->constrained('users');
            $table->decimal('total_value_a', 15, 2);
            $table->decimal('total_value_b', 15, 2);
            $table->timestamps();
        });

        Schema::create('transfer_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transfer_id')->constrained('transfers')->cascadeOnDelete();
            $table->foreignId('asset_id')->constrained('assets');
            $table->foreignId('from_user_id')->constrained('users');
            $table->foreignId('to_user_id')->constrained('users');
            $table->decimal('book_value_snapshot', 15, 2);
            $table->timestamps();
            $table->unique(['transfer_id','asset_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('transfer_assets');
        Schema::dropIfExists('transfers');
    }
};

