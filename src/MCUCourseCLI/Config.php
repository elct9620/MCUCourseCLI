<?php

namespace MCUCourseCLI;

class Config {

  protected static $instance = null;

  protected $workPath = null;

  protected $config = array();

  private function __construct($workPath = null)
  {
    $this->workPath = getcwd();

    if($workPath && file_exists($workPath)) {
      $this->workPath = $workPath;
    }

    $this->parseConfigFile();
  }

  public static function getInstance($workPath = null)
  {
    if(self::$instance && self::$instance instanceof Config) {
      return self::$instance;
    }

    self::$instance = new Config($workPath);
    return self::$instance;

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
