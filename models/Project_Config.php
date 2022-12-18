<?php

namespace n0nag0n;

class Project_Config extends \DB\Jig\Mapper {
	public function __construct(\DB\Jig $db) {
		parent::__construct($db, 'project_config');
	}
}
