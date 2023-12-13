<?php

declare(strict_types=1);

namespace n0nag0n;

use CLI;

class Devtools_Controller {
	public function beforeroute(\Base $fw) {
		ob_end_clean();
	}

	public function serve(\Base $fw, array $args = []): void {

		$project_dir = getenv('FATFREE_DEVTOOLS_PROJECT_BASE_DIR');

		if(isset($fw->GET['help'])) {
			CLI::info('Starts up your project from the public/ directory.');
			CLI::info('Usage: fatfree serve [--port=8080[--host=localhost]]');
			return;
		}

		$dir = realpath($project_dir.'/public/');
		$port = $fw->GET['port'] ?? ($fw->GET['p'] ?? 8080);
		$host = $fw->GET['host'] ?? ($fw->GET['h'] ?? 'localhost');
		$port = intval($port);

		if($port === 0) {
			die('Please choose a valid port');
		}

		CLI::info("PHP Webserver Started for Fat-Free DevTools. Navigate to http://{$host}:{$port} to view your project. Press Ctrl+C to stop it!");
		system('php -S '.$host.':'.$port.' -t '.$dir);
	}

	public function adminServe(\Base $fw, array $args = []): void {
		$dir = realpath(__DIR__.'/../public/');

		if(isset($fw->GET['help'])) {
			CLI::info('Starts up your project from the public/ directory.');
			CLI::info('Usage: fatfree serve admin [--port=8081[--host=localhost]]');
			return;
		}

		$port = $fw->GET['port'] ?? ($fw->GET['p'] ?? 8081);
		$host = $fw->GET['host'] ?? ($fw->GET['h'] ?? 'localhost');
		$port = intval($port);

		if($port === 0) {
			die('Please choose a valid port');
		}

		CLI::info("PHP Webserver Started for Fat-Free DevTools.\nNavigate to http://{$host}:{$port} to view the DevTools admin console.\n\nPress Ctrl+C to stop it!");
		system('php -S '.$host.':'.$port.' -t '.$dir);
	}

	public function help(\Base $fw) {
		CLI::out('Fat-Free DevTools v-'.DEVTOOLS_VERSION.' Basic Usage: fatfree-devtools [action] [--flags]');
	}

	public function version(\Base $fw) {
		CLI::out('Fat-Free DevTools v-'.DEVTOOLS_VERSION);
	}

	public function cacheClear(\Base $fw) {
		$fw->config(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'webtools_config.ini', true);
		$fw->set('DB', new \DB\Jig($fw->PROJECT_DATA_DIR, \DB\Jig::FORMAT_JSON ));

		$Project_Config = new Project_Config($fw->DB);
		$Project_Config->load();
		$temp_dir = $fw->PROJECT_BASE_DIR.$Project_Config->temp;
		CLI::deleteDirectoryAndFiles($temp_dir);
		mkdir($temp_dir);
		CLI::out('Temp Cache Cleared Successfully');
	}
}
