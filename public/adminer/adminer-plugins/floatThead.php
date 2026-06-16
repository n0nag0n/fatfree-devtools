<?php

class AdminerFloatThead {

	private $pathToJquery;
	private $pathToFloatThead;

	/**
	 * @param string $pathToJquery Path to jquery js library. Can be url, filesystem relative path related to the adminer directory or null (if jquery is provided by another plugin).
	 * @param string $pathToFloatThead Path to floatThead js library. Can be url or filesystem relative path related to the adminer directory.
	 */
	public function __construct($pathToJquery='https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js',
			$pathToFloatThead='https://cdnjs.cloudflare.com/ajax/libs/floatthead/2.0.3/jquery.floatThead.min.js') {
		$this->pathToJquery = $pathToJquery;
		$this->pathToFloatThead = $pathToFloatThead;
	}

	public function head() {
		if ($this->pathToJquery) {
			echo '<script'.Adminer\nonce().' src="'.Adminer\h($this->pathToJquery).'"></script>';
		}
		echo '<script'.Adminer\nonce().' src="'.Adminer\h($this->pathToFloatThead).'"></script>';
		echo '<script'.Adminer\nonce().'>$(document).ready(function() { $(\'#content table\').first().floatThead(); });</script>';
		echo '<style type="text/css">.floatThead-container { overflow: visible !important; }</style>';
	}
}
