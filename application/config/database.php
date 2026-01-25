<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['dsn']      The full DSN string describe a connection to the database.
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database driver. e.g.: mysqli.
|			Currently supported:
|				 cubrid, ibase, mssql, mysql, mysqli, oci8,
|				 odbc, pdo, postgre, sqlite, sqlite3, sqlsrv
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Query Builder class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['encrypt']  Whether or not to use an encrypted connection.
|
|			'mysql' (deprecated), 'sqlsrv' and 'pdo/sqlsrv' drivers accept TRUE/FALSE
|			'mysqli' and 'pdo/mysql' drivers accept an array with the following options:
|
|				'ssl_key'    - Path to the private key file
|				'ssl_cert'   - Path to the public key certificate file
|				'ssl_ca'     - Path to the certificate authority file
|				'ssl_capath' - Path to a directory containing trusted CA certificates in PEM format
|				'ssl_cipher' - List of *allowed* ciphers to be used for the encryption, separated by colons (':')
|				'ssl_verify' - TRUE/FALSE; Whether verify the server certificate or not
|
|	['compress'] Whether or not to use client compression (MySQL only)
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|	['ssl_options']	Used to set various SSL options that can be used when making SSL connections.
|	['failover'] array - A array with 0 or more data for connections if the main should fail.
|	['save_queries'] TRUE/FALSE - Whether to "save" all executed queries.
| 				NOTE: Disabling this will also effectively disable both
| 				$this->db->last_query() and profiling of DB queries.
| 				When you run a query, with this setting set to TRUE (default),
| 				CodeIgniter will store the SQL statement for debugging purposes.
| 				However, this may cause high memory usage, especially if you run
| 				a lot of SQL queries ... disable this to avoid that problem.
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $query_builder variables lets you determine whether or not to load
| the query builder class.
*/
$active_group = 'default';
$query_builder = TRUE;


$urlParts = parse_url($_SERVER['HTTP_HOST']);
$urlKey = array_keys($urlParts);
$url = $urlParts[$urlKey[0]];


// print_r($urlParts['host']);
if($url=='localhost'){
	// $servername = "18.130.130.0";
	// $username = "console";
	// $password = "MeeraN@g10";
	// $database = "vowdev";

	// $servername = "35.178.47.156";
	// $username = "reportsqa";
	// $password = "Reports@12345";
	// $database = "vowdev";

	$servername = "3.7.255.145";
	$username = "reports";
	$password = "Reports@12345";
	$database = "vowsls";

	// $servername = "3.110.17.158";
	// $username = "reports";
	// $password = "Reports@12345";
	// $database = "vowsw_capex_live";

	// $servername = "43.205.92.221";
	// $username = "sw";
	// $password = "Stpl@321";
	// $database = "vowsw_capex_live";

	// $servername = "43.205.92.221";
	// $username = "reports";
	// $password = "Reports@12345";
	// $database = "vowsls";

	// $servername = "3.7.255.145";
	// $username = "reports";
	// $password = "Reports@12345";
	// $database = "vowtalbot";

	// $servername = "3.7.255.145";
	// $username = "reports";
	// $password = "Reports@12345";
	// $database = "vowsw_capex_live";
	
	
}else if($url == '13.126.47.172'){


//	echo 'my http://103.154.234.215/';
	$servername = "3.7.255.145";
	$username = "reports";
	$password = "Reports@12345";
	$database = "vowsls";
}else if($url == '103.154.234.215'){


	//	echo 'my http://103.154.234.215/';
		$servername = "3.7.255.145";
		$username = "reports";
		$password = "Reports@12345";
		$database = "vowsls";
	}else if($url == 'data.dev.vowerp.com'){
	$servername = "13.232.34.218";
	$username = "devuser";
	$password = "VowDev!123";
	$database = "vowdev";
}else if($url == 'data.qa.vowerp.com'){
	$servername = "13.232.34.218";
	$username = "devuser";
	$password = "VowDev!123";
	$database = "vowqa";
}else if($url == 'data.sls.vowerp.com'){
	$servername = "3.7.255.145";
	$username = "reports";
	$password = "Reports@12345";
	$database = "vowsls";
}else if($url == 'data.sls1.2.vowerp.com'){
	$servername = "3.7.255.145";
	$username = "reports";
	$password = "Reports@12345";
	$database = "vowsls";
}else if($url == 'data.talbot.vowerp.com'){
	$servername = "3.7.255.145";
	$username = "reports";
	$password = "Reports@12345";
	$database = "vowtalbot";
}else if($url == 'data.ajm1.2.vowerp.com'){
	$servername = "3.7.255.145";
	$username = "reports";
	$password = "Reports@12345";
	$database = "vowajm";
}else if($url == 'data.ajm.vowerp.com'){
	$servername = "3.7.255.145";
	$username = "reports";
	$password = "Reports@12345";
	$database = "vowajm";
// }else if($url == 'data.sw.vowerp.com'){
// 	$servername = "3.7.255.145";
// 	$username = "reports";
// 	$password = "Reports@12345";
// 	$database = "vowsworks";
// }else if($url == 'data.capexsw.vowerp.com'){
// 	$servername = "3.7.255.145";
// 	$username = "reports";
// 	$password = "Reports@12345";
// 	$database = "vowsw_capex_live";
// }else if($url == 'data.smartworks.vowerp.com'){
// 	$servername = "3.7.255.145";
// 	$username = "reports";
// 	$password = "Reports@12345";
// 	$database = "vowsw_capex_live";
}else if($url == 'data.cloud.vowerp.com'){
	$servername = "3.7.255.145";
	$username = "reports";
	$password = "Reports@12345";
	$database = "vowcloud";
}else if($url == 'data.capexdev.vowerp.com'){
	$servername = "3.7.255.145";
	$username = "reports";
	$password = "Reports@12345";
	$database = "vowsw_capex_live";
}else if($url == 'data.workplace.vowerp.com'){
	$servername = "3.7.255.145";
	$username = "reports";
	$password = "Reports@12345";
	$database = "vow_workplace";
}else if($url == 'data.qasls.vowerp.com'){
	$servername = "3.7.255.145";
	$username = "reports";
	$password = "Reports@12345";
	$database = "vowqa";
}else if($url == 'data.qasw12.vowerp.com'){
	$servername = "3.7.255.145";
	$username = "reports";
	$password = "Reports@12345";
	$database = "vowdevproc";
}else if($url == 'data.capextest.vowerp.com'){
	$servername = "13.232.34.218";
	$username = "devuser";
    $password = "VowDev!123";
	$database = "vowsw_capex_test";
}





$db['default'] = array(
	'dsn'	=> '',
	'hostname' => $servername,
	'username' => $username,
	'password' => $password,
	'database' => $database,
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => FALSE,
	'failover' => array(),
	'save_queries' => TRUE
);
