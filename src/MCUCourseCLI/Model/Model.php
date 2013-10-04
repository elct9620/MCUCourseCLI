<?php

namespace MCUCourseCLI\Model;

use Illuminate\Database\Eloquent\Model as BaseModel;

use MCUCourseCLI\Database;

class Model extends BaseModel {
  public function __construct(array $attributes = array())
  {
    parent::__construct($attributes);

    $this->setConnectionResolver(Database::getInstance()->getResolver());
  }
}
