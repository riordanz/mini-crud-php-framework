<?php
require 'config/config.php';
$dir = scandir(__DIR__);
for ($i = 2; $i < count($dir);$i++){
	if ($dir[$i] !== 'class.php')
		require $dir[$i];
}
class RnD {

}