<?php

use MCUCourseCLI\Migration;

class CreateTeacherTable extends Migration
{
  public function up()
  {
    $this->schema()->create('teachers', function($table) {
      $table->increments('id');
      $table->string('teacher');
      $table->integer('teacher_type');
      $table->string('class_room');
      $table->integer('camps');
      $table->integer('course_day');
      $table->integer('course_id');
      $table->timestamps();

      $table->index('teacher');
      $table->index('class_room');
    });
  }

  public function down()
  {
    $this->schema()->drop('teachers');
  }
}
