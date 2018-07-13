<?php
class Input extends RnD {
	public function __construct(){

	}
	public function post($name){
		return htmlentities($_POST[$name]);
	}
	public function get($name){
		return htmlentities($_GET[$name]);
	}
	public function cookie($name){
		return htmlentities($_COOKIE[$name]);
	}
}