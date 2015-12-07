<?php 
class FromNumber{
	public $__invar = "";

	public function __construct($var){
		$this->__invar = $var;
	}

	/* number_format */
	public function numberFormat($decimals = 0 , $dec_point = "." , $thousands_sep = ","){
		$ret = number_format($this->__invar, $decimals, $dec_point, $thousands_sep);		
		return $this->self->fromString($ret);
	}

	public function out(){
		echo $this->__invar;
	}
}

?>