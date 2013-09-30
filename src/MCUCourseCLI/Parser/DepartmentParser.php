<?php

namespace MCUCourseCLI\Parser;

use Symfony\Component\DomCrawler\Crawler;
use MCUCourseCLI\MCUClient;

class DepartmentParser
{
  protected $queryPage = "query_1_up.asp";
  protected $client = null;

  protected $crawler = null;
  protected $body = null;

  protected $departmentData = array();
  protected $yearData = array();

  public function __construct()
  {
    $this->client = new MCUClient($this->queryPage);
    $this->body = $this->client->doGet()->getBody();
    $this->crawler = new Crawler();
    $this->crawler->addHTMLContent($this->body, "UTF-8");
  }

  public function parseDepartment()
  {
    $departmentListElement = $this->crawler->filter("select[name=dept] option");
    $departmentListElement->each($this->insertDepartmentData()); // Should imrpove closure pass method

    return $this->departmentData;
  }

  public function parseYear()
  {
    $yearListElement = $this->crawler->filter("select[name=yr] option");
    $yearListElement->each($this->insertYearData());

    return $this->yearData;
  }

  public function parse()
  {
    return array(
      "departments" => $this->parseDepartment(),
      "years" => $this->parseYear()
    );
  }

  private function insertDepartmentData()
  {
    return function(Crawler $node, $index) {
      $code = $node->extract('value')[0];
      $departmentName = $this->formatDepartmentName($node->text());
      $this->departmentData[(string) $code] = $departmentName;
    };
  }

  private function insertYearData()
  {
    return function(Crawler $node, $index) {
      $year = $node->extract('value')[0];
      $yearName = trim($node->text());
      $this->yearData[$year] = $yearName;
    };
  }

  private function formatDepartmentName($name)
  {
    preg_match("/([0-9]{2} - )(.*)/", $name, $matches);
    return trim($matches[2]);
  }
}
