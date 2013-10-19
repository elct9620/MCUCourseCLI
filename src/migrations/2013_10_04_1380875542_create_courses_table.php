<?php

use MCUCourseCLI\Migration;

class CreateCoursesTable extends Migration {
  public function up() {
    $this->schema()->create('courses', function($table)
    {
      $table->increments('id');
      $table->integer('system');
      $table->string('course_code');
      $table->string('course_name');
      $table->string('class_code')->unique();
      $table->integer('max_people');
      $table->integer('selected_people');
      $table->integer('year');
      $table->integer('select_type');
      $table->integer('credit');
      $table->integer('class_type');
      $table->integer('semester');
      $table->timestamps();
    });
  }

  public function down() {
    $this->schema()->drop('courses');
  }
}
