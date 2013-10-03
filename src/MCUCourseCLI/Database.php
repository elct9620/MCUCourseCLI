<?php

namespace MCUCourseCLI;

use Illuminate\Database\Capsule\Manager as Capsule;

class Database {

  private static $instance = null;
  protected $capsule = null;

  private function __construct()
  {
    if(is_null($this->capsule)) {
      $this->capsule = new Capsule;
    }

    $config = Config::getInstance();

    $connection = $config->get('DBConnection');
    $this->capsule->addConnection($connection);

    $this->prepareSQLite($connection); // Auto create sqlite file

    $this->capsule->setAsGlobal();
    $this->capsule->bootEloquent();
  }

  public static function getInstance()
  {
    if(self::$instance && self::$instance instanceof Database) {
      return self::$instance;
    }

    self::$instance = new Database();
    return self::$instance;
  }

  public function getConnection($name = null)
  {
    return $this->capsule->getConnection($name);
  }

  private function prepareSQLite($connection)
  {
    $driver = $connection['driver'];
    $database = $connection['database'];

    if($driver != "sqlite") { // Skip if didn't use SQLite
      return -1;
    }

    if($database == ":memory:") { // Skip if use memory as database
      return -1;
    }

    $config = Config::getInstance();
    $workPath = $config->getWorkPath();
    $databasePath = $workPath . '/' . $database;

    if(file_exists($databasePath)) { // If sqlite file exists, then return
      return -1;
    }

    return file_put_contents($databasePath, ""); // Simple create empty file for sqlite
  }
}
