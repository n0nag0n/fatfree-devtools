#!/usr/bin/env php
<?php
DEFINE("PROJECT_ROOT_DIR", __DIR__.'/../../');
require(PROJECT_ROOT_DIR.'{{ @cnf_config }}bootstrap.php');

/**
 * Some helpful functions to calculate time elapsed
 */

function getCurrentMicrotime(): float {
	$mtime = microtime();
	$mtime = explode(" ",$mtime);
	return $mtime[1] + $mtime[0];
}

function calculateMicrotimeDifference(float $start_time, float $end_time): float {
	return round($end_time - $start_time, 4);
}

function echoLine(string $text) {
	$dateObj = DateTime::createFromFormat('0.u00 U', microtime());
	$dateObj->setTimeZone(new DateTimeZone('America/Denver'));
	if($text) {
		echo '[ '.$dateObj->format('Y-m-d H:i:s.v').' Mem: '.memory_get_usage().' ]: '.$text."\n";
	}
}

$test = new Test;

echoLine('Beginning Unit Tests');
$source_time_stamps = [];
foreach(glob(__DIR__.'/../../{{ @cnf_tests }}/*') as $unit_test_file) {
	if($unit_test_file === '.' || $unit_test_file === '..') {
		continue;
	}
	$start_ts = getCurrentMicrotime();
	$filename = basename($unit_test_file);
	echoLine($ts.' - Testing '.$filename);
	require($unit_test_file);
	$source_time_stamps[$filename] = calculateMicrotimeDifference($start_ts,getCurrentMicrotime());
}
echoLine('Completed Unit Tests');

foreach ($test->results() as $result) {
	$desc = '';
	if(!empty($result['text'])) {
		$desc = $result['text'].' - ';
	}
	$filename = basename($result['source']);
    if($result['status']) {
        echoLine($filename.' - '.$desc.'Pass ('.$source_time_stamps[$filename].' seconds)');
	} else {
		echoLine($filename.' - '.$desc.'Fail ('.$source_time_stamps[$filename].' seconds)');
	}
}