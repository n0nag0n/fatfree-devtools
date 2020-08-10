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

		$dir = realpath($project_dir.'/../public/');
		$port = $fw->GET['port'] ?? ($fw->GET['p'] ?? 8080);
		$port = intval($port);

		if($port === 0) {
			die('Please choose a valid port');
		}

		CLI::info("PHP Webserver Started for Fat-Free Dev Tools. Navigate to http://localhost:{$port} to view your project. Press Ctrl+C to stop it!");
		system('php -S localhost:'.$port.' -t '.$dir);
	}

	public function adminServe(\Base $fw, array $args = []): void {
		$dir = realpath(__DIR__.'/../public/');
		$port = $fw->GET['port'] ?? ($fw->GET['p'] ?? 8081);
		$port = intval($port);

		if($port === 0) {
			die('Please choose a valid port');
		}

		CLI::info("PHP Webserver Started for Fat-Free Dev Tools. Navigate to http://localhost:{$port} to view your admin console for dev tools. Press Ctrl+C to stop it!");
		system('php -S localhost:'.$port.' -t '.$dir);
	}

	public function help(\Base $fw) {
		CLI::out('Fat-Free Dev Tools v-'.DEVTOOLS_VERSION.' Basic Usage: fatfree-devtools [action] [--flags]');
	}

	public function version(\Base $fw) {
		CLI::out('Fat-Free Dev Tools v-'.DEVTOOLS_VERSION);
	}
}