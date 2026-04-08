<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prototype_pages', function (Blueprint $table) {
            $table->ulid('id')->primary();

            $table->foreignUlid('prototype_id')
                ->constrained('prototypes')
                ->cascadeOnDelete();

            $table->ulid('parent_page_id')->nullable();
            $table->integer('deep_index')->default(0);

            $table->string('file_name');
            $table->string('title');
            $table->text('description');
            $table->longText('implementation')->nullable();

            $table->timestamps();
        });

        Schema::table('prototype_pages', function (Blueprint $table) {
            $table->foreign('parent_page_id')
                ->references('id')
                ->on('prototype_pages')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('prototype_pages', function (Blueprint $table) {
            $table->dropForeign(['parent_page_id']);
        });

        Schema::dropIfExists('prototype_pages');
    }
};
