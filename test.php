<?php
include_once('self.php');

$ar = array(1,2,3,4,5);
$self->fromArray($ar)->join("-")->out();

?>