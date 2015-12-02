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

	/** fromString 반환. 배열을 $glue로 이어붙입니다. fromArray(array(1,2,3,4))->join() 은 1234. fromArray(array(1,2,3,4))->join(',') => 1,2,3,4 가 출력됩니다. */
	public function join($glue=''){
		$ret = implode($glue, $this->__invar);
		return $this->self->fromString($ret);
	}

	/** fromArray 반환.. 여러 객체를 한번에 출력하고 싶을 때 사용합니다. */	
	public  function out(){
		echo $this->join($this->__invar());
		return $this;
	}


}

?>