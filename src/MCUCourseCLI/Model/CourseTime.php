<?php

namespace MCUCourseCLI\Model;

class CourseTime extends Model {
  protected $fillable = array('time');

  public function teacher()
  {
    return $this->belongsTo('\MCUCourseCLI\Model\Teacher');
  }
}
