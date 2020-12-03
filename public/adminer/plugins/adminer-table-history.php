<?php

/**
* Show the history of the latest selected tables. Cookies based.
* Set the js variable history_length to define the history length.
* Works only with current browsers.
* @link http://www.adminer.org/plugins/#use
* @author Ale Rimoldi, http://www.ideale.ch/
* @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
*/
class AdminerTablesHistory {

	function tablesPrint($tables) {
		?>
<script <?=nonce();?>>

	history_length = 5;

    function setCookie(c_name, value, exdays) {
      var exdate = new Date();
      exdate.setDate(exdate.getDate() + exdays);
      var c_value = escape(value) + ((exdays == null) ? "" : "; expires=" + exdate.toUTCString());
      document.cookie = c_name + "=" + c_value;
    }

    function getCookie(c_name) {
      var i, x, y, ARRcookies = document.cookie.split(";");
      for (i = 0; i < ARRcookies.length; i++) {
        x = ARRcookies[i].substr(0, ARRcookies[i].indexOf("="));
        y = ARRcookies[i].substr(ARRcookies[i].indexOf("=") + 1);
        x = x.replace(/^\s+|\s+$/g, "");
        if (x == c_name) {
          return unescape(y);
        }
      }
	  return "";
    }

	function addToHistory(table) {
		// alert(table)
		var history_array = [];
		var history_cookie = getCookie('adminer_tables_history');
		if (history_cookie != '') {
			history_array = JSON && JSON.parse(history_cookie)
		}
		if (history_array.indexOf(table) == -1) {
			if (history_array.length >= history_length) {
				history_array.splice(0, 1);
			}
			history_array.push(table)
			setCookie('adminer_tables_history', JSON.stringify(history_array), 10)
		}
	}

	// $(document).ready "equivalent" without jQuery. should work with current browsers
	document.addEventListener('DOMContentLoaded',function(){
		// alert('chuila');
		var tables = document.getElementById('tables').getElementsByTagName('a');
		for (var i = 0; i < tables.length; i = i + 2) {
			var a = tables[i + 1];
			var text = a.innerText || a.textContent;
			a.setAttribute('onclick', 'addToHistory("'+text+'")');
			var a = tables[i];
			a.setAttribute('onclick', 'addToHistory("'+text+'")');
		}
	})
</script>
<?php if (array_key_exists('adminer_tables_history', $_COOKIE)) : ?>
<p onmouseover="menuOver(this, event);" onmouseout="menuOut(this);" style="white-space:nowrap;overflow:auto;text-overflow:ellipsis;"><?php
  // print_r($_COOKIE['adminer_tables_history']);
	foreach (array_reverse(json_decode($_COOKIE['adminer_tables_history'])) as $table) {
		echo '<a href="' . h(ME) . 'select=' . urlencode($table) . '"' . bold($_GET["select"] == $table) . ">" . lang('select') . "</a>&nbsp;";
		echo '<a href="' . h(ME) . 'table=' . urlencode($table) . '"' . bold($_GET["table"] == $table) . ">" . h($table) . "</a><br>\n";
	}
?></p>
<?php endif; ?>
<?php
		return null;
	}

}