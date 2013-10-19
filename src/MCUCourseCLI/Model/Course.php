<?php

namespace MCUCourseCLI\Model;

class Course extends Model {
  protected $fillable = array(
    'system',
    'course_code',
    'course_name',
    'class_code',
    'max_people',
    'selected_people',
    'teacher',
    'time',
    'year',
    'class_room',
    'camps',
    'select_type',
    'credit',
    'semester'
  );

  public static $SYSTEM = array(
    1 =>'大學日間部',
    2 => '碩士班',
    3 => '海青班',
    4 => '研碩士班',
    5 => '博士班',
    6 => '碩士專班'
  );

  public static $CAMPS = array(
    1 => '台北',
    2 => '桃園',
    3 => '基河'
  );

  public static $SELECT_TYPE = array(
    1 => '通識',
    2 => '必修',
    3 => '選修',
    4 => '教育'
  );

  public static $SEMESTER = array(
    1 => '上學期',
    2 => '下學期',
    3 => '全學年'
  );

  public function teachers() {
    return $this->hasMany('\MCUCourseCLI\Model\Teacher'); // This seems bad, fix later ...
  }
}
