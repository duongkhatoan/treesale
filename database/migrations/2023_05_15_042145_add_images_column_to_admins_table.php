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
        if (!Schema::hasColumn('admins', 'images')) {
            Schema::table('admins', function (Blueprint $table) {
                $table->string('images')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('admins', 'images')) {
            Schema::table('admins', function (Blueprint $table) {
                $table->dropColumn('images');
            });
        }
    }
};
