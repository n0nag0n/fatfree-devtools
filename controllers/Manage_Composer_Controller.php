<?php

declare(strict_types=1);

namespace n0nag0n;

use stdClass;

class Manage_Composer_Controller extends Base_Controller {
	public function indexAction(\Base $fw): void {
		foreach([
			__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR,
			__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR,
			getenv('HOME').DIRECTORY_SEPARATOR.'.config'.DIRECTORY_SEPARATOR.'composer'.DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR,
		] as $dir_path) {
			$final_path = $dir_path.'net-tools'.DIRECTORY_SEPARATOR.'composer-interface'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'autoload.php';
			if(file_exists($final_path) === true) {
				require($final_path);
				break;
			}
		}

		$root = rtrim($fw->PROJECT_BASE_DIR, DIRECTORY_SEPARATOR);
		$ret = '';
		try
		{
			$raw_config = new stdClass;
			$raw_config->composer_phpbin = PHP_BINARY;
			$config = new \Nettools\ComposerInterface\Config($raw_config);
			$composer_paths = [
				'/usr/local/bin/',
				'/usr/bin/',
				'~/',
				$fw->PROJECT_BASE_DIR,
				'.',
				'C:\ProgramData\ComposerSetup\bin'
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

			$composer = new \ComposerInterfaceAdapter($config, $root);

			error_reporting(0);
			if ( !empty($_REQUEST['composer']) ) {
				$action = InputValidator::assertAllowlisted(
					(string)$_REQUEST['composer'],
					InputValidator::COMPOSER_ACTIONS,
					'composer action'
				);
				$ret = $composer->{$action}();
			}
			else if ( !empty($_REQUEST['package_cmd']) && !empty($_REQUEST['package']) ) {
				$package_cmd = InputValidator::assertAllowlisted(
					(string)$_REQUEST['package_cmd'],
					InputValidator::PACKAGE_COMMANDS,
					'package command'
				);
				$package = InputValidator::validatePackageName((string)$_REQUEST['package']);
				$ret = $composer->{'package_'.$package_cmd}($package);
			}
			else if ( !empty($_REQUEST['repository_cmd']) && !empty($_REQUEST['url']) ) {
				$repository_cmd = InputValidator::assertAllowlisted(
					(string)$_REQUEST['repository_cmd'],
					InputValidator::REPOSITORY_COMMANDS,
					'repository command'
				);
				$url = InputValidator::validateUrl((string)$_REQUEST['url']);
				switch ( $repository_cmd ) {
					case 'add' :
						if ( !empty($_REQUEST['type']) ) {
							$type = InputValidator::assertAllowlisted(
								(string)$_REQUEST['type'],
								InputValidator::REPOSITORY_TYPES,
								'repository type'
							);
							$ret = $composer->repository_add($type, $url);
						}
						break;

					case 'remove' :
						$ret = $composer->repository_remove($url);
						break;
				}
			}
			else if ( !empty($_REQUEST['cmd']) ) {
				$ret = $composer->command((string)$_REQUEST['cmd']);
			}
		}
		catch(\Throwable $e) {
			if($fw->CLI) {
				$ret = $e->getMessage() . "\n---\nTrace : " . $e->getTraceAsString();
			} else {
				$ret = $e->getMessage();
			}
		}

		error_reporting(E_ALL);

		if(file_exists($root . DIRECTORY_SEPARATOR . 'composer.json')) {
			$composer_file_contents = file_get_contents($root . DIRECTORY_SEPARATOR . 'composer.json');
		} else {
			$composer_file_contents = "No composer.json file detected; you MUST install composer by hitting the SETUP link below.>";
		}

		$params = [
			'command_return' => $ret,
			'composer_file_contents' => $composer_file_contents,
			'composer_file_path' => $root.DIRECTORY_SEPARATOR.'composer.json'
		];

		$this->renderHtml('manage_composer'.DIRECTORY_SEPARATOR.'index.htm', $params);
	}
}