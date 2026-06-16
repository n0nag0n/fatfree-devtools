<?php

declare(strict_types=1);

namespace n0nag0n;

class Init_Environment_Controller extends Base_Controller {

	/** @var array Holds the hive temporarily for sandboxing */
	protected array $temp;

	/** @var string[] */
	private const PATH_FIELDS = [
		'config', 'controller', 'task', 'model', 'ui', 'tests', 'public', 'bin',
		'utils', 'log', 'uploads', 'temp', 'composer', 'sqlite', 'jig',
		'general_config', 'routes', 'cli_routes', 'services',
	];

	public function indexAction(\Base $fw): void {
		$this->renderHtml('init_environment'.DIRECTORY_SEPARATOR.'index.htm', [ 'PAGE_TITLE' => 'Initialize Environment' ]);
	}

	public function pageAction(\Base $fw, array $args = []): void {

		$page = $args['page'];
		$page_ui = '';
		$page_title = '';

		switch($page) {
			case 'title':
				$page_ui = 'init_environment'.DIRECTORY_SEPARATOR.'title.htm';
				$page_title = 'Title';
			break;

			case 'directory-setup':
				$page_ui = 'init_environment'.DIRECTORY_SEPARATOR.'directory_setup.htm';
				$page_title = 'Directory Setup';
			break;

			case 'basic-config':
				$page_ui = 'init_environment'.DIRECTORY_SEPARATOR.'basic_config.htm';
				$page_title = 'Basic Config';
			break;

			case 'choose-database':
				$page_ui = 'init_environment'.DIRECTORY_SEPARATOR.'choose_database.htm';
				$page_title = 'Choose Database';
			break;

			case 'jig':
				$page_ui = 'init_environment'.DIRECTORY_SEPARATOR.'jig.htm';
				$page_title = 'Jig';
			break;

			case 'sqlite':
				$page_ui = 'init_environment'.DIRECTORY_SEPARATOR.'sqlite.htm';
				$page_title = 'SQLite';
			break;

			case 'mysql':
				$page_ui = 'init_environment'.DIRECTORY_SEPARATOR.'mysql.htm';
				$page_title = 'MySQL';
			break;

			case 'security':
				$page_ui = 'init_environment'.DIRECTORY_SEPARATOR.'security.htm';
				$page_title = 'Security';
			break;

			default:
				$fw->flash->addMessage('Unable to navigate to the correct page.', 'warning');
				$fw->reroute('init-environment');
		}

		$project_config = new Project_Config($fw->DB);
		$project_config->load();

		$this->renderHtml($page_ui, [ 'config' => $project_config, 'PAGE_TITLE' => $page_title.' - Configure Environment' ] );
	}

	public function update(\Base $fw): void {

		$post = $fw->scrub($fw->POST);

		if(!file_exists($fw->PROJECT_DATA_DIR)) {
			mkdir($fw->PROJECT_DATA_DIR, 0775);
		}

		foreach(self::PATH_FIELDS as $field) {
			if(isset($post[$field]) && $post[$field] !== '') {
				$post[$field] = InputValidator::validateRelativePath((string)$post[$field]);
			}
		}

		if(!empty($post['database_management'])) {
			$post['database_management'] = $this->validateDatabaseManagementPath((string)$post['database_management']);
		}

		$project_config = new Project_Config($fw->DB);
		$project_config->load();
		$project_config->copyfrom($post);
		$project_config->save();

		if(!empty($post['next_page'])) {
			$next_page = InputValidator::assertInitEnvironmentPage((string)$post['next_page']);
			$fw->reroute('/init-environment/'.$next_page, false);
		}
	}

