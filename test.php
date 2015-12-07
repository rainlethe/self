<?php
include_once('self.php');

// $self->fromString("a b c 가 나 다 ")->toCharArray()->out();
// $self->fromNumber(1234.56)->numberFormat()->out();
// $self->fromString("first=value&arr[]=foo+bar&arr[]=baz")->urlToArray()->out();
// $self->fromNumber(17999)->numberFormat()->out();
// $self->fromString("asdf")->sha1()->out();
// $self->fromString("asdfg")->sha1()->out();

//$self->fromString('this is %d monkeys in the %s')->format(12,"zoo")->out();
// $self->fromString("SN/January 01 2000")->formatScan("SN/%s %d %d")->out();

//$self->fromString("aBCdAa")->replace('a',1)->out();
//$self->fromString("aBCdAa")->replace('a',1,false)->out();
$self->from("aBCdAa")->replace('a',1,false)->out();

?>