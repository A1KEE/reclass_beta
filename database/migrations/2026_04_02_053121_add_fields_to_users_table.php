<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('users', function (Blueprint $table) {

        $table->foreignId('application_id')
              ->nullable()
              ->constrained()
              ->nullOnDelete();

        $table->boolean('must_change_password')
              ->default(0);
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {

        $table->dropForeign(['application_id']);
        $table->dropColumn(['application_id', 'must_change_password']);
    });
}
}
