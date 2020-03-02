<?php 

	/*Security*/
	define('SECRETE_KEY', 'IajxETyHgKbWADSfG9pBmJuFkwlsZrtC');

	DEFINE ('DBUSERL', 'velocity_db1');
	DEFINE ('DBPWL', 'VAdmin@db');
	DEFINE ('DBHOST', 'localhost');
	DEFINE ('DBNAMEL', 'velocity_clients');
	DEFINE ('NOREPLY', 'noreply@velocityhealth.co.za');
	DEFINE ('NOREPLYPWD' ,'EE#{G%2U{bkl');

	/*Data Type*/
	define('BOOLEAN', 	'1');
	define('INTEGER', 	'2');
	define('STRING', 	'3');

	/*Response Codes Codes*/
	define('SUCCESS_RESPONSE', 						200);
	define('CREATED', 								201);
	define('NOT_MODIFIED', 							304);
	define('BAD_REQUEST', 							400);
	define('UNAUTHORISED', 							401);
	define('FORBIDEN', 								403);
	define('NOT_FOUND',								404);
	define('UNPROCESSABLE_ENTITY', 					422);
	define('INTERNAL_SERVER_ERROR', 				500);