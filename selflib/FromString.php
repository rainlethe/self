<?php 
class FromString{
	public $__invar = "";
	public function __construct($var){
		$this->__invar = $this->fromStringToString($var);
	}

	/* obj가 string 이면 그대로 반환. fromString 의 인스턴스이면 string 으로 바꿔서  반환합니다. */

	public function fromStringToString($obj){
		if (get_class($obj) === 'FromString'){
			return $obj->toString();
		}

		try{
			$ret = (string)	$obj;
			return $ret;
		}catch(Exception $ex){
			throw new Exception($ex);
		}
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

	/** fromString 반환. htmlentities 의 별칭입니다. 
	html 인코딩된 문자열을 디코딩합니다. 
	예를들면 ' ' 공백은 &nbsp; 로 변경됩니다. 
	htmlEncodeSpecial과는 다르게 모든 html에 대해 작동합니다. */		
	function htmlEncode($quote_style = ENT_COMPAT, $charset="UTF-8", $double_encode=true){		
		$this->__invar = htmlentities($this->__invar, $quote_style, $charset, $double_encode);
		return $this;
	}

	/** fromString 반환. htmlspecialchars 의 별칭입니다. 
	html 인코딩된 문자열을 디코딩합니다. 
	예를들면 ' ' 공백은 &nbsp; 로 변경됩니다. 
	htmlEncode와의 차이는 htmlEncodeSpecial 은 &, <, >, ', " 에 대해서만 작동합니다.*/		
	function htmlEncodeSpecial($quote_style = ENT_COMPAT, $charset="UTF-8", $double_encode=true){
		$this->__invar = htmlentities($this->__invar, $quote_style, $charset, $double_encode);
		return $this;
	}

	/** fromString 반환. html_entity_decode 의 별칭입니다. html 디코딩된 문자열을 인코딩합니다. 예를 들면 &nbsp; 는 ' ' 공백으로 변경됩니다. */	
	function htmlDecode($quote_style = ENT_COMPAT, $charset="UTF-8"){
		$this->__invar = html_entity_decode($this->__invar, $quote_style, $charset);
		return $this;
	}

}

?>