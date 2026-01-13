<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('position_applied');
            $table->foreignId('station_school_id')->nullable()->constrained('schools')->nullOnDelete();
            $table->string('current_position')->nullable();
            $table->string('item_number')->nullable();
            $table->string('sg_annual_salary')->nullable();
            // store checked levels as JSON array (["elementary"])
            $table->json('levels')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};
