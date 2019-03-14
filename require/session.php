<?php
class Session extends RnD {
	public function __construct(){
		session_start();
	}
	public function all(){
		return $_SESSION;
	}
	public function get($ses){
		return $_SESSION[$ses];
	}
	public function has($ses){
		if (isset($_SESSION[$ses]) && !empty($_SESSION[$ses]))
			return true;
		else
			return false;
	}
	public function set($data){
		if(is_array($data)){
			while ($A = current($data)){
				$_SESSION[key($data)] = $A;
				next($data);
			}
		}
		else
			die('Parameter data must array');
	}
	public function unset($data = NULL){
		if (is_array($data)){
			foreach ($data as $d)
				unset($_SESSION[$d]);
		}
		else{
			if (!empty($data))
				unset($_SESSION[$data]);
			else
				session_destroy();
		}
	}
}