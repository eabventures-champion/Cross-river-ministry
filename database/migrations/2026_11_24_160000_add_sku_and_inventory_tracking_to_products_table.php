<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'sku')) {
                $table->string('sku', 60)->nullable()->unique()->after('slug');
            }
            if (! Schema::hasColumn('products', 'initial_stock')) {
                $table->unsignedInteger('initial_stock')->default(50)->after('stock');
            }
            if (! Schema::hasColumn('products', 'low_stock_threshold')) {
                $table->unsignedInteger('low_stock_threshold')->default(10)->after('initial_stock');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['sku', 'initial_stock', 'low_stock_threshold']);
        });
    }
};
