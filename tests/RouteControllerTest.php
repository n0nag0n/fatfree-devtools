<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use n0nag0n\Route_Controller;

class TestRouteController extends Route_Controller {
	public function __construct() {
		$this->fw = \Base::instance();
		$this->routes_file_path = '/tmp/routes.ini';
	}
}

class RouteControllerTest extends TestCase {
	public function testCreateRouteConfigString(): void {
		$controller = new TestRouteController();

		$result = $controller->createRouteConfigString(
			[ 'GET', 'POST' ],
			'[sync]',
			'/users',
			'User_Controller->indexAction',
			'@users: '
		);

		$this->assertStringContainsString('GET|POST', $result);
		$this->assertStringContainsString('@users: /users [sync] = User_Controller->indexAction', $result);
	}

	public function testGenerateLineHashIsDeterministic(): void {
		$controller = new TestRouteController();
		$line = "GET / = Index_Controller->index\n";

		$this->assertSame(md5($line), $controller->generateLineHash($line));
	}
}