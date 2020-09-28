<?php
declare(strict_types=1);

namespace n0nag0n;

use CLI;

class Route_Controller extends Base_Controller {

	protected $routes_file_path;

	public function __construct(\Base $fw) {
		parent::__construct($fw);
		$this->routes_file_path = $this->fw->PROJECT_BASE_DIR.$this->fw->project_config->config.$this->fw->project_config->routes;
	}

	public function indexAction(\Base $fw): void {
		$routes = $this->getRoutes();
		$this->renderHtml('routes/index.htm', [ 'routes' => $routes ]);
	}

	public function addAction(\Base $fw): void {
		$Controller_Controller = new Controller_Controller($fw);
		$controllers = $Controller_Controller->getControllers();
		foreach($controllers as &$controller) {
			$controller['endpoints'] = $Controller_Controller->getControllerEndpointMethods($controller['base_name']);
		}
		$this->renderHtml('routes/add.htm', [
			'controllers' => $controllers
		]);
	}

	public function create(\Base $fw, array $args = []): void {
		$post = $fw->clean($fw->POST);
		$methods = $post['methods'];
		$type = $post['type'];
		$path = $post['path'];
		$namespace = $post['namespace'] ?: '';
		$controller_endpoint = $post['controller_endpoint'];

		if(empty($methods)) {
			$this->fw->flash->addMessage('You must select at least one method before proceeding.', 'danger');
			$this->fw->reroute('/routes/add', false);
		}

		if(empty($methods) || empty($type) || empty($path) || empty($controller_endpoint)) {
			throw new \Exception('Missing data.');
		}

		$result = $this->addRoute($methods, $type, $path, $namespace.$controller_endpoint);

		if($result === true) {
			$message = 'Route successfully created';
			if($this->fw->CLI) {
				CLI::success($message);
			} else {
				$this->fw->flash->addMessage($message, 'success');
				$this->fw->reroute('/routes', false);
			}
		} else {
			$message = 'Route failed to be created';
			if($this->fw->CLI) {
				CLI::error($message);
			} else {
				$this->fw->flash->addMessage($message, 'danger');
				$this->fw->reroute('/routes/add', false);
			}
		}
	}

	public function delete(\Base $fw, array $args = []): void {
		$args = $fw->clean($args);
		$token = $args['token'];
		if(empty($token)) {
			throw new \Exception('Missing token.');
		}

		$result = $this->deleteRoute($token);

		if($result === true) {
			$message = 'Route successfully deleted';
			if($this->fw->CLI) {
				CLI::success($message);
			} else {
				$this->fw->flash->addMessage($message, 'success');
				$this->fw->reroute('/routes', false);
			}
		} else {
			$message = 'Route failed to be deleted';
			if($this->fw->CLI) {
				CLI::error($message);
			} else {
				$this->fw->flash->addMessage($message, 'danger');
				$this->fw->reroute('/routes', false);
			}
		}
	}

	public function getRoutes(): array {
		$routes = [];
		$routes_file_lines = $this->getRawTextRoutes();
		foreach($routes_file_lines as $line) {

			$line_token = $this->generateLineHash($line);
			$exp = explode('=', $line);
			$exp = array_map('trim', $exp);
			$route_definition = $exp[0];
			$controller = $exp[1];

			$explode = explode(' ', preg_replace("/\s+/", ' ', trim($route_definition)));

			$final_route = [];
			$final_route['method'] = $explode[0];
			$final_route['route'] = $explode[1];
			$final_route['type'] = $explode[2] ?? '';
			$final_route['controller'] = $controller;
			$final_route['token'] = $line_token;
			$routes[] = $final_route;
		}
		return $routes;
	}

	public function addRoute(array $methods, string $type, string $path, string $controller) : bool {
		$route_string = $this->createRouteConfigString($methods, $type, $path, $controller);
		$routes_file = $this->routes_file_path;
		$result = $this->fw->write($routes_file, $route_string, true);
		return is_int($result);
	}

	public function deleteRoute(string $token): bool {
		$raw_file_lines = file($this->routes_file_path);
		foreach($raw_file_lines as $line_number => $text) {
			if($this->generateLineHash($text) === $token) {
				unset($raw_file_lines[$line_number]);
				break;
			}
		}
		$result = $this->fw->write($this->routes_file_path, join('', $raw_file_lines));
		return is_int($result);
	}

	public function createRouteConfigString(array $methods, string $type, string $path, string $controller): string {
		$path = preg_replace("/\-+/", '-', preg_replace("/[^\w\-\@\/\:]+/", '-', $path));
		if(substr($path, 0, 1) !== '/') {
			$path = '/'.$path;
		}
		return "\n".join('|', $methods).' '.$path.' '.$type.' = '.$controller;
	}

	public function generateLineHash(string $line): string {
		return md5($line);
	}

	public function getRawTextRoutes(): array {
		$routes = [];
		$routes_file_lines = file($this->routes_file_path);
		foreach($routes_file_lines as $line) {
			if(strpos($line, '=') === false) {
				continue;
			}
			$routes[] = $line;
		}
		return $routes;
	}
}