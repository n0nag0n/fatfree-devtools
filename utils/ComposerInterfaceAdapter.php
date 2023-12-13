<?php

class ComposerInterfaceAdapter extends \Nettools\ComposerInterface\ComposerInterface {
	/**
	 * Execute shell command
	 *
	 * @param string $script Composer command line
	 * @return string Output of shell command
	 */
	public function php_shell($script) {
		// path to php binary
		$phpbin = $this->_config->composer_phpbin;

		// path to composer home ; will be set in the shell environment. We recommend defining a home value
		// in the parent folder of the project, allowing sharing data between projects
		if ( $this->_config->composer_home ) {
			$home = $this->_config->composer_home;
			putenv('HOME=' . $home);
		}

		// navigate to composer project folder and execute command
		return shell_exec("cd {$this->_libc} ; $phpbin $script 2>&1");
	}

	/**
	 * Execute a composer command
	 *
	 * @param string $cmdline Composer command line to execute
	 * @return string Output of shell command
	 */
	public function command($cmdline) {
		return $this->php_shell($this->_config->composer_bin_location." $cmdline");
	}
}
