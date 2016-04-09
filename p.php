<?php

$o =array(1,2,array('  <a','b  '),array(3,array('f','g')));
//$o =array('a','b ');			
function rekurFil($in){
		if(is_array($in))return array_map('rekurFil',$in);
		return htmlspecialchars(trim($in));
}			

$o = array_map('rekurFil',$o);

var_dump($o);

?>