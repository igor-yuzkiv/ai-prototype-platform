<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prototypes', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');

            $table->text('initial_requirements');
            $table->text('formatted_requirements')->nullable();

            $table->text('project_overview')->nullable();
            $table->text('global_rules')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prototypes');
    }
};
