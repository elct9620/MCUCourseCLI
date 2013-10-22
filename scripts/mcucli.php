#!/usr/bin/env php
<?php

// Load Composer Autoload
require("phar://mcucli.phar/vendor/autoload.php");

// Starting Console App
use MCUCourseCLI\Console\Application;

// Starting Application
$application = new Application();
$application->run();


