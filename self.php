<?php
class SelfCls{
	private $selfConfig = null;
	private $membervars = null;

	public function __construct(){
		include_once('selfconfig.php');
		$this->selfConfig = $selfConfig;
		$this->membervars = array();
	}

	public function bindCall($method, $args){		
		if (isset($this->selfConfig[$method])){
			$src = 	$this->selfConfig[$method];
			$src = $src['src'];
			include_once($src);
				$justclsname = explode('/', $src);
				$justclsname = $justclsname[count($justclsname) - 1];
				$justclsname = str_replace('.php','', $justclsname);
				
				$reflect  = new ReflectionClass($justclsname);
				$newcls = $reflect->newInstanceArgs($args);
				$newcls->self = $this;
				$membervars[$method] = $newcls;
				return $newcls;
		}else{ // config 에 정의 안되어 있음.
			throw new Exception('Non selfconfig settings Class.');
		}
	}

	public function __call($method, $args){
		return $this->bindCall($method, $args);
	}

	public function from($mixvar){
		foreach($this->selfConfig as $key=>$val){
			$typechecker = $val['typechecker'];
			foreach($typechecker as $funcname=>$equalval){
				if (call_user_func($funcname, $mixvar) === $equalval){
					
					return $this->bindCall($key, array($mixvar));
				}
			}
		}

		throw new Exception('cannot from any type of settings.');
	}
}

$self = new SelfCls();

?>