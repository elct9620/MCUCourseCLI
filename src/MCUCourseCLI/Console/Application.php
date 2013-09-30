<?php

namespace MCUCourseCLI\Console;

use Symfony\Component\Console\Application as BaseApplication;

use MCUCourseCLI\MCUCourseCLI;

class Application extends BaseApplication {

  private static $logo = <<<EOF
 __  __  ____ _   _  ____                           ____ _     ___
|  \/  |/ ___| | | |/ ___|___  _   _ _ __ ___  ___ / ___| |   |_ _|
| |\/| | |   | | | | |   / _ \| | | | '__/ __|/ _ \ |   | |    | |
| |  | | |___| |_| | |__| (_) | |_| | |  \__ \  __/ |___| |___ | |
|_|  |_|\____|\___/ \____\___/ \__,_|_|  |___/\___|\____|_____|___|


EOF;

  public function __construct()
  {
    parent::__construct("MCUCourseCLI Tools", MCUCourseCLI::VERSION);
  }

  public function getHelp()
  {
    return self::$logo . parent::getHelp();
  }
}
