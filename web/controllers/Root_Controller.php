<?php
declare(strict_types=1);

namespace n0nag0n;

class Root_Controller extends Base_Controller {

	public function beforeroute(\Base $fw) {
		
		
	}
	
	public function index(\Base $fw): void {
		
		// if the main config file is missing, start initing this bad boy
		if(!file_exists($fw->PROJECT_DATA_DIR)) {
			$fw->reroute('/init-environment', false);
		}

		$this->renderHtml('index/index.htm');
	}

	public function initEnvironment(\Base $fw): void {
		$this->renderHtml('index/init_environment.htm');
	}

	public function initEnvironmentCreate(\Base $fw): void {
		$post = $fw->clean($fw->POST);
		
		if(!file_exists($fw->PROJECT_DATA_DIR)) {
			mkdir($fw->PROJECT_DATA_DIR, 0775);
		}

		$project_config = new Project_Config($fw->DB);
		$project_config->load();
		$project_config->copyfrom($post);
		$project_config->save();

		// start creating directories
		$this->createProjectDir($post['config']);
		$this->createProjectDir($post['controller']);
		$this->createProjectDir($post['model']);
		$this->createProjectDir($post['ui']);
		$this->createProjectDir($post['bin']);
		$this->createProjectDir($post['public']);
		$this->createProjectDir($post['utils']);
		$this->createProjectDir($post['log']);
		$this->createProjectDir($post['temp']);

		// create some template files
		$this->createProjectFile($post['public'].'index.php', 'index.php');

		$fw->reroute('/', false);
	}

	public function createProjectDir(string $relative_path): bool {
		if(!file_exists($this->fw->PROJECT_BASE_DIR.$relative_path)) {
			return mkdir($this->fw->PROJECT_BASE_DIR.$relative_path, 0775, true);
		}
		return true;
	}

	public function createProjectFile(string $relative_path, string $template_file_path): bool {
		if(!file_exists($this->fw->PROJECT_DATA_DIR.$relative_path)) {
			$contents = $this->fw->read(__DIR__.'/../../templates/'.$template_file_path);
			$contents = str_replace('<?php', '#?php', $contents);
			$contents = \Template::instance()->resolve($contents);
			$this->fw->write($this->fw->PROJECT_BASE_DIR.$relative_path, str_replace('#?php', '<?php', $contents));
		}
		return true;
	}
}