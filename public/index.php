<?php
// local and global installation paths
foreach([ __DIR__.'/../vendor/autoload.php', __DIR__.'/../../../autoload.php', getenv('HOME').'/.config/composer/vendor/autoload.php' ] as $path) {
	if(file_exists($path)) {
		require($path);
	}
}

$fw = Base::instance();
$fw->config(__DIR__.'/../config/webtools_config.ini', true);

$fw->set('flash', \Flash::instance());

// Setup Jig DB
$fw->set('DB', new \DB\Jig($fw->PROJECT_DATA_DIR, \DB\Jig::FORMAT_JSON ));

$fw->run();
