<?php

use MCUCourseCLI\Migration;

class CreateCourseTimeTable extends Migration
{
  public function up()
  {
    $this->schema()->create('course_times', function($table) {
      $table->increments('id');
      $table->integer('time');
      $table->integer('teacher_id');
      $table->timestamps();
    });
  }

  public function down()
  {
    $this->schema()->drop('course_times');
  }
}
