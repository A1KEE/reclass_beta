<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTrainingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::create('trainings', function (Blueprint $table) {
        $table->id();
        $table->foreignId('applicant_id')->constrained()->onDelete('cascade');
        $table->string('training_name');
        $table->date('training_date');
        $table->string('file_path');
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('trainings');
}
}
