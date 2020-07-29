<?php
declare(strict_types=1);

namespace n0nag0n;
use CLI;

class Cli_Controller {

	public function beforeroute(\Base $fw) {
		ob_end_clean();
	}
	public function serve(\Base $fw, array $args = []): void {
		$dir = realpath(__DIR__.'/../public/');
		CLI::info("PHP Webserver Started for Fat-Free Dev Tools. Press Ctrl+C to stop it!");
		system('php -S localhost:8000 -t '.$dir);
	}

	public function help(\Base $fw) {
		CLI::out('Fat-Free Dev Tools v-'.DEVTOOLS_VERSION.' Basic Usage: fatfree-devtools [action] [--flags]');
	}

	public function version(\Base $fw) {
		CLI::out('Fat-Free Dev Tools v-'.DEVTOOLS_VERSION);
	}
}