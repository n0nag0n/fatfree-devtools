<?php
declare(strict_types=1);

namespace n0nag0n;

class Root_Controller extends Base_Controller {

	public function beforeroute(\Base $fw) {
		
		
	}
	
	public function index(\Base $fw): void {
		$this->renderHtml('index/index.htm');
	}

	public function initEnvironment(\Base $fw): void {
		$this->renderHtml('index/init-environment.htm');
	}
}