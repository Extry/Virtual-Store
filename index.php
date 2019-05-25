<?php 
include_once 'config.php';
session_start();

function check($class,$file = [])
{
	foreach($file as $files) {
		if(file_exists($files.'/'.$class.'.php')) 
			require $files.'/'.$class.'.php';
	}
	if (file_exists($files.'/'.$class.'.class.php')) {
		require $files.'/'.$class.'.class.php';
	}
}
spl_autoload_register(function($class){

	check($class,['core','controllers','models']);

});
$core = new Core();
$core->run();