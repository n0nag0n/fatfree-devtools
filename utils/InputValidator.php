<?php

declare(strict_types=1);

class InputValidator {
	/** @var string[] */
	public const COMPOSER_ACTIONS = [
		'setup', 'show', 'clear_cache', 'install', 'update', 'validate',
		'diagnose', 'archive', 'outdated', 'self_update',
	];

	/** @var string[] */
	public const PACKAGE_COMMANDS = [
		'require', 'remove', 'update', 'show', 'archive',
		'prohibits', 'depends', 'show_dependencies',
	];

	/** @var string[] */
	public const REPOSITORY_COMMANDS = [ 'add', 'remove' ];

	/** @var string[] */
	public const REPOSITORY_TYPES = [ 'path', 'vcs', 'pear', 'git' ];

	/** @var string[] */
	public const INIT_ENVIRONMENT_PAGES = [
		'title', 'directory-setup', 'basic-config', 'choose-database',
		'jig', 'sqlite', 'mysql', 'security',
	];

	public static function validateHost(string $host): string {
		$host = trim($host);
		if($host === '' || !preg_match('/^[a-zA-Z0-9.\-]+$/', $host)) {
			throw new \InvalidArgumentException('Invalid host');
		}
		return $host;
	}

	public static function sanitizeIdentifier(string $name): string {
		$name = trim($name);
		if($name === '' || !preg_match('/^[a-zA-Z0-9_]+$/', $name)) {
			throw new \InvalidArgumentException('Invalid identifier');
		}
		return $name;
	}

	public static function sanitizeIdentifierLoose(string $name): string {
		$purified = preg_replace('/\W/', '_', trim($name));
		if($purified === '' || $purified === null) {
			throw new \InvalidArgumentException('Invalid identifier');
		}
		return $purified;
	}

	public static function validateRelativePath(string $path): string {
		$path = str_replace('\\', '/', trim($path));
		if($path === '' || strpos($path, "\0") !== false) {
			throw new \InvalidArgumentException('Invalid path');
		}
		if($path[0] === '/' || preg_match('/^[a-zA-Z]:/', $path)) {
			throw new \InvalidArgumentException('Invalid path');
		}
		if(preg_match('#(^|/)\.\.(/|$)#', $path)) {
			throw new \InvalidArgumentException('Invalid path');
		}
		if(!preg_match('#^[a-zA-Z0-9_./\-]+$#', $path)) {
			throw new \InvalidArgumentException('Invalid path');
		}
		return $path;
	}

	public static function assertPathUnderBase(string $base_dir, string $relative_path): string {
		$validated = self::validateRelativePath($relative_path);
		$base_real = realpath($base_dir);
		if($base_real === false) {
			throw new \InvalidArgumentException('Invalid base directory');
		}
		$base_real = rtrim(str_replace('\\', '/', $base_real), '/').'/';
		$target = $base_real.$validated;
		$parent = dirname($target);
		if(!is_dir($parent) && !@mkdir($parent, 0775, true) && !is_dir($parent)) {
			// parent may not exist yet during project init
		}
		$target_real = realpath($target);
		if($target_real !== false) {
			$target_real = rtrim(str_replace('\\', '/', $target_real), '/').'/';
			if(strpos($target_real, $base_real) !== 0) {
				throw new \InvalidArgumentException('Path escapes project base directory');
			}
			return $validated;
		}
		$normalized = rtrim(str_replace('\\', '/', $target), '/').'/';
		if(strpos($normalized, $base_real) !== 0) {
			throw new \InvalidArgumentException('Path escapes project base directory');
		}
		return $validated;
	}

	public static function validateComposerCmd(string $cmd): string {
		$cmd = trim($cmd);
		if($cmd === '' || preg_match('/[;|&$`<>\\n\\r]/', $cmd)) {
			throw new \InvalidArgumentException('Invalid composer command');
		}
		return $cmd;
	}

	public static function validatePackageName(string $package): string {
		$package = trim($package);
		if($package === '' || preg_match('/[;|&$`<>\\n\\r]/', $package)) {
			throw new \InvalidArgumentException('Invalid package name');
		}
		return $package;
	}

	public static function validateUrl(string $url): string {
		$url = trim($url);
		if($url === '' || preg_match('/[;|&$`<>\\n\\r]/', $url)) {
			throw new \InvalidArgumentException('Invalid URL');
		}
		return $url;
	}

	public static function validateNamespace(string $namespace): string {
		if($namespace === '') {
			return '';
		}
		if(!preg_match('/^[a-zA-Z0-9\\\\]+$/', $namespace)) {
			throw new \InvalidArgumentException('Invalid namespace');
		}
		return $namespace;
	}

	public static function validateControllerEndpoint(string $endpoint): string {
		$endpoint = trim($endpoint);
		if(!preg_match('/^[a-zA-Z0-9_\\\\]+->[a-zA-Z0-9_]+$/', $endpoint)) {
			throw new \InvalidArgumentException('Invalid controller endpoint');
		}
		return $endpoint;
	}

	public static function assertAllowlisted(string $value, array $allowed, string $label): string {
		if(!in_array($value, $allowed, true)) {
			throw new \InvalidArgumentException('Invalid '.$label);
		}
		return $value;
	}

	public static function assertInitEnvironmentPage(string $page): string {
		return self::assertAllowlisted($page, self::INIT_ENVIRONMENT_PAGES, 'page');
	}
}