<?php

declare(strict_types=1);

namespace n0nag0n;

class Config_Check_Controller extends Base_Controller {
	public function indexAction(\Base $fw): void {
		$this->renderHtml('config_check'.DIRECTORY_SEPARATOR.'index.htm');
	}
}
