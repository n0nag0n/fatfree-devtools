<?php

class ComposerInterfaceAdapter extends \Nettools\ComposerInterface\ComposerInterface {
	
	/**
     * Execute a composer command
     * 
     * @param string $cmdline Composer command line to execute
     * @return string Output of shell command
     */
    public function command($cmdline)
    {
        return $this->php_shell($this->_config->composer_bin_location." $cmdline");
	}
}