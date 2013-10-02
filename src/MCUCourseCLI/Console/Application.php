<?php

namespace MCUCourseCLI\Console;

use Symfony\Component\Console\Application as BaseApplication;

use MCUCourseCLI\Config;
use MCUCourseCLI\MCUCourseCLI;
use MCUCourseCLI\Command;

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
    $config = Config::getInstance();
    parent::__construct("MCUCourseCLI Tools", $config->getVersion());
  }

  public function getHelp()
  {
    return self::$logo . parent::getHelp();
  }

  protected function getDefaultCommands()
  {
    $commands = parent::getDefaultCommands();
    $commands[] = new Command\InitCommand();

    return $commands;
  }
}
