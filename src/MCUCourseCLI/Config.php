<?php

namespace MCUCourseCLI;

class Config {

  protected $workPath = null;

  protected $config = array();

  public function __construct($workPath = null)
  {
    $this->workPath = getcwd();

    if($workPath && file_exists($workPath)) {
      $this->workPath = $workPath;
    }

    $this->parseConfigFile();
  }

  public function getAll()
  {
    return $this->config;
  }

  public function get($key)
  {
    return $this->config[$key];
  }

  public function getVersion()
  {
    return $this->config['VERSION'];
  }

  public function getWorkPath()
  {
    return $this->workPath;
  }

  private function parseConfigFile()
  {
    $defaultConfig = parse_ini_file(__DIR__ . '/../../config.ini');

    $userConfigFile = $this->workPath . '/.mcuConfig';
    $userConfig = array();
    if(file_exists($userConfigFile)) {
      $userConfig = parse_ini_file($userConfigFile);
    }

    $this->config = array_merge($defaultConfig, $userConfig);
    $this->config['VERSION'] = $defaultConfig['VERSION']; // Prevent user change version
  }

}
