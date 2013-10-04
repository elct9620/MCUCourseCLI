<?php

use MCUCourseCLI\Migration;

class CreateDepartmentsTable extends Migration
{
  public function up()
  {
    $this->schema()->create('departments', function($table) {
      $table->increments('id');
      $table->string('code');
      $table->string('name');
      $table->timestamps();

      $table->index('name');
      $table->unique('code');
    });
  }

  public function down()
  {
    $this->schema()->drop('departments');
  }
}
