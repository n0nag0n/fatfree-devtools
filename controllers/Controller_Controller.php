<?php
declare(strict_types=1);

namespace n0nag0n;

use CLI;


class Controller_Controller extends Base_Controller {

	public function indexAction(\Base $fw): void {
		$controllers = $this->getControllers();
		$this->renderHtml('controllers/index.htm', [ 'controllers' => $controllers ]);
	}

	public function addAction(\Base $fw): void {
		$this->renderHtml('controllers/add.htm');
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

	public function detailsAction(\Base $fw, array $args = []): void {

		$controller = $args['controller'];

		$methods = $this->getControllerMethods($controller);
		$methods = $this->getControllerEndpointMethods($controller);
		$this->renderHtml('controllers/details.htm', [
			'controller_name' => $controller,
			'methods' => $methods
		]);
	}

	public function addEndpointAction(\Base $fw, array $args = []): void {
		$controller = $args['controller'];
		$this->renderHtml('controllers/add_endpoint.htm', [
			'controller_name' => $controller
		]);
	}

	public function createEndpoint(\Base $fw, array $args = []): void {
		$post = $fw->clean($fw->POST);
		$controller_name = $post['controller'] ?? $args['controller'];

		if(empty($controller_name)) {
			throw new \Exception('No controller_name specified');
		}

		$method_name = $post['method_name'] ?? $args['method_name'];

		if(empty($method_name)) {
			throw new \Exception('No method_name specified');
		}

		$result = $this->addEndpointMethodToController($controller_name, $method_name);
		if($result === true) {
			$message = 'Method successfully created';
			if($this->fw->CLI) {
				CLI::success($message);
			} else {
				$this->fw->flash->addMessage($message, 'success');
				$this->fw->reroute('/controllers/details/'.$controller_name, false);
			}
		} else {
			$message = 'Method failed to be created';
			if($this->fw->CLI) {
				CLI::error($message);
			} else {
				$this->fw->flash->addMessage($message, 'danger');
				$this->fw->reroute('/controllers/details/'.$controller_name.'/add-method', false);
			}
		}
	}

	public function getControllers() {
		$controller_dir = $this->fw->PROJECT_BASE_DIR.$this->fw->project_config->controller;
		$controllers = glob($controller_dir.'*');
		foreach($controllers as &$controller) {
			$controller = [ 'file' => $controller, 'base_name' => basename($controller, '.php') ];
		}
		return $controllers;
	}

	public function createControllerFile(string $controller_name): bool {

		$purified_controller_name = preg_replace("/\W/", '_', $controller_name).'_Controller';

		$full_path = $this->fw->PROJECT_BASE_DIR.$this->fw->project_config->controller.$purified_controller_name.'.php';
		if(file_exists($full_path)) {
			throw new \Exception('The controller already exists');
		}

		$contents = $this->fw->read(__DIR__.'/../templates/Controller.php');
		$contents = str_replace([ '<?php', '#controller_name#' ], [ '#?php', $purified_controller_name ], $contents);
		$contents = \Template::instance()->resolve($contents);
		$write_result = $this->fw->write($full_path, str_replace('#?php', '<?php', $contents));
		return is_int($write_result);
	}

	protected function getControllerMethods(string $controller_name): array {
		$arr = file($this->fw->PROJECT_BASE_DIR.$this->fw->project_config->controller.$controller_name.'.php');
		$arr_methods = [];
		foreach($arr as $line) {
			if (preg_match('/function ([_A-Za-z0-9]+)/i', $line, $regs)) {
				$arr_methods[] = $regs[1];
			}
		}
		return $arr_methods;
	}

	protected function getControllerEndpointMethods(string $controller_name): array {
		$arr_methods = $this->getControllerMethods($controller_name);
		return array_filter($arr_methods, function($value) {
			return strpos($value, 'Action') !== false;
		});
	}

	public function addEndpointMethodToController(string $controller_name, string $method_name): bool {
		$purified_controller_name = preg_replace("/\W/", '_', $controller_name);
		$purified_method_name = preg_replace("/\W/", '_', $method_name);
		$file_path = $this->fw->PROJECT_BASE_DIR.$this->fw->project_config->controller.$purified_controller_name.'.php';
		$arr = file($file_path);
		$new_method_arr = [
			"\n\tpublic function {$purified_method_name}Action(\\Base \$fw, array \$args = []): void {\n",
			"\t\t\n",
			"\t}\n"
		];
		$arr = array_merge(array_slice($arr, 0, -1), $new_method_arr, array_slice($arr, -1));
		$write_result = $this->fw->write($file_path, join('', $arr));
		return is_int($write_result);
	}
}