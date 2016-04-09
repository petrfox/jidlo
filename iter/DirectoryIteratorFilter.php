<?
class DirectoryIteratorFilter extends FilterIterator {
	private $_rx, $_onlyFiles,$changeDate;
	public function __construct($path, $regex=null, $onlyFiles=0,$changeDate=0) {
		if (is_object($path)) parent::__construct($path);
		else parent::__construct(new DirectoryIterator($path));
		$this->_rx=$regex;
		$this->_onlyFiles=$onlyFiles;
		$this->changeDate=$changeDate;
	}
	public function accept() {
		$inner=$this->getInnerIterator();
		if ($inner->isDot()) return false;
		if ($this->_onlyFiles) if ($inner->isDir()) return false;
		if ($this->changeDate){
			$da=new DateTime($this->changeDate);//pred
			$daa= $da->getTimestamp();
			$dac= new DateTime('2016-02-01');//po
			$daca= $dac->getTimestamp();
		}
		if ($this->_rx) return  (preg_match($this->_rx, $inner->getFileName()) && ($daa>filemtime($inner->getPathname())) && ($daca<filemtime($inner->getPathname())));
		return true;
	}
	public function key() {
		return $this->getInnerIterator()->getPathname();
	}
}
?>