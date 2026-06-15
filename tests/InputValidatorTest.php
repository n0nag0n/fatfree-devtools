<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class InputValidatorTest extends TestCase {
	public function testValidateHostAcceptsLocalhost(): void {
		$this->assertSame('localhost', InputValidator::validateHost('localhost'));
	}

	public function testValidateHostAcceptsIpAddress(): void {
		$this->assertSame('127.0.0.1', InputValidator::validateHost('127.0.0.1'));
	}

	public function testValidateHostRejectsInjection(): void {
		$this->expectException(\InvalidArgumentException::class);
		InputValidator::validateHost('localhost; rm -rf /');
	}

	public function testSanitizeIdentifierLoose(): void {
		$this->assertSame('My_Controller_', InputValidator::sanitizeIdentifierLoose('My Controller!'));
	}

	public function testSanitizeIdentifierRejectsEmpty(): void {
		$this->expectException(\InvalidArgumentException::class);
		InputValidator::sanitizeIdentifierLoose('   ');
	}

	public function testValidateRelativePathAcceptsProjectPaths(): void {
		$this->assertSame('app/config/', InputValidator::validateRelativePath('app/config/'));
	}

	public function testValidateRelativePathRejectsTraversal(): void {
		$this->expectException(\InvalidArgumentException::class);
		InputValidator::validateRelativePath('../etc/passwd');
	}

	public function testValidateRelativePathRejectsAbsolute(): void {
		$this->expectException(\InvalidArgumentException::class);
		InputValidator::validateRelativePath('/etc/passwd');
	}

	public function testValidateComposerCmdAcceptsRequire(): void {
		$this->assertSame('require symfony/console', InputValidator::validateComposerCmd('require symfony/console'));
	}

	public function testValidateComposerCmdRejectsShellMetacharacters(): void {
		$this->expectException(\InvalidArgumentException::class);
		InputValidator::validateComposerCmd('install; whoami');
	}

	public function testAssertAllowlistedComposerActions(): void {
		$this->assertSame('install', InputValidator::assertAllowlisted('install', InputValidator::COMPOSER_ACTIONS, 'composer action'));
	}

	public function testAssertAllowlistedRejectsUnknownAction(): void {
		$this->expectException(\InvalidArgumentException::class);
		InputValidator::assertAllowlisted('php_shell', InputValidator::COMPOSER_ACTIONS, 'composer action');
	}

	public function testValidateNamespaceAllowsTrailingBackslash(): void {
		$this->assertSame('\\App\\Controllers\\', InputValidator::validateNamespace('\\App\\Controllers\\'));
	}

	public function testValidateControllerEndpoint(): void {
		$this->assertSame('Index_Controller->indexAction', InputValidator::validateControllerEndpoint('Index_Controller->indexAction'));
	}

	public function testValidateControllerEndpointRejectsInvalid(): void {
		$this->expectException(\InvalidArgumentException::class);
		InputValidator::validateControllerEndpoint('bad-endpoint');
	}

	public function testAssertPathUnderBase(): void {
		$base = sys_get_temp_dir().'/devtools-test-'.uniqid();
		mkdir($base, 0775, true);
		try {
			$this->assertSame('app/config/', InputValidator::assertPathUnderBase($base.'/', 'app/config/'));
		} finally {
			$this->removeDirectory($base);
		}
	}

	public function testAssertPathUnderBaseRejectsEscape(): void {
		$base = sys_get_temp_dir().'/devtools-test-'.uniqid();
		mkdir($base.'/app', 0775, true);
		try {
			$this->expectException(\InvalidArgumentException::class);
			InputValidator::assertPathUnderBase($base.'/', '../../etc/passwd');
		} finally {
			rmdir($base.'/app');
			rmdir($base);
		}
	}

	public function testAssertInitEnvironmentPage(): void {
		$this->assertSame('directory-setup', InputValidator::assertInitEnvironmentPage('directory-setup'));
	}

	public function testAssertInitEnvironmentPageRejectsUnknown(): void {
		$this->expectException(\InvalidArgumentException::class);
		InputValidator::assertInitEnvironmentPage('../../../evil');
	}

	private function removeDirectory(string $dir): void {
		if(!is_dir($dir)) {
			return;
		}
		$items = scandir($dir);
		if($items === false) {
			return;
		}
		foreach($items as $item) {
			if($item === '.' || $item === '..') {
				continue;
			}
			$path = $dir.DIRECTORY_SEPARATOR.$item;
			if(is_dir($path)) {
				$this->removeDirectory($path);
			} else {
				unlink($path);
			}
		}
		rmdir($dir);
	}
}