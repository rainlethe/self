<?php 
class FromArray implements Iterator{
	public $__invar = array();	

	// Iterator 인터페이스 구현
	function rewind() {
		$v = reset($this->__invar);		
		return $v;
        
    }

    function current() {    	
    	$v = current($this->__invar);
    	return $this->self->from($v);    	        
    }

    function key() {
    	
    	$v = key($this->__invar);
    	return $v;    	        
    }

    function next() {        
        $v = next($this->__invar);
    	return $this->self->from($v);
    }

    function valid() {  
    	$v = key($this->__invar) !== null;    	
    	return $v;
    }


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

	public function addDict($key, $val){
		array_push($this->__invar, array($key=>$val));
		return $this;
	}

	public function addTuple(){
		$args = func_get_args();
		array_push($this->__invar, $args);
		return $this;
	}
	

	/* array_column */
	public function column($column_key, $index_key = null){
		$this->__invar = array_column($this->__invar, $column_key, $index_key);
		return $this;
	}

	/* array_combine, array_fill_keys */
	public function combineKeys($values){
		if (is_string($values)){
			$this->__invar = array_fill_keys($this->__invar, $values);
		}
		else if (is_array($values)){
			$this->__invar = array_combine($this->__invar, $values);	
		}
		else if (get_class($values) == "FromString"){
			$this->__invar = array_fill_keys($this->__invar, $values->toString());	
		}
		else if (get_class($values) == "FromArray"){
			$this->__invar = array_combine($this->__invar, $values->toArray());	
		}
		
		return $this;	
	}

	/* array_combine */
	public function combineValues($keys){
		if (is_array($keys)){
			$this->__invar = array_combine($keys, $this->__invar);	
		}		
		else if (get_class($keys) == "FromArray"){
			$this->__invar = array_combine($keys->toString(), $this->__invar);	
		}
		return $this;	
	}

	public function count(){
		return count($this->__invar);
	}

	/* array_count_values */
	public function countValues(){
		$this->__invar = array_count_values($this->__invar);
		return $this;
	}

	/* array_chunk */
	public function chunk($size, $holdkey=false){
		$this->__invar = array_chunk($this->__invar, $size, $holdkey);
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
		return $this;
	}

	/* array_fill */
	public function fillValues($startIdx, $num, $value){
		$this->__invar = array_fill($startIdx, $num, $value);
		return $this;

	}

	/* array_filter(string). 혹은 직접 구현. */
	public function filter($func = null){
		if ($func == null){
			$this->__invar = array_filter($this->__invar);	
		}else if (is_string($func)){
			$this->__invar = array_filter($this->__invar, $func);	
		}else if (is_callable($func)){
			$ret = array();
			foreach($this->__invar as $key=>$val){
				if ($func($key, $val)){
					$ret[$key] = $val;
				}
			}

			$this->__invar = $ret;
		}
		return $this;		
	}

	/* array_flip 키를 값으로. 값을 키로 변경. */
	public function flip(){
		$this->__invar = array_flip($this->__invar);
		return $this;
	}

	public function get($key1, $key2 = null, $defaultValue = ''){
		if (isset($this->__invar[$key1]) == false){
			return $defaultValue;
		}

		if ($key2 == null){ // key1 통채로 반환.			
			if (isset($this->__invar[$key1])){
				return $this->__invar[$key1];
			}else{
				return $defaultValue;
			}
		}else{
			$v = $this->__invar[$key1];

			if (isset($v[$key2])){
				return $v[$key2];
			}else{
				return $defaultValue;
			}
		}
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

	public function toArray(){
		return $this->__invar;
	}

	// array_change_key_case
	public function upperKey(){
		$this->__invar = array_change_key_case($this->__invar, CASE_UPPER);
	}


	
}

?>