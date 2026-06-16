<?php

class ComposerInterfaceAdapter extends \Nettools\ComposerInterface\ComposerInterface {
	/**
	 * Execute shell command
	 *
	 * @param string $script Composer command line
	 * @return string Output of shell command
	 */
	public function php_shell($script) {
		$phpbin = $this->_config->composer_phpbin;

		if ( $this->_config->composer_home ) {
			$home = $this->_config->composer_home;
			putenv('HOME=' . $home);
		}

		$libc = escapeshellarg($this->_libc);
		$phpbin_arg = escapeshellarg($phpbin);
		$script_arg = escapeshellarg($script);

		return shell_exec("cd {$libc} && {$phpbin_arg} {$script_arg} 2>&1");
	}

	/**
	 * Execute a composer command
	 *
	 * @param string $cmdline Composer command line to execute
	 * @return string Output of shell command
	 */
	public function command($cmdline) {
		$cmdline = InputValidator::validateComposerCmd($cmdline);
		return $this->php_shell($this->_config->composer_bin_location.' '.$cmdline);
	}
}