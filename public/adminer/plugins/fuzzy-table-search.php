<?php
/** Add fuzzy search in tables for Adminer
* @link https://github.com/brunetton/adminer-tables_fuzzy_search
* @author Bruno Duyé, https://github.com/brunetton
* @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
* @license http://www.gnu.org/licenses/gpl-2.0.html GNU General Public License, version 2 (one or other)
*/
class AdminerTablesFuzzySearch {
	function tablesPrint($tables) {
?>

<style media="screen" type="text/css">
#fuzzy_tables_search_result .selected a {
	color: red;
}
#fuzzy_tables_search_result a em {
	font-weight: bold;
	font-style: italic;
}
</style>

<script <?= nonce(); ?>>
<?php
	include("fuzzy_min.js");
	include("fuzzy_search.js");
?>
</script>
<p class="jsonly" style="padding-bottom: 0; border-bottom: none;">
	<input id='fuzzy_tables_search_input' accesskey="F" onblur="closeResults()"/>
</p>
<div id="fuzzy_tables_search_result" style="
	margin: 0 0 0 1em;
	border: 1px solid #969696;
	position: absolute;
	background-color: #FCFCFF;
	padding: 0.2em 0.2em 0.2em 0.4em;
	width: 25em;
	overflow: hidden;
	display: none;">
</div>
<?php
		//Adminer::tablesPrint();
		?>
<script <?= nonce(); ?>>
<?php include "tables_print.js"; ?>
</script>
<?php
	}
}
