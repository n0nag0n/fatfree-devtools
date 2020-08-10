<?php
require(__DIR__.'/../vendor/autoload.php');

$fw = Base::instance();
$fw->config(__DIR__.'/../config/webtools_config.ini', true);

$fw->set('flash', \Flash::instance());

// Setup Jig DB
$fw->set('DB', new \DB\Jig($fw->PROJECT_DATA_DIR, \DB\Jig::FORMAT_JSON ));

$fw->run();