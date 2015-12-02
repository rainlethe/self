<?php
include_once('self.php');
// $self->fromString('abcd')->upper(false)->out()->upper()->out();

$self->fromString("A한글b")->lower(false)->out()->upper(false)->out()->lower()->out()->upper()->out();


?>