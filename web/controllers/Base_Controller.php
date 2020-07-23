<?php
declare(strict_types=1);

namespace n0nag0n;

abstract class Base_Controller {

	protected $fw;

	public function __construct(\Base $fw) {
		$this->fw = $fw;
	}

	public function renderHtml(string $file_path, array $hive = []): void {
		$this->fw->content = $file_path;
		$this->fw->mset($hive);
		echo \Template::instance()->render('layout.htm');
	}
	
}