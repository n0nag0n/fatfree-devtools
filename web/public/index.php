<?php
require(__DIR__.'/../vendor/autoload.php');

//define("PROJECT_BASE_DIR", getcwd());

$fw = Base::instance();
$fw->config(__DIR__.'/../app/config/config.ini', true);

$fw->run();