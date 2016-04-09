<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ukázkový příklad k článku Rekurzivní procházení adresářů s filtrem souborů</title>
</head>

<body>
Výpis pomocí DirectoryIteratorFilter:<br/>
$it=new DirectoryIteratorFilter(dirname(__FILE__), "^clanek");<br/>
foreach ($it as $itFile) {<br/>
	echo $itFile->getPath()." - ".$itFile->getFileName()."<br/>";<br/>
}<br/>
<?
include("DirectoryIteratorFilter.php");
include("FileIteratorFilter.php");
include("RecursiveDirectoryIteratorFilter.php");
include("RecursiveFileIteratorFilter.php");
$it=new RecursiveFileIteratorFilter(dirname(__FILE__), "^clanek");
foreach ($it as $itFile) {
	echo $itFile->getPath()." - ".$itFile->getFileName()."<br/>";
}
echo "Výpis bez omezení<br/>";
$it=new RecursiveFileIteratorFilter(dirname(__FILE__));
foreach ($it as $itFile) {
	echo $itFile->getPath()." - ".$itFile->getFileName()."<br/>";
}

?>
</body>
</html>
