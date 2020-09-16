<?php
declare(strict_types=1);

namespace n0nag0n;

use CLI;


class Route_Controller extends Base_Controller {

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
		$controller_name = $post['controller'] ?? $args['controller'];

		if(empty($controller_name)) {
			throw new \Exception('No controller_name specified');
		}

		$result = $this->createControllerFile($controller_name);
		if($result === true) {
			$message = 'Controller successfully created';
			if($this->fw->CLI) {
				CLI::success($message);
			} else {
				$this->fw->flash->addMessage($message, 'success');
				$this->fw->reroute('/controllers', false);
			}
		} else {
			$message = 'Controller failed to be created';
			if($this->fw->CLI) {
				CLI::error($message);
			} else {
				$this->fw->flash->addMessage($message, 'danger');
				$this->fw->reroute('/controllers/add', false);
			}
		}
	}

	public function getRoutes(): array {
		$routes_file = $this->fw->PROJECT_BASE_DIR.$this->fw->project_config->config.$this->fw->project_config->routes;
		$routes = [];
		foreach(parse_ini_file($routes_file) as $route_definition => $controller) {
			$final_route = [];
			
			$explode = explode(' ', preg_replace("/\s+/", ' ', trim($route_definition)));
			$final_route['method'] = $explode[0];
			$final_route['route'] = $explode[1];
			$final_route['type'] = $explode[2] ?? '';
			$final_route['controller'] = $controller;
			$routes[] = $final_route;
		}
		return $routes;
	}

	public function createRouteConfigString(array $methods, string $type, string $path, string $controller): string {
		return join('|', $methods).' '.$path.' '.$type.' = '.$controller;
	}
}