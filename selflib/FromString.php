<?php 
class FromString{
	public $__invar = "";
	public function __construct($var){
		$this->__invar = $this->fromStringToString($var);
	}

	/* obj가 string 이면 그대로 반환. fromString 의 인스턴스이면 string 으로 바꿔서  반환합니다. */
	public function fromStringToString($obj){
		if (is_string($obj)){
			return $obj;
		}

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

	/** boolean 반환. 문자열이 $needle 을 포함하는 지 검사합니다. */
	function contains($needle)
	{
		return mb_strrpos($this->__invar, $needle) !== false;		
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

	/** boolean 반환. 문자열이 $needle 로 끝나는 지 검사합니다. */
	function endsWith($needle)
	{
		return $needle === "" || mb_substr($this->__invar, -mb_strlen($needle)) === $needle;
	}

	function equalsValue($obj){
		if (is_string($obj)){
			return $this->__invar == $obj;
		}else if (get_class($obj) == "FromString"){
			return $this->__invar == $obj->toString();
		}
		return false;
	}

	function equalsValueAndType($obj){		
		if (get_class($obj) === "FromString"){
			return $this->__invar === $obj->toString();
		}
		return false;
	}

	// mb_strstr, mb_stristr, mb_strrichr, mb_strrchr
	function find($needle, $ignoreCase=false, $reverse=false){
		if ($ignoreCase){
			if ($reverse){				
				$this->__invar = mb_strrichr($this->__invar, $needle);	
			}else{
				$this->__invar = mb_stristr($this->__invar, $needle);
			}
			
		}else{				
			if ($reverse){
				$this->__invar = mb_strrchr($this->__invar, $needle);	
			}else{
				$this->__invar = mb_strstr($this->__invar, $needle);
			}
		}
		return $this;
	}

	/* sprintf */
	function format(){
		$args = func_get_args();
		array_unshift($args, $this->__invar);		
		$this->__invar = call_user_func_array("sprintf", $args);
		return $this;
	}

	function formatScan($format){
		$ret = sscanf($this->__invar, $format);
		return $this->self->fromArray($ret);
	}

	/** fromString 반환. 
	html_entity_decode 의 별칭입니다. 
	html 디코딩된 문자열을 인코딩합니다. 
	예를 들면 &nbsp; 는 ' ' 공백으로 변경됩니다. */	
	function htmlDecode($quote_style = ENT_COMPAT, $charset="UTF-8"){
		$this->__invar = html_entity_decode($this->__invar, $quote_style, $charset);
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
	/* strip_tags */
	function htmlStrip($allowable_tags = ''){
		$this->__invar = strip_tags($this->__invar, $allowable_tags);
		return $this;
	}

	/* mb_strpos, mb_stripos */
	function indexOf($needle, $ignoreCase = false){
		$ret = 0;
		if ($ignoreCase){			
			$ret = $this->__invar = mb_stripos($this->__invar, $needle);
		}else{
			$ret = $this->__invar = mb_strpos($this->__invar, $needle);
		}

		return $this->self->from($ret);
	}

	function lastIndexOf($needle, $ignoreCase = false){
		$ret = 0;
		if ($ignoreCase){						
			$ret = $this->__invar = mb_strripos($this->__invar, $needle);
		}else{
			$ret = $this->__invar = mb_strrpos($this->__invar, $needle);
		}

		return $this->self->from($ret);

	}
	

	/** int 반환. 문자열의 길이를 셉니다. $charset 이 지정되지 않으면 기본값은 UTF-8 입니다. */
	function length($charset='UTF-8'){
		return  mb_strlen($this->__invar, $charset);		
	}

	/** fromString 반환
	lcfirst, strtolower 의 별칭입니다. 
	$isalllower 가 true이면  믄자열 전체를 소문자로 변경합니다. 
	$isalllower 가 false  믄자열의 첫번째만 소문자로 변경합니다. 
	*/
	function lower($isalllower=true, $charset='UTF-8'){
		if ($isalllower){			
			$this->__invar = mb_strtolower($this->__invar, $charset);			
		}else{
			$this->__invar = mb_strtolower(mb_substr($this->__invar, 0,1), $charset) . mb_strtolower(mb_substr($this->__invar, 1), $charset);
		}
		return $this;
	}

	/** fromString 반환.
	ltrim 의 별칭입니다.
	*/
	function ltrim(){
		$this->__invar = ltrim($this->__invar);
		return $this;
	}

	/* fromString 반환.md5 로 변환합니다. */
	function md5($raw_output=false){
		$this->__invar = md5($this->__invar, $raw_output);
		return $this;	
	}

	/** fromString 반환. nl2br의 별칭입니다. [\r | \n | \r\n] 을 [\r <br /> | \n <br /> | \r\n <br />] 로 변경합니다. */
	function newLineToBR($is_xhtml=false, $is_remove_nl = false){
		$this->__invar = nl2br($this->__invar, $is_xhtml);
		if ($is_remove_nl){
			$this->replace("\r","")->replace("\n","");
		}

		return $this;
	}

	/*str_pad */
	function padLeft($total_string_length, $pad_string = ' '){
		$this->__invar = str_pad($this->__invar, $total_string_length, $pad_string, STR_PAD_LEFT);		
		return $this;
	}

	/*str_pad */
	function padRight($total_string_length, $pad_string = ' '){
		$this->__invar = str_pad($this->__invar, $total_string_length, $pad_string, STR_PAD_RIGHT);
		return $this;
	}

	/*str_pad */
	function padBoth($total_string_length, $pad_string = ' '){
		$this->__invar = str_pad($this->__invar, $total_string_length, $pad_string, STR_PAD_BOTH);
		return $this;
	}

	function repeat($iterator_count){
		$this->__invar = str_repeat($this->__invar, $iterator_count);
		return $this;
	}

	/** fromString 반환. str_replace의 별칭입니다.  */
	function replace($oldstring, $newstring, $ignoreCase=false){
		if ($ignoreCase){
			$this->__invar = str_ireplace($oldstring, $newstring, $this->__invar);
		}else{
			$this->__invar = str_replace($oldstring, $newstring, $this->__invar);			
		}
		
		return $this;
	}

	/* http://stackoverflow.com/questions/434250/how-to-reverse-a-unicode-string */
	function reverse(){		 
		preg_match_all('/./us', $this->__invar, $ar);
 		$this->__invar = implode(array_reverse($ar[0]));
		return $this;
	}


	/** fromString 반환.
	rtrim 의 별칭입니다.
	*/
	function rtrim(){
		$this->__invar = rtrim($this->__invar);
		return $this;
	}

	

	/* sha1 */
	function sha1($raw_output=false){
		$this->__invar = sha1($this->__invar, $raw_output);
		return $this;
	}

	/** fromString 반환. str_shuffle과 같은 기능을 하지만 유니코드 확장을 위해서 구현은 다릅니다. 
	소스코드는 http://php.net/manual/en/function.str-shuffle.php#107656 를 참조했습니다.
	*/
	function shuffle(){	
		$tmp = preg_split("//u", $this->__invar, -1, PREG_SPLIT_NO_EMPTY);
	    shuffle($tmp);
	    $this->__invar = join("", $tmp);			
		//$this->__invar = str_shuffle($this->__invar);		
		return $this;
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
		return $needle === "" || mb_strpos($this->__invar, $needle) === 0;		
	}	

	

	/* substr */
	function subString($start_index, $length = null){
		if ($length == null){
			$this->__invar = mb_substr($this->__invar, $start_index);
		}else{
			$this->__invar = mb_substr($this->__invar, $start_index, $length);
		}
	}

	/** fromArray 반환.  str_split 의 별칭입니다. 각 문자열을 분해합니다. 
	$split_length 가 입력되면 하나의 블럭당 $split_length 만큼의 글자가 할당됩니다.
	  */
	function toCharArray($split_length = 1){
		$ret = str_split($this->__invar, $split_length);
		return $this->self->fromArray($ret);
	}

	/** string 반환.  fromString 개체를 string 으로 변경합니다. */
	public function toString(){
		return $this->__invar;
	}
	

	/** fromString 반환.
	rtrim 의 별칭입니다.
	*/
	function trim(){
		$this->__invar = trim($this->__invar);
		return $this;
	}


	/** fromString 반환
	ucfirst, strtoupper 의 별칭입니다. 	
	$isallupper 가 true이면  믄자열 전체를 대문자로 변경합니다. 
	$isallupper 가 false  믄자열의 첫번째만 대문자로 변경합니다. 
	*/
	function upper($isallupper=true, $charset='UTF-8'){
		if ($isallupper){			
			$this->__invar = mb_strtoupper($this->__invar, $charset);
		}else{
			$this->__invar = mb_strtoupper(mb_substr($this->__invar, 0,1), $charset) . mb_strtoupper(mb_substr($this->__invar, 1), $charset);
		}
		return $this;
	}

	/* parse_str()  */
	function urlToArray(){
		parse_str($this->__invar, $output);		
		return $this->self->fromArray($output);
	}


	/** fromString 반환. $wrapLeft와 $wrapRight로 감쌉니다. */
	function wrap($wrapLeft="[", $wrapRight="]"){
		$this->__invar = "[" . $this->__invar . "]";
		return $this;
	}


	/** fromString 반환. 문자열을 출력합니다. echo 의 별칭입니다. */
	function out(){
		echo $this->__invar;
		return $this;
	}
}

?>