<?php
/** Remembers and restores scollbar position of side menu
* @author Jiří @NoxArt Petruželka, www.noxart.cz
* @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
*/
class AdminerRestoreMenuScroll {
	protected $script;
	/**
	* @param string text to append before first calendar usage
	*/
	public function __construct()
	{
		$this->script = "<script".nonce().">\n(function(){\nvar executed = false;\nvar saveAndRestore = function() {\nif( executed ) {\nreturn;\n}\n
executed = true;\nvar menu = document.getElementById('menu');\nvar scrolled = localStorage.getItem('_adminerScrolled');\nif( scrolled && scrolled >= 0 ) {\nmenu.scrollTop = scrolled;\n}\n
window.addEventListener('unload', function(){\nlocalStorage.setItem('_adminerScrolled', menu.scrollTop);\n});\n};\ndocument.addEventListener && document.addEventListener('DOMContentLoaded', saveAndRestore);\ndocument.attachEvent && document.attachEvent('onreadystatechange', saveAndRestore);\n})();\n</script>";
	}
	public function head()
	{
		echo $this->script;
	}
}