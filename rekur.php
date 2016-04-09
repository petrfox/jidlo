<?php
 

    include("iter/DirectoryIteratorFilter.php");
    include("iter/FileIteratorFilter.php");
    include("iter/RecursiveDirectoryIteratorFilter.php");
    include("iter/RecursiveFileIteratorFilter.php");
    $it=new RecursiveFileIteratorFilter(dirname(__FILE__), "/.*/",'2016-03-20');//pred
    foreach ($it as $itFile) {
    echo $itFile->getPath()."/".$itFile->getFileName()."<br/>";
    }


?>