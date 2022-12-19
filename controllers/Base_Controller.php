<?php

declare(strict_types=1);

namespace n0nag0n;

abstract class Base_Controller {
	protected $fw;

	public function __construct(\Base $fw) {
		$this->fw = $fw;
		$has_been_inited = $this->hasBeenInited();
		if($this->hasBeenInited() === false && strpos($this->fw->URI, '/init-environment') === false) {
			$this->fw->reroute('/init-environment', false);
		}

		$this->fw->set('has_been_inited', $has_been_inited);
		if($has_been_inited) {
			$project_config = new Project_Config($fw->DB);
			$project_config->load();
			$this->fw->set('project_config', $project_config);
		}
	}

	public function renderHtml(string $file_path, array $hive = []): void {
		$this->fw->content = $file_path;
		$this->fw->mset($hive);
		echo \Template::instance()->render('layout.htm');
	}

	public function hasBeenInited(): bool {
		return file_exists($this->fw->PROJECT_CONFIG);
	}
}
