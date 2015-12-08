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
	

	/* array_column */
	public function column($column_key, $index_key = null){
		$this->__invar = array_column($this->__invar, $column_key, $index_key);
		return $this;
	}

	/* array_combine */
	public function combineKeys($values){
		$this->__invar = array_combine($this->__invar, $values);
		return $this;	
	}

	/* array_combine */
	public function combineValues($keys){
		$this->__invar = array_combine($values, $this->__invar);
		return $this;	
	}

	/* array_count_values */
	public function countValues(){
		$this->__invar = array_count_values($this->__invar);
		return $this;
	}

	/* array_diff_key , array_diff_ukey */
	public function diffKey($diffarray, $funcName=null){		
		if ($funcName == null){
			$this->__invar = array_diff_key($this->__invar, $diffarray);
		}else{
			$this->__invar = array_diff_ukey($this->__invar, $diffarray, $funcName);
		}
		
		return $this;
	}

	/* array_diff_assoc, array_diff_uassoc */
	public function diffKeyValue($diffarray, $funcName=null){
		if ($funcName == null){
			$this->__invar = array_diff_assoc($this->__invar, $diffarray);
		}else{
			$this->__invar = array_diff_uassoc($this->__invar, $diffarray, $funcName);
		}
		return $this;
	}

	/* array_diff */
	public function diffValue($diffArray){
		$this->__invar = array_diff_key($this->__invar, $diffArray);
	}

	/* array_chunk */
	public function chunk($size, $holdkey=false){
		$this->__invar = array_chunk($this->__invar, $size, $holdkey);
		return $this;
	}

	/** fromString 반환. 배열을 $glue로 이어붙입니다. fromArray(array(1,2,3,4))->join() 은 1234. fromArray(array(1,2,3,4))->join(',') => 1,2,3,4 가 출력됩니다. */
	public function join($glue=''){
		$ret = implode($glue, $this->__invar);
		return $this->self->fromString($ret);
	}

	// array_change_key_case
	public function lowerKey(){
		$this->__invar = array_change_key_case($this->__invar, CASE_LOWER);	
	}

	/** fromArray 반환.. 여러 객체를 한번에 출력하고 싶을 때 사용합니다. */	
	public  function out(){		
		var_dump($this->__invar);
		return $this;
	} 

	// array_change_key_case
	public function upperKey(){
		$this->__invar = array_change_key_case($this->__invar, CASE_UPPER);
	}


	
}

?>