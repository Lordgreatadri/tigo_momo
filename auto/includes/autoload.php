<?php 

// spl_autoload_register( function ($className) 
// 	{
// 		$file = dirname(__DIR__) . '/classes/' . $className . '.php';

// 		if (file_exists($file)) {

// 		include $file;
// 		}
// 	});

spl_autoload_register('myAutoloader');

	function myAutoloader($className)
	{
		$path = "classes/";
		$extension = ".php";
		$fullPath = $path.$className.$extension;

		include_once $fullPath;
	}
	 ?>