<?php
DEFINE("PROJECT_ROOT_DIR", __DIR__.'/../');
require(__DIR__.'/../{{ @cnf_config }}bootstrap.php');

$fw->run();