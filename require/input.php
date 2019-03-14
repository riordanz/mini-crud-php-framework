<?php
class Input extends RnD {
	public function __construct(){

	}
	public function post($name){
		return htmlentities($_POST[$name]);
	}
	public function has($method, $name){
		if (strtolower($method) === 'get')
			return (isset($_GET[$name]) && !empty($_GET[$name])) ? 1 : 0;
		elseif (strtolower($method) === 'post')
			return (isset($_POST[$name]) && !empty($_POST[$name])) ? 1 : 0;
		elseif (strtolower($method) === 'head')
			return (isset($_HEAD[$name]) && !empty($_HEAD[$name])) ? 1 : 0;
		elseif (strtolower($method) === 'delete')
			return (isset($_DELETE[$name]) && !empty($_DELETE[$name])) ? 1 : 0;
	}
	public function get($name){
		return htmlentities($_GET[$name]);
	}
	public function cookie($name){
		return htmlentities($_COOKIE[$name]);
	}
}