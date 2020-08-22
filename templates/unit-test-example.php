<?php
	$hello = 'Hello World!';
	$test->expect(
		!empty($hello),
		'Var has something'
	);

	$test->expect(
		$hello === 'Hello World!',
		'Text is equal'
	);

	$test->expect(
		$hello === 'Bye World!',
		'Text is equal, but this will fail'
	);