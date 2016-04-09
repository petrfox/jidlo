<?
class RecursiveFileIteratorFilter extends RecursiveDirectoryIteratorFilter {
	public function __construct($path, $regex=null,$changeDate=0) {	
		parent::__construct($path, $regex, 1,$changeDate);
	}
}
?>