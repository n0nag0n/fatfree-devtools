<?php
require(__DIR__.'/../{{@POST['composer'] }}autoload.php');

$fw = Base::instance();

// Define some constants you may need
define('PROJECT_DEVTOOLS_DATA_DIR', __DIR__.'/../.fatfree-devtools/');

// This will hold the config settings necessary for your routes, variables, connection settings, etc.
$fw->config(__DIR__.'/../{{ @POST['config'] }}main_config.ini', true);

// This file will be all the other plugins, services, tools that you need to put in the Fat-Free class
require(__DIR__.'/../{{@POST['config'] }}{{ @POST['services'] }}');

$fw->run();