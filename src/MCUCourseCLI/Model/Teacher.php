<?php

namespace MCUCourseCLI\Model;

class Teacher extends Model {
  protected $fillable = array(
    'teacher',
    'teacher_type',
    'class_room',
    'camps',
    'course_day'
  );

  public static $TEACHER_TYP = array(
    1 => '正課',
    2 => '實習'
  );

  public function course()
  {
    return $this->belongsTo('\MCUCourseCLI\Model\Course');
  }

  public function times()
  {
    return $this->hasMany('\MCUCourseCLI\Model\CourseTime');
  }
}
