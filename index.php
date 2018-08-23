<?php

	if (file_exists(dirname(__FILE__)) . '/development.lock') {
		define('ENVIRONMENT', 'development');
	} else {
		define('ENVIRONMENT', 'production');
	}

	if (defined('ENVIRONMENT')) {
		switch(ENVIRONMENT) {
			case 'production':
				error_reporting(-1);
				break;
			case 'development':
				error_reporting(0);
				break;
			default:
				exit('please set environment');				
		}
	}

	$error_log_temp = './error_log/error_log.log';
	$error_log = ini_get('error_log');
	if (empty($error_log)) {
		ini_set('error_log', $error_log_temp);
	}

	$system_path = 'system';
	$application_path = 'application';

	// /vagrant_data/beibei/system/
	if (realpath($system_path) !== FALSE) {
		$system_path = realpath($system_path) . '/';
	}

	if (!is_dir($system_path)) {
		exit("Your system folder path does not appear to be set correctly. Please open the following file and correct this: " . pathinfo(__FILE__, PATHINFO_BASENAME));
	}

	// Array ( [dirname] => /vagrant_data/beibei [basename] => index.php [extension] => php [filename] => index )
	define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));	// index.php

	define('EXT', '.php');

	define('BASEPATH', str_replace("\\", "/", $system_path));

	define('FCPATH', str_replace(SELF, '', __FILE__));

	define('SYSDIR', trim(strrchr(trim(BASEPATH, '/'), '/'), '/'));

	define('ENABLE_ELASTICSEARCH', TRUE);

	if (id_dir($application_path)) {
		define('APPPATH', $application_path . '/');
	} else {
		if (!is_dir(BASEPATH . $application_path . '/')) {
			exit('Your application folder path does not appear to be set correctly. Please open the following file and correct this: ' . SELF);
		}
		define('APPPATH', $BASEPATH . $application_path . '/');
	}

	require_once BASEPATH . 'core/Codeigniter' . EXT;
