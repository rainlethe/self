<?php
class Request{
	public function __construct(){
		// nothing to do...
	}

	public function method(){
		return $_SERVER['REQUEST_METHOD'];
	}

	public function  isPost(){
		return $this->method() == "POST";
	}

	public function post($key, $defaultValue=null){
		if (isset($_POST[$key])){
			return $_POST[$key];
		}else{
			return $defaultValue;
		}
	}

	public function get($key, $defaultValue=null){
		if (isset($_GET[$key])){
			return $_GET[$key];
		}else{
			return $defaultValue;
		}
	}

	public function parameter($key, $defaultValue=null){
		if ($this->get($key, null) != null){
			return $this->get($key);
		}
		return $this->post($key, $defaultValue);
	}

	public function redirect($redirectUrl){
		header('Location: ' .$redirectUrl);
		exit();
	}

	public function redirectBack(){
		header('Location: ' . $_SERVER['HTTP_REFERER']);
		exit();
	}
}
?>