<?php
<check if="{{ @cnf_jig }}">
// Setup Jig DB Connection
$fw->set('DB', new \DB\Jig(PROJECT_DEVTOOLS_DATA_DIR, \DB\Jig::FORMAT_JSON ), 6000);
</check><check if="{{ @cnf_mysql_name }}">
$fw->set('DB', new DB\SQL('mysql:host='.$fw->get('mysql.host').';port='.$fw->get('mysql.port').';dbname='.$fw->get('mysql.database').';charset='.$fw->get('mysql.charset'), $fw->get('mysql.username'),$fw->get('mysql.password')
));
</check><check if="{{ @cnf_sqlite }}">
$fw->set('DB', new DB\SQL('sqlite:{{ @PROJECT_BASE_DIR }}{{ @cnf_sqlite }}'));
</check>

// Additional Template Headers for Convenience
Template::instance()->filter('length', 'Template_Helper::length');
Template::instance()->filter('convertDate', 'Date::convertUtcDateToTimeZone');
Template::instance()->filter('convertDateTime', 'Date::convertUtcTimestampToTimeZone');