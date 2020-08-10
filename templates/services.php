<?php

// Setup Jig DB Connection
$fw->set('DB', new \DB\Jig(PROJECT_DEVTOOLS_DATA_DIR, \DB\Jig::FORMAT_JSON ), 6000);