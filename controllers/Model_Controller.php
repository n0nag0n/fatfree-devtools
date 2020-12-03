<?php
declare(strict_types=1);

namespace n0nag0n;

use CLI;

class Model_Controller extends Base_Controller {

	public function indexAction(\Base $fw): void {
		$models = $this->getModels();
		$this->renderHtml('models/index.htm', [ 'models' => $models ]);
	}

	public function addAction(\Base $fw): void {
		$this->renderHtml('models/add.htm');
	}

	public function create(\Base $fw, array $args = []): void {
		$post = $fw->clean($fw->POST);
		$model_name = $post['model'] ?? $args['model'];
		$table_name = $post['table'] ?? $args['table'];

		if(empty($model_name)) {
			throw new \Exception('No model_name specified');
		}

		$result = $this->createModelFile($model_name, $table_name);
		if($result === true) {
			$message = 'Model successfully created';
			if($this->fw->CLI) {
				CLI::success($message);
			} else {
				$this->fw->flash->addMessage($message, 'success');
				$this->fw->reroute('/models', false);
			}
		} else {
			$message = 'Model failed to be created';
			if($this->fw->CLI) {
				CLI::error($message);
			} else {
				$this->fw->flash->addMessage($message, 'danger');
				$this->fw->reroute('/models/add', false);
			}
		}
	}

	public function createModelFile(string $model_name, string $table_name): bool {

		$purified_model_name = preg_replace("/\W/", '_', $model_name);

		$full_path = $this->fw->PROJECT_BASE_DIR.$this->fw->project_config->model.$purified_model_name.'.php';
		if(file_exists($full_path)) {
			throw new \Exception('The model already exists');
		}

		$contents = $this->fw->read(__DIR__.'/../templates/Model.php');
		$contents = str_replace([ '<?php', '#model_name#', '#table_name#' ], [ '#?php', $purified_model_name, $table_name ], $contents);
		$contents = \Template::instance()->resolve($contents);
		$write_result = $this->fw->write($full_path, str_replace('#?php', '<?php', $contents));
		return is_int($write_result);
	}

	public function getModels() {
		$model_dir = $this->fw->PROJECT_BASE_DIR.$this->fw->project_config->model;
		$models = glob($model_dir.'*');
		foreach($models as &$model) {
			$model = [ 'file' => $model, 'base_name' => basename($model, '.php') ];
		}
		return $models;
	}
}