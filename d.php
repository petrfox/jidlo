<?php
define('VYVOJ',true);
require 'classes/tosniscls.php';
if ($_SERVER["SERVER_NAME"]=='localhost') {
$dbcnf = array("host"=>"localhost","name"=>"tosnis","user"=>"fox","pass"=>"foxovo");
}else{
$dbcnf = array("host"=>"127.0.0.1","name"=>"tosniscz1","user"=>"tosnis.cz","pass"=>"foxovo456");
}
Tovarna::setDb("MySQL",$dbcnf);
$dbModel = Tovarna::getDb();

$r=$dbModel::getResult("SELECT id,nazev,cena,malacena FROM polozka where malacena = 999");
//$r=$dbModel::getResult("SELECT id,nazev,cena,malacena FROM polozka");

var_dump($r);
var_dump($r->fetch());
foreach($r->fetchall() as $poind){
	$menu[$poind['id']]=array('nazev' => $poind['nazev'],'cena' => $poind['cena'],'malacena' => $poind['malacena']);
}

//if (!isset($menu)) echo 'mo';
//var_dump($menu);

?>