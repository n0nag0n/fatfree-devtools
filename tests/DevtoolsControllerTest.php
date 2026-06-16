<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class DevtoolsControllerTest extends TestCase {
	public function testValidateHostRejectsCommandInjectionPatterns(): void {
		$invalidHosts = [
			'localhost;id',
			'$(whoami)',
			'host && id',
		];

		foreach($invalidHosts as $host) {
			try {
				InputValidator::validateHost($host);
				$this->fail('Expected InvalidArgumentException for host: '.$host);
			} catch(\InvalidArgumentException $e) {
				$this->addToAssertionCount(1);
			}
		}
	}
}