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
// $self->from("aBCdAa")->replace('a',1,false)->out();
// $self->from("aBCdAa")->padLeft(30,"zzz")->out();
// $self->from("=-")->repeat(10)->out();

// var_dump($self->from("asdf")->equalsValue("asdf"));
// var_dump($self->from("asdf")->equalsValue("asdfg"));
// var_dump($self->from("asdf")->equalsValueAndType("asdf"));
// var_dump($self->from("asdf")->equalsValueAndType($self->from("asdf")));

// $self->from('<p>테스트 문단.</p><!-- 주석 --> <a href="#fragment">다른 텍스트</a>')->htmlStrip()->out();
//$self->from('<p>테스트 문단.</p><!-- 주석 --> <a href="#fragment">다른 텍스트</a>')->htmlStrip("<p>")->out();

// $self->from("a나bc가 d")->reverse()->out();

// $arr = array(1,2,3,4,5);

function key_compare_func($a, $b)
{	
    if ($a === $b) {
        return 0;
    }
    return ($a > $b)? 1:-1;
}

$array1 = array("a" => "green", "b" => "brown", "c" => "blue", "red");
$array2 = array("a" => "green", "yellow", "red");
$result = array_diff_uassoc($array1, $array2, "key_compare_func");
print_r($result);
?>