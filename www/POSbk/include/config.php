<?php

/* Do not modify below this line */

define('DOC_ROOT', realpath(dirname(__FILE__) . '/../'));
define('URL_ROOT', str_replace('\\', '/', substr(DOC_ROOT, strlen(realpath($_SERVER['DOCUMENT_ROOT'])))));

//error_reporting(E_STRICT | E_ALL);
fCore::enableErrorHandling('html');
fCore::enableExceptionHandling('html');
fCore::disableContext();

fTimestamp::setDefaultTimezone('Asia/Singapore');

$template = new fTemplating(DOC_ROOT.'/include/templates/');

$template->set(array(
    'header' => 'header.php',
    'footer' => 'footer.php'
    // ,'synhight' => 'synhigh.php'
));

include DOC_ROOT."/samples/_pdo.php";
$db_file = DOC_ROOT."/include/luckybunny_db.db";
PDO_Connect("sqlite:$db_file");
$date_format = date("Y-m-d");
//throw new Exception('DOC_ROOT: ' . URL_ROOT);

//fAuthorization::setLoginPage('http://localhost/login.php?action=log_in'); //URL_ROOT . 

// This prevents cross-site session transfer
//fSession::setPath(DOC_ROOT . '/sesfiles/');

/**
 * Automatically includes classes
 * 
 * @throws Exception
 * 
 * @param  string $class  Name of the class to load
 * @return void
 */
function __autoload($class)
{
	$flourish_file = DOC_ROOT . '/include/flourish/' . $class . '.php';
 
	if (file_exists($flourish_file)) {
		return require $flourish_file;
	}
	
	$file = DOC_ROOT . '/include/classes/' . $class . '.php';
 
	if (file_exists($file)) {
		
		return require $file;
	}
	
	throw new Exception('The class ' . $class . ' could not be loaded');
}

// if(!isset($_SESSION["username"])) {  
//     header("location:login-register.html");  
// }  

function isJson($string) {
	json_decode($string);
	return (json_last_error() == JSON_ERROR_NONE);
}