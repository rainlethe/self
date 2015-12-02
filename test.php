<?php
include_once('self.php');
$decode = $self->fromString("I'll &quot;walk&quot; the &lt;b&gt;dog&lt;/b&gt; now")->htmlDecode();
$encode = $self->fromString($decode)->htmlEncode();

// $decode = $self->fromString($decode);
$decode->out();
$encode->out();

?>