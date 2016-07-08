<?php

// This is the database connection configuration.
return array(
	//'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
	// uncomment the following lines to use a MySQL database
	
	'connectionString' => 'mysql:host=localhost;dbname=superixu_afit',
	'emulatePrepare' => true,
	'username' => 'superixu_afit',
	'password' => 'afit_password',
	'charset' => 'utf8',
	'tablePrefix'=>'afit_',
);