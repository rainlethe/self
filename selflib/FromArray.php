<?php 
class FromArray{
	public $__invar = "";

	public function __construct($var){
		$this->__invar = $var;
	}

	/** fromArray 반환. 한번에 여러 element 입력 가능.  */
	public function add(){
		$args = func_get_args();
		foreach($args as $arg){
			array_push($this->__invar, $arg);	
		}

		return $this;		
	}



	/** fromArray 반환.. 여러 객체를 한번에 출력하고 싶을 때 사용합니다. */	
	public  function out(){
		$args = $this->__invar();		
		foreach($args as $arg){
			echo $arg;
			echo " ";
		}

		return $this->__invar;
	}

	


}

?>