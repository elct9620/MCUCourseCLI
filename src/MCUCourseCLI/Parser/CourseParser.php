<?php

namespace MCUCourseCLI\Parser;

use Closure;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Console\Helper\ProgressHelper;
use Symfony\Component\Console\Output\OutputInterface;

use MCUCourseCLI\MCUClient;

class CourseParser {

  protected $queryCodes = array(
    'normal' => 0
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

  public function __construct(Closure $rowFunction, Closure $columnFunction, $queryType = "normal")
  {
    $this->setQueryCode($queryType);
    $this->client = new MCUClient($this->queryPage);
    $this->body = $this->client->doPost(array('mk' => $this->queryCode))->getBody();
    $this->crawler = new Crawler();
    $this->crawler->addHTMLContent($this->body, "UTF-8");

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

  public function parse()
  {
    $this->analyticDOM();
    $this->courseElement->each($this->rowFunction);
    return $this->courseData;
  }

  private function analyticDOM()
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
    return explode(' ', trim($courseName)); // Should return array has 1 to 2 element
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
      $teacherData['type'] = $teacher[0];
      $teacherData['name'] = $teacher[1];
      $teachers[] = $teacherData;
    }
    return $teachers;
  }
}
