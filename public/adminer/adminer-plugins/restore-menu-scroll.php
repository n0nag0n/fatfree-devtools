<?php
/**
* Remembers and restores scrollbar position of side menu
*
* Changes (2025-03-18):
* - Added nonce()
* - Changed load event binding
*
* @author Jiří @NoxArt Petruželka
* @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
*/
class AdminerRestoreMenuScroll {
	protected $script;

	/**
	* @param string text to append before first calendar usage
	*/
	public function __construct() {
		$this->script = "
		<script type='text/javascript' " . Adminer\nonce() .">
		(function(){
			var executed = false;
			var saveAndRestore = function() {
				if( executed ) {
					return;
				}

				executed = true;
				var menu = document.getElementById('menu');
				var scrolled = localStorage.getItem('_adminerScrolled');
				if( scrolled && scrolled >= 0 ) {
					menu.scrollTop = scrolled;
				}

				window.addEventListener('unload', function(){
					localStorage.setItem('_adminerScrolled', menu.scrollTop);
				});
			};
			
			window.addEventListener('load', saveAndRestore);
		})();
		</script>";
	}

	public function head() {
		echo $this->script;
	}

}
