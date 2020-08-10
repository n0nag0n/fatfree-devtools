<?php

final class #controller_name# extends Base_Controller {

	public function indexAction(\Base $fw, array $args = []): void {
		$this->outputHtmlResponse('<h1>'.__METHOD__.' is up and running!</h1>');
	}
}