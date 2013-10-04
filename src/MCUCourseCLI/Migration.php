<?php

namespace MCUCourseCLI;

use Illuminate\Database\Migrations\Migration as BaseMigration;

abstract class Migration extends BaseMigration{

  private $database = null;

  protected function schema()
  {
    $connection = $this->getConnection();
    return $connection->getSchemaBuilder();
  }

  public function getConnection()
  {
    if($this->database) {
      return $this->database->getConnection();
    }

    $this->database = Database::getInstance();
    return $this->database->getConnection();
  }

}
