<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'age')) {
                $table->unsignedSmallInteger('age')->after('name');
            }
            if (!Schema::hasColumn('users', 'current_lat')) {
                $table->decimal('current_lat', 10, 7)->nullable()->after('age');
            }
            if (!Schema::hasColumn('users', 'current_lng')) {
                $table->decimal('current_lng', 10, 7)->nullable()->after('current_lat');
            }
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'age')) $table->dropColumn('age');
            if (Schema::hasColumn('users', 'current_lat')) $table->dropColumn('current_lat');
            if (Schema::hasColumn('users', 'current_lng')) $table->dropColumn('current_lng');
        });
    }
};

