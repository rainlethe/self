<?php
class SelfCls{
	private $selfConfig = null;
	private $membervars = null;

	public function __construct(){
		include_once('selfconfig.php');
		$this->selfConfig = $selfConfig;
		$this->membervars = array();
	}

	public function __call($method, $args){		
		if (isset($this->selfConfig[$method])){						
			include_once($this->selfConfig[$method]);
				$justclsname = explode('/', $this->selfConfig[$method]);
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
}

$self = new SelfCls();

?>