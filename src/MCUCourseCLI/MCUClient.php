<?php

namespace MCUCourseCLI;

use Guzzle\Http\Client;

class MCUClient {

  protected $baseURL = "http://www.mcu.edu.tw/student/new-query/sel-query/";
  protected $queryPage = null;
  protected $header = array();

  protected $client = null;
  protected $lastRequest = null;

  protected $body = null;

  public function __construct($page = null)
  {
    if($page) {
      $this->queryPage = $page;
    }

    $this->client = new Client($baseURL);
  }

  public function setPage($page)
  {
    $this->queryPage = $page;
    return $this;
  }

  public function get($params)
  {
    $this->lastRequest = $this->client->get($this->queryPage, $this->header, $params);
    return $this;
  }

  public function post($params)
  {
    $this->lastRequest = $this->client->post($this->queryPage, $this->header, $params);
    return $this;
  }

  public function getBody()
  {
    if($body) {
      return $this->body;
    }

    $response = $this->client->send();
    $big5Body = $response->getBody();

    $this->body = mb_convert_encoding($big5Body, "UTF-8", "big5");

    return $this->body;
  }
}
