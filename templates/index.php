<?php

DEFINE("PROJECT_ROOT_DIR", __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR);
require(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'{{ @cnf_config }}bootstrap.php');

$fw->run();
