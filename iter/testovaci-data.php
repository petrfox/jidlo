<?
$fname=array("clanek", "neco", "uplne", "jineho");
function createDirWithFiles($path) {
	global $fname;
	@mkdir($path);
	for($i=0;$i<5;$i++) {
		$file=$fname[rand(0,2)].(str_repeat(chr(rand(65,90)), rand(0,10))).".txt";
		$f=fopen($path."/".$file, "w");
		fwrite($f, "test");
		fclose($f);
	}
}
for($i=0;$i<10;$i++) {
	$dname1=$fname[rand(0,2)].(str_repeat(chr(rand(65,90)), rand(0,10)));
	createDirWithFiles($dname1);
	for($ii=0;$ii<5;$ii++) {
		$dname2=$dname1."/".$fname[rand(0,2)].(str_repeat(chr(rand(65,90)), rand(0,10)));
		createDirWithFiles($dname2);
	}
}
die("Uz jsem vsechno vytvoril, podivej se do meho adresare! Umim udelat velky neporadek, tak se mnou zachazej slusne!");
?>