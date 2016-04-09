<?
class FileIteratorFilter extends DirectoryIteratorFilter {
	public function __construct($path, $regex=null) {
		parent::__construct($path, $regex, 1);
	}
}
?>