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

            $table->string('prototype_id');
            $table->string('parent_page_id')->nullable();
            $table->integer('deep_index')->default(0);
            $table->string('file_name');
            $table->string('title');
            $table->text('description');
            $table->longText('implementation')->nullable();

            $table->foreign('prototype_id')->references('id')->on('prototypes')->onDelete('cascade');
            $table->foreign('parent_page_id')->references('id')->on('prototype_pages')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prototype_pages');
    }
};