	public function build(\Base $fw): void {

		$project_config = new Project_Config($fw->DB);
		$project_config->load();
		$config = $project_config->cast();

		foreach(self::PATH_FIELDS as $field) {
			if(!empty($config[$field])) {
				$config[$field] = InputValidator::validateRelativePath((string)$config[$field]);
			}
		}

		$fw->mset($config, 'cnf_');
		$fw->set('project_config', $project_config);

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
		$this->createProjectDir($config['uploads']);
		$this->createProjectDir($config['tests']);

		if(!empty($config['sqlite'])) {
			$this->createProjectDir(dirname($config['sqlite']));
		}

		$this->createProjectFile($config['public'].'index.php', 'index.php');
		$this->createProjectFile($config['config'].'main_config.ini', 'main_config.ini');
		$this->createProjectFile($config['config'].$config['general_config'], 'config.ini');
		$this->createProjectFile($config['config'].$config['routes'], 'routes.ini');
		$this->createProjectFile($config['config'].$config['cli_routes'], 'cli_routes.ini');
		$this->createProjectFile($config['config'].$config['services'], 'services.php');
		$this->createProjectFile($config['config'].'bootstrap.php', 'bootstrap.php');

		$this->createProjectFile($config['controller'].'Base_Controller.php', 'Base_Controller.php');
		$this->createProjectFile($config['bin'].'unit-test', 'unit-test.php');
		$this->makeProjectFileExecutable($config['bin'].'unit-test');
		$this->createProjectFile($config['tests'].'unit-test-example.php', 'unit-test-example.php');
		$Controller_Controller = new Controller_Controller($this->fw);
		$Controller_Controller->createControllerFile('Index');
		$this->createControllerFileOld($config['task'].'Task_Controller.php');
		$this->createProjectFile('.gitignore', 'vcsignore');

		$this->createProjectFile($config['utils'].'Date.php', 'Date.php');
		$this->createProjectFile($config['utils'].'Template_Helper.php', 'Template_Helper.php');
		$this->createProjectFile($config['model'].'Mapper_Shim.php', 'Mapper_Shim.php');

		$fw->flash->addMessage('Project Built Successfully! You can now use `fatfree serve` to serve your new project!', 'success');
		$fw->reroute('/', false);
	}

	protected function createProjectDir(string $relative_path): bool {
		if(empty($relative_path)) {
			return true;
		}
		InputValidator::assertPathUnderBase($this->fw->PROJECT_BASE_DIR, $relative_path);
		if(!file_exists($this->fw->PROJECT_BASE_DIR.$relative_path)) {
			return mkdir($this->fw->PROJECT_BASE_DIR.$relative_path, 0775, true);
		}
		return true;
	}

	protected function createProjectFile(string $relative_path, string $template_file_path): bool {
		InputValidator::assertPathUnderBase($this->fw->PROJECT_BASE_DIR, $relative_path);
		$file_path = $this->fw->PROJECT_BASE_DIR.$relative_path;
		if(!empty($relative_path) && !file_exists($file_path)) {
			$contents = $this->fw->read(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.$template_file_path);
			$contents = str_replace('<?php', '<test-php', $contents);

			$parsed_contents = \Template::instance()->parse($contents);
			$contents = \Template::instance()->build($parsed_contents);
			$contents = \Template::instance()->resolve($contents);
			$this->fw->write($file_path, $contents);
			$contents = $this->sandbox($file_path);
			$this->fw->write($file_path, str_replace('<test-php', '<?php', $contents));
		}
		return true;
	}

	protected function makeProjectFileExecutable(string $relative_path): bool {
		InputValidator::assertPathUnderBase($this->fw->PROJECT_BASE_DIR, $relative_path);
		$file_path = $this->fw->PROJECT_BASE_DIR.$relative_path;
		return chmod($file_path, 0775);
	}

	protected function sandbox(string $file_path): string {
		$base_real = realpath($this->fw->PROJECT_BASE_DIR);
		$file_real = realpath($file_path);
		if($base_real === false || $file_real === false) {
			throw new \InvalidArgumentException('Invalid sandbox file path');
		}
		$base_real = rtrim(str_replace('\\', '/', $base_real), '/').'/';
		$file_real = str_replace('\\', '/', $file_real);
		if(strpos($file_real, $base_real) !== 0) {
			throw new \InvalidArgumentException('Sandbox file escapes project base directory');
		}

		$this->temp=$this->fw->hive();
		unset($hive);
		extract($this->temp);
		ob_start();
		require($file_path);
		return ob_get_clean();
	}

	protected function createControllerFileOld(string $relative_path): bool {
		InputValidator::assertPathUnderBase($this->fw->PROJECT_BASE_DIR, $relative_path);
		if(!file_exists($this->fw->PROJECT_BASE_DIR.$relative_path)) {
			$controller_filename = basename($this->fw->PROJECT_BASE_DIR.$relative_path);
			$controller_name = explode('.', $controller_filename)[0];
			$contents = $this->fw->read(__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR.'Controller.php');
			$contents = str_replace([ '<?php', '#controller_name#' ], [ '#?php', $controller_name ], $contents);
			$contents = \Template::instance()->resolve($contents);
			$this->fw->write($this->fw->PROJECT_BASE_DIR.$relative_path, str_replace('#?php', '<?php', $contents));
		}
		return true;
	}

	protected function validateDatabaseManagementPath(string $path): string {
		$path = trim($path);
		if($path === '' || !preg_match('#^/[a-zA-Z0-9_./\-]*$#', $path)) {
			throw new \InvalidArgumentException('Invalid database management path');
		}
		return $path;
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