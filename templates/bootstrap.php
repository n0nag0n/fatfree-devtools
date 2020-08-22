<?php
/*
 * The purpose of this file is to have one central file hold all the requirements
 * to build up this app (index.php, cli or unit-test for example all share the same
 * requirements) 
 */

require(PROJECT_ROOT_DIR.'{{ @cnf_composer }}autoload.php');

$fw = Base::instance();

// Define some constants you may need
define('PROJECT_DEVTOOLS_DATA_DIR', PROJECT_ROOT_DIR.'.fatfree-devtools/');

// This will hold the config settings necessary for your routes, variables, connection settings, etc.
$fw->config(PROJECT_ROOT_DIR.'{{ @cnf_config }}main_config.ini', true);

// This file will be all the other plugins, services, tools that you need to put in the Fat-Free class
require(PROJECT_ROOT_DIR.'{{@cnf_config }}{{ @cnf_services }}');