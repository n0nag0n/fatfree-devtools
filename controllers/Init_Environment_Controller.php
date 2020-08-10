<?php
declare(strict_types=1);

namespace n0nag0n;

class Init_Environment_Controller extends Base_Controller {

	public function beforeroute(\Base $fw) {
		
		
	}
	
	public function indexAction(\Base $fw): void {
		$this->renderHtml('init_environment/index.htm');
	}

	public function pageAction(\Base $fw, array $args = []): void {

		$page = $args['page'];
		$page_ui = '';
		switch($page) {
			case 'title':
				$page_ui = 'init_environment/title.htm';
			break;

			case 'directory-setup':
				$page_ui = 'init_environment/directory_setup.htm';
			break;

			case 'basic-config':
				$page_ui = 'init_environment/basic_config.htm';
			break;

			case 'choose-database':
				$page_ui = 'init_environment/choose_database.htm';
			break;

			case 'jig':
				$page_ui = 'init_environment/jig.htm';
			break;

			case 'sqlite':
				$page_ui = 'init_environment/sqlite.htm';
			break;

			case 'mysql':
				$page_ui = 'init_environment/mysql.htm';
			break;

			case 'security':
				$page_ui = 'init_environment/security.htm';
			break;

			default:
				$fw->flash->addMessage('Unable to navigate to the correct page.', 'warning');
				$fw->reroute('init-environment');
		}

		$project_config = new Project_Config($fw->DB);
		$project_config->load();

		$this->renderHtml($page_ui, [ 'config' => $project_config->cast() ] );
	}

	public function update(\Base $fw): void {

		$post = $fw->scrub($fw->POST);

		if(!file_exists($fw->PROJECT_DATA_DIR)) {
			mkdir($fw->PROJECT_DATA_DIR, 0775);
		}

		$project_config = new Project_Config($fw->DB);
		$project_config->load();
		$project_config->copyfrom($post);
		$project_config->save();

		$fw->flash->addMessage('Settings Updated Successfully', 'success');

		if($post['next_page']) {
			$fw->reroute('/init-environment/'.$post['next_page'], false);
		}
	}

	public function build(\Base $fw): void {

		$project_config = new Project_Config($fw->DB);
		$project_config->load();
		$config = $project_config->cast();

		// start creating directories
		$this->createProjectDir($config['config']);
		$this->createProjectDir($config['controller']);
		$this->createProjectDir($config['model']);
		$this->createProjectDir($config['ui']);
		$this->createProjectDir($config['bin']);
		$this->createProjectDir($config['public']);
		$this->createProjectDir($config['utils']);
		$this->createProjectDir($config['log']);
		$this->createProjectDir($config['temp']);
		$this->createProjectDir($config['task']);

		// create some template files
		$this->createProjectFile($config['public'].'index.php', 'index.php');
		$this->createProjectFile($config['config'].'main_config.ini', 'main_config.ini');
		$this->createProjectFile($config['config'].$config['general_config'], 'config.ini');
		$this->createProjectFile($config['config'].$config['routes'], 'routes.ini');
		$this->createProjectFile($config['config'].$config['cli_routes'], 'cli_routes.ini');
		$this->createProjectFile($config['config'].$config['services'], 'services.php');

		// some default files
		$this->createProjectFile($config['controller'].'Base_Controller.php', 'Base_Controller.php');
		$this->createControllerFile($config['controller'].'Index_Controller.php');
		$this->createControllerFile($config['task'].'Task_Controller.php');

		$fw->flash->addMessage('Project Built Successfully! You can now use <code>fatfree-devtools serve</code> to serve your new project!', 'success');
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

	public function createControllerFile(string $relative_path): bool {
		if(!file_exists($this->fw->PROJECT_DATA_DIR.$relative_path)) {
			$controller_filename = basename($this->fw->PROJECT_DATA_DIR.$relative_path);
			$controller_name = explode('.', $controller_filename)[0];
			$contents = $this->fw->read(__DIR__.'/../../templates/Controller.php');
			$contents = str_replace([ '<?php', '#controller_name#' ], [ '#?php', $controller_name ], $contents);
			$contents = \Template::instance()->resolve($contents);
			$this->fw->write($this->fw->PROJECT_BASE_DIR.$relative_path, str_replace('#?php', '<?php', $contents));
		}
		return true;
	}

	public function relativePath(string $from, string $to, string $ps = DIRECTORY_SEPARATOR): string {
		$arFrom = explode($ps, rtrim($from, $ps));
		$arTo = explode($ps, rtrim($to, $ps));
		while(count($arFrom) && count($arTo) && ($arFrom[0] === $arTo[0])) {
			array_shift($arFrom);
			array_shift($arTo);
		}
		return str_pad("", count($arFrom) * 3, '..'.$ps).implode($ps, $arTo);
	}
}