<?
class RecursiveDirectoryIteratorFilter extends DirectoryIteratorFilter {
	public function __construct($path, $regex=null, $onlyFiles=0,$changeDate=0) {	
		parent::__construct(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::KEY_AS_PATHNAME), RecursiveIteratorIterator::CHILD_FIRST), $regex, $onlyFiles,$changeDate);
	}
}
?>