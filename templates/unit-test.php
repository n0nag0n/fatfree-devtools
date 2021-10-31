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
		echo '[ '.$dateObj->format('Y-m-d H:i:s.v').' ('.convertMemoryUsage(memory_get_usage()).') ]: '.$text."\n";
	}
}

// https://www.php.net/manual/fr/function.memory-get-usage.php#96280
function convertMemoryUsage($size) {
    $unit=array('b','kb','mb','gb','tb','pb');
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}

function formatTextColor($str, $color) {
	switch($color) {
		case "green":
			$return = "\033[0;32m{$str}\033[0m";
			break;
			
		case "red":
			$return = "\033[0;31m{$str}\033[0m";
			break;
			
		case "yellow":
			$return = "\033[1;33m{$str}\033[0m";
			break;
			
		case "white":
			$return = "\033[1;37m{$str}\033[0m";
			break;
			
		case "orange":
			$return = "\033[0;33m{$str}\033[0m";
			break;
			
		case "blue":
			$return = "\033[0;34m{$str}\033[0m";
			break;
			
		case "gray":
		case "grey":
			$return = "\033[1;30m{$str}\033[0m";
			break;
			
		default:
			$return = $str;
	}
	
	return $return;
}

function prompt($prompt, $strtolower = true) {
	echoLine($prompt.' ', 'green');
	$response = trim(fgets(STDIN));
	if($strtolower)
		$response = strtolower($response);
	return $response;
}

$test = new Test;

echoLine('Beginning Unit Tests');
$source_time_stamps = [];
foreach(glob(__DIR__.'/../../{{ @cnf_tests }}*') as $unit_test_file) {
	if($unit_test_file === '.' || $unit_test_file === '..') {
		continue;
	}
	$start_ts = getCurrentMicrotime();
	$filename = basename($unit_test_file);
	echoLine('Testing '.$filename);
	require($unit_test_file);
	$source_time_stamps[$filename] = calculateMicrotimeDifference($start_ts,getCurrentMicrotime());
}
echoLine('Completed Unit Tests');

foreach ($test->results() as $result) {
	$desc = '';
	if(!empty($result['text'])) {
		$desc = ' - '.$result['text'];
	}
	$filename = basename(explode(':', $result['source'])[0]);
    if($result['status']) {
        echoLine(formatTextColor('Pass ('.$source_time_stamps[$filename].'s) - '.str_replace($fw->get('ROOT'), '', $result['source']).$desc, 'green'));
	} else {
		echoLine(formatTextColor('FAIL ('.$source_time_stamps[$filename].'s) - '.str_replace($fw->get('ROOT'), '', $result['source']).$desc, 'red'));
	}
}