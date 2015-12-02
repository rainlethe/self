<?php
include_once('self.php');
$len = $self->fromString("한글 테스트 ABCDEF")->out()->length();
var_dump($len);
?>