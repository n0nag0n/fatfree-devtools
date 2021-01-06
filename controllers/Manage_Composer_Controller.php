<?php
declare(strict_types=1);

namespace n0nag0n;
use \Nettools\ComposerInterface\ComposerInterface;
use stdClass;

class Manage_Composer_Controller extends Base_Controller {

	public function indexAction(\Base $fw): void {
		require(__DIR__.'/../vendor/net-tools/composer-interface/src/autoload.php');
		$PROJECT = array_pop(explode(DIRECTORY_SEPARATOR, rtrim($fw->PROJECT_BASE_DIR, DIRECTORY_SEPARATOR)));
		$root = rtrim($fw->PROJECT_BASE_DIR, '/');
		try
		{
			// create config object and set composer home (here, the parent folder of document root, so that other composer projects may benefit from global caching)
			$raw_config = new stdClass;
			$raw_config->composer_phpbin = PHP_BINARY;
			$config = new \Nettools\ComposerInterface\Config($raw_config);
			$composer_paths = [
				'/usr/local/bin/',
				'/usr/bin/',
				'~/',
				$fw->PROJECT_BASE_DIR,
			];
			$composer_bin_location = '';
			foreach([ 'composer', 'composer.phar' ] as $composer_app_name) {
				foreach($composer_paths as $path) {
					if(file_exists($path.$composer_app_name)) {
						$composer_bin_location = $path.$composer_app_name;
						break 2;
					}
				}
			}
			$config->composer_bin_location = $composer_bin_location;
			$config->composer_home = rtrim($fw->PROJECT_BASE_DIR, '/');

			// create interface and set the composer project to be in folder PROJECT
			$composer = new \ComposerInterfaceAdapter($config, $root);
			
			// global commands (not relative to a package or repository)
			if ( $_REQUEST['composer'] )
				$ret = $composer->{$_REQUEST['composer']}();


			// package commands
			else if ( $_REQUEST['package_cmd'] && $_REQUEST['package'] )
				$ret = $composer->{'package_' . $_REQUEST['package_cmd']}($_REQUEST['package']);

			
			// repositories commands
			else if ( $_REQUEST['repository_cmd'] && $_REQUEST['url'] )
				switch ( $_REQUEST['repository_cmd'] )
				{
					case 'add' : 
						if ( $_REQUEST['type'] )
							$ret = $composer->repository_add($_REQUEST['type'], $_REQUEST['url']);
						break;
						
					case 'remove' : 
						$ret = $composer->repository_remove($_REQUEST['url']);
						break;
				}
			
			
			// user command (not supported by this library)
			else if ( $_REQUEST['cmd'] )
				$ret = $composer->command($_REQUEST['cmd']);
		}
		catch(\Throwable $e)
		{
			$ret = $e->getMessage() . "\n---\nTrace : " . $e->getTraceAsString();
		}

		if(file_exists($root . '/composer.json')) {
			$composer_file_contents = file_get_contents($root . '/composer.json');
		} else {
			$composer_file_contents = "No composer.json file detected; you MUST install composer by hitting the SETUP link below.>";
		}


		$params = [
			'command_return' => $ret,
			'composer_file_contents' => $composer_file_contents,
			'composer_file_path' => $root.'/composer.json'
		];

		$this->renderHtml('manage_composer/index.htm', $params);
	}
}