<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coaching_services', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('icon', 50)->default('cross');
            $table->string('tagline')->nullable();
            $table->text('excerpt')->nullable();
            $table->longText('description')->nullable();
            $table->json('benefits')->nullable();
            $table->json('audience')->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('coaches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('role')->nullable();
            $table->text('bio')->nullable();
            $table->string('photo')->nullable();
            $table->string('email')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Consulting services, speaking topics and workshops share one table, split by `type`.
        Schema::create('consulting_services', function (Blueprint $table) {
            $table->id();
            $table->string('type', 30)->default('consulting')->index(); // consulting | speaking | workshop
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('icon', 50)->nullable();
            $table->text('excerpt')->nullable();
            $table->longText('description')->nullable();
            $table->json('points')->nullable();
            $table->string('image')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location')->nullable();
            $table->string('category')->nullable();
            $table->string('headline')->nullable();
            $table->text('content');
            $table->string('photo')->nullable();
            $table->boolean('is_featured')->default(false)->index();
            $table->boolean('is_published')->default(true)->index();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('consulting_services');
        Schema::dropIfExists('coaches');
        Schema::dropIfExists('coaching_services');
    }
};
