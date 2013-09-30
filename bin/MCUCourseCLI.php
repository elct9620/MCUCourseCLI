#!/bin/env php
<?php

if(PHP_SAPI != "cli") {
}

// Load Composer Autoload
require(__DIR__ . "/../vendor/autoload.php");

// Starting Console App
use MCUCourseCLI\Console\Application;

// Starting Application
$application = new Application();
$application->run();
