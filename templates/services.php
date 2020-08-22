<?php

<check if="{{ @cnf_jig }}">
<true>
// Setup Jig DB Connection
$fw->set('DB', new \DB\Jig(PROJECT_DEVTOOLS_DATA_DIR, \DB\Jig::FORMAT_JSON ), 6000);
</true>
</check>

<check if="{{ @cnf_mysql_name }}">
<true>
$fw->set('DB', new DB\SQL('mysql:host={{ @cnf_mysql_host }};port={{ @cnf_mysql_port }};dbname={{ @cnf_mysql_name }};charset={{ @cnf_mysql_charset }}', '{{ @cnf_mysql_username }}',
    '{{ @cnf_mysql_password }}'
));
</true>
</check>

<check if="{{ @cnf_sqlite }}">
<true>
$fw->set('DB', new DB\SQL('sqlite:{{ @PROJECT_BASE_DIR }}{{ @cnf_sqlite }}'));
</true>
</check>