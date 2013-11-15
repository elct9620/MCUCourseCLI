<?php

namespace MCUCourseCLI\Parser;

use Closure;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use MCUCourseCLI\MCUClient;

class CourseParser {

  protected $queryCodes = array(
    'normal' => 0,
    'require' => 4
  );
  protected $queryCode = 0;
  protected $queryPage = "query_7_1.asp";
  protected $client = null;

  protected $crawler = null;
  protected $body = null;

  protected $courseElement = null;
  protected $courseData = array();
  private $lastParsedData = array();

  protected $rowFunction = null;
  protected $columnFunction = null;

  // Column Data
  protected $systemMap = array( // 制別
    '大學日間部' => 1,
    '碩士班' => 2,
    '海青班' => 3,
    '研碩士班' => 4,
    '博士班' => 5,
    '碩士專班' => 6
  );

  protected $campsMap = array(
    '台北' => 1,
    '桃園' => 2,
    '基河' => 3
  );

  protected $selectTypeMap = array(
    '通識' => 1,
    '必修' => 2,
    '選修' => 3,
    '教育' => 4
  );

  protected $semesterMap = array(
    '上學期' => 1,
    '下學期' => 2,
    '全學年' => 3
  );

  protected $teacherTypeMap = array(
    '正課' => 1,
    '實習' => 2
  );

  public function __construct(Closure $rowFunction, Closure $columnFunction, $queryType = "normal")
  {
    $this->client = new MCUClient($this->queryPage);
    $this->setQueryCode($queryType);
    $this->getData();

    $this->rowFunction = $rowFunction->bindTo($this, $this); // Notice: make closure function $this refere to this object
    $this->columnFunction = $columnFunction->bindTo($this, $this);
  }

  public function setQueryCode($type = "normal")
  {
    if(isset($this->queryCodes[$type])) {
      $this->queryCode = $this->queryCodes[$type];
      return $this->queryCode;
    }

    $this->queryCode = $this->queryCodes['normal'];
    return $this->queryCode; // normal type should exists
  }

  public function count()
  {
    $this->analyticDOM();
    return $this->courseElement->count() - 1; // Skip column header
  }

  public function getData()
  {
    $this->client->clear();
    $this->body = $this->client->doPost(array('mk' => $this->queryCode))->getBody();
    if($this->crawler == null) {
      $this->crawler = new Crawler();
    }
    $this->crawler->clear(); // Remove Old Nodes
    $this->crawler->addHTMLContent($this->body, "UTF-8");
    $this->courseElement = null;
    $this->courseData = array();
  }

  public function parse()
  {
    $this->analyticDOM();
    $this->courseElement->each($this->rowFunction);
    return $this->courseData;
  }

  public function analyticDOM()
  {
    if(is_null($this->courseElement)) {
      $this->courseElement = $this->crawler->filter("body > table tr");
    }
  }

  // Column Parser

  protected function getSystemCode($system) {
    if(isset($this->systemMap[$system])) {
      return $this->systemMap[$system];
    }
    return 0; // Unknow
  }

  protected function splitCourseCodeAndName($courseName) {
     $courseData = explode(' ', trim($courseName)); // Should return array has 1 to 2 element
     return array(
       'course_code' => substr($courseData[0], 2), // Remove chinese space can't trim
       'course_name' => trim($courseData[1])
     );
  }

  protected function getClassCode($classData) {
    $classData = explode(' ', trim($classData));
    return substr($classData[0], 2); // Remove chinese space can't trim
  }

  protected function getPeopleStatus($peopleStatus) {
    return explode('／', trim($peopleStatus)); // Should return array has 2 element (max / selected)
  }

  protected function getTeacher(Crawler $teacherNode) {
    $teachers = array();
    $teacherRawData = $teacherNode->filter('a font')->html();
    $teacherElements = explode('<br>', $teacherRawData);
    foreach($teacherElements as $teacher) {
      $teacher = explode(':', $teacher);
      if(isset($this->teacherTypeMap[$teacher[0]])) {
        $teacherData['type'] = $this->teacherTypeMap[$teacher[0]];
      } else {
        $teacherData['type'] = 0;
      }
      $teacherData['name'] = trim($teacher[1]);
      $teachers[] = $teacherData;
    }
    return $teachers;
  }

  protected function getTime($timeData) {
    $timeDatas = array();

    $weekPattern = "/星期\s([0-9]{1})\s:([\s0-9]+)\s節/";
    $timePattern = "/\s([0-9]{1,2})\s/";
    $weekMatches = array();
    preg_match_all($weekPattern, $timeData, $weekMatches);

    foreach($weekMatches[2] as $index => $timeData) {
      $timeMatches = array();
      preg_match_all($timePattern, $timeData, $timeMatches);
      $timeDatas[] = array(
        'course_day' => $weekMatches[1][$index],
        'time' => $timeMatches[1]
      );
    }

    return $timeDatas;
  }

  protected function getRoomAndCamps($nodeData) {
    $pattern = "/([a-zA-Z0-9]+)【([^】]+)】/";
    $matches = array();
    preg_match_all($pattern, $nodeData, $matches);

    $roomAndCamps = array();

    foreach($matches[0] as $index => $data) {
      $camps = $this->campsMap[trim($matches[2][$index])];
      $roomAndCamps['class_room'][] = $matches[1][$index];
      $roomAndCamps['camps'][] =  $camps;
    }

    return $roomAndCamps;
  }

  protected function getSelectType($selectData) {
    return $this->selectTypeMap[trim($selectData)];
  }

  protected function getSemester($semester) {
    return $this->semesterMap[trim($semester)];
  }
}
