<?php 
class FromString{
	public $__invar = "";
	public function __construct($var){
		$this->__invar = $var;
	}

	/** string 반환.  fromString 개체를 string 으로 변경합니다. */
	public function toString(){
		return $this->__invar;
	}

	/** fromArray 반환. 문자열을 $delimiter로 나눌 때 사용합니다. */
	public function split($delimiter, $limit=null){
		$splitresult = null;
		if ($limit == null){
			$splitresult = explode($delimiter, $this->__invar);			
		}else{
			$splitresult = explode($delimiter, $this->__invar, $limit);			
		}

		return $this->self->fromArray($splitresult);
	}

	/** boolean 반환. 문자열이 $needle 로 시작하는 지 검사합니다. */
	public function startsWith($needle)
	{
		return $needle === "" || strpos($this->__invar, $needle) === 0;
		
	}

	/** boolean 반환. 문자열이 $needle 로 끝나는 지 검사합니다. */
	function endsWith($needle)
	{
		return $needle === "" || substr($this->__invar, -strlen($needle)) === $needle;
	}

	/** boolean 반환. 문자열이 $needle 을 포함하는 지 검사합니다. */
	function contains($needle)
	{
		return strrpos($this->__invar, $needle) !== false;		
	}

	/** int 반환. 문자열의 길이를 셉니다. $charset 이 지정되지 않으면 기본값은 UTF-8 입니다. */
	function length($charset='UTF-8'){
		return  mb_strlen($this->__invar, $charset);		
	}

	/** fromString 반환. 문자열을 단방향으로 암호화합니다. $salt 는 암호화에 쓰이는 키입니다. */
	function crypt($salt=null){
		if ($salt == null){
			$this->__invar = crypt($this->__invar);
		}else{
			$this->__invar = crypt($this->__invar, $salt);
		}

		return $this;
	}

	/** fromString 반환. 문자열을 출력합니다. echo 의 별칭입니다. */
	function out(){
		echo $this->__invar;
		return $this;
	}

}

?>