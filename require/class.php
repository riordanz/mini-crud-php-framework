<?php
// error_reporting(0);
$server = empty($_SERVER['DOCUMENT_ROOT']) ? '' : $_SERVER['DOCUMENT_ROOT'].'/';
require $server.'config/config.php';
$dir = scandir(__DIR__);
for ($i = 2; $i < count($dir);$i++){
	if ($dir[$i] !== 'class.php')
		require $dir[$i];
}
class RnD {

}