<?php

// set the using lib.
$selfConfig = array(
	'fromNumber'=>array('src'=>'selflib/FromNumber.php', 'typechecker'=> array( 'is_numeric' => true, 'get_class'=> 'FromNumber'))
	,'fromString'=>array('src'=>'selflib/FromString.php', 'typechecker'=> array( 'is_string' => true, 'get_class'=> 'FromString'))
	,'fromArray'=>array('src'=>'selflib/FromArray.php', 'typechecker'=> array( 'is_array' => true, 'get_class'=> 'FromArray'))
	
);
?>