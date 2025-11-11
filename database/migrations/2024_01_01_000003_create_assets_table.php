<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('book_value', 15, 2);
            $table->decimal('distribution_lat', 10, 7);
            $table->decimal('distribution_lng', 10, 7);
            $table->enum('status', ['NOVO','EM_USO','MANUTENCAO'])->default('NOVO');
            $table->foreignId('user_id')->constrained('users');
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('assets');
    }
};

