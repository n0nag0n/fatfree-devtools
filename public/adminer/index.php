<?php

if (PHP_MAJOR_VERSION >= 7) {
    set_error_handler(function ($errno, $errstr) {
       return strpos($errstr, 'Declaration of') === 0;
    }, E_WARNING);
}

function adminer_object() {
	
	// required to run any plugin
	include_once "./plugins/plugin.php";

	// autoloader
	foreach (glob("plugins/*.php") as $filename) {
		include_once "./$filename";
	}
	
    $plugins = array(
        // specify enabled plugins here
        new AdminerDumpZip,
        new AdminerDumpDate,
        new AdminerTablesHistory,
		new AdminerFloatThead,
		new AdminerRestoreMenuScroll,
		new AdminerReadableDates,
		new AdminerTablesFuzzySearch,
		new AdminerJsonPreview
    );
    
    return new AdminerPlugin($plugins);
}

// include original Adminer or Adminer Editor
include "./adminer.php";
?>