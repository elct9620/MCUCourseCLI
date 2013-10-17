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

    $this->client = new Client($this->baseURL);
  }

  public function setPage($page)
  {
    $this->queryPage = $page;
    return $this;
  }

  public function doGet($params = array())
  {
    $this->lastRequest = $this->client->get($this->queryPage, $this->header, $params);
    return $this;
  }

  public function doPost($params = array())
  {
    $this->lastRequest = $this->client->post($this->queryPage, $this->header, $params);
    return $this;
  }

  public function getBody()
  {
    if($this->body) {
      return $this->body;
    }

    if($this->lastRequest == null) {
      return "";
    }

    $response = $this->lastRequest->send();
    $big5Body = $response->getBody();

    $this->body = mb_convert_encoding($big5Body, "UTF-8", "big5");

    return $this->body;
  }

  public function clear()
  {
    $this->body = null;
  }
}
