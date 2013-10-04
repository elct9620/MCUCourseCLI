<?php

use MCUCourseCLI\Migration;

class CreateStatusTable extends Migration
{
  public function up()
  {
    $this->schema()->create('status', function($table) {
      $table->increments('id');
      $table->string('type')->unique();
      $table->timestamps();
    });
  }

  public function down()
  {
    $this->schema()->drop('status');
  }
}
