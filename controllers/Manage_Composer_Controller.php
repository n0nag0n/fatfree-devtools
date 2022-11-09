<?php
declare(strict_types=1);

namespace n0nag0n;
use stdClass;

class Manage_Composer_Controller extends Base_Controller {

	public function indexAction(\Base $fw): void {
		foreach([ __DIR__.'/../vendor/', getenv('HOME').'/.config/composer/vendor/' ] as $dir_path) {
			$final_path = $dir_path.'net-tools/composer-interface/src/autoload.php';
			if(file_exists($final_path)) {
				require($final_path);
				break;
			}
		}

		$root = rtrim($fw->PROJECT_BASE_DIR, '/');
		$ret = '';
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

			// create interface and set the composer project to be in folder PROJECT
			$composer = new \ComposerInterfaceAdapter($config, $root);
			
			error_reporting(0);
			// global commands (not relative to a package or repository)
			if ( !empty($_REQUEST['composer']) ) {
				$ret = $composer->{$_REQUEST['composer']}();
			}

			// package commands
			else if ( !empty($_REQUEST['package_cmd']) && !empty($_REQUEST['package']) ) {
				$ret = $composer->{'package_' . $_REQUEST['package_cmd']}($_REQUEST['package']);
			
			// repositories commands
			} else if ( !empty($_REQUEST['repository_cmd']) && !empty($_REQUEST['url']) ) {
				switch ( $_REQUEST['repository_cmd'] ) {
					case 'add' : 
						if ( !empty($_REQUEST['type']) )
							$ret = $composer->repository_add($_REQUEST['type'], $_REQUEST['url']);
						break;
						
					case 'remove' : 
						$ret = $composer->repository_remove($_REQUEST['url']);
						break;
				}
			
			// user command (not supported by this library)
			} else if ( !empty($_REQUEST['cmd']) ) {
				$ret = $composer->command($_REQUEST['cmd']);
			}
		}
		catch(\Throwable $e) {
			$ret = $e->getMessage() . "\n---\nTrace : " . $e->getTraceAsString();
		}

		error_reporting(E_ALL);

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