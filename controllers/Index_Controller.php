<?php

declare(strict_types=1);

namespace n0nag0n;

class Index_Controller extends Base_Controller {

	public function index(\Base $fw): void {

		// if the main config file is missing, start initing this bad boy
		if(!$fw->has_been_inited) {
			$fw->reroute('/init-environment');
		}

		$this->renderHtml('index'.DIRECTORY_SEPARATOR.'index.htm');
	}

}
