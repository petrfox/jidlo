<?php
class EuroDateTime extends DateTime {

	// Override "modify()" http://stackoverflow.com/questions/13128854/php-datetime-class-change-first-day-of-the-week-to-monday
	public function modify($string) {
	
	    // Change the modifier string if needed
	    if ( $this->format('N') == 7 ) { // It's Sunday and we're calculating a day using relative weeks
	        $matches = array();
	        $pattern = '/this week|next week|previous week|last week/i';
	        if ( preg_match( $pattern, $string, $matches )) {
	            $string = str_replace($matches[0], '-7 days '.$matches[0], $string);
	        }
	    }
	    return parent::modify($string);
	}
	public static function czechDay($dayNumber,$type){

		if(is_numeric ($dayNumber) && $dayNumber<8 && $dayNumber>0){

			$d=new stdClass();
			$d->krVel = array('Po', 'Út', 'St', 'Čt', 'Pá', 'So','Ne');
			$d->krMal = array('po', 'út', 'st', 'čt', 'pá', 'so','ne');
			$d->celeVel = array('Pondělí', 'Úterý', 'Středa', 'Čtvrtek', 'Pátek', 'Sobota','Neděle');
			$d->celeMal = array('pondělí', 'úterý', 'středa', 'čtvrtek', 'pátek', 'sobota','neděle');
			$d->celeMal2p = array('pondělí', 'úterý', 'středy', 'čtvrtka', 'pátku', 'soboty','neděle') ;
			$d->celeMal6p = array('pondělí', 'úterý', 'středu', 'čtvrtek', 'pátek', 'sobotu','neděli');
			if (property_exists($d, $type)){return $d->{$type}[$dayNumber-1];
			}else{return false;
			}
		}else{
			return false;
		}
			
	}            
}
class Dat extends DateTime {
	static function getDate($date=""){
		if (empty($date)) {
			$parrent->__construct();
		}else{
// 		HOW TO:
// 		$date=Application::DateObj("2010-04-28");
// 		echo $date->format('d. m. Y').'<br>';
// 		$date->add(new DateInterval('P10Y')); P is period 
// 		echo $date->format('d. m. Y').'<br>';
//		$date->modify('- 1 year');
//		$date->modify('- 6 month');

			if (strpos($date,'.')){
				$date=str_replace (' ', '' ,$date);
			}
			$date=trim($date);
			if (empty($date)) $parrent->__construct();
			if (strpos($date,' ')){
					$format=	'Y-m-d H:i:s';
					$pat="~(\d{2}|\d{4})(?:\-)?([0]{1}\d{1}|[1]{1}[0-2]{1})(?:\-)?([0-2]{1}\d{1}|[3]{1}[0-1]{1})(?:\s)?([0-1]{1}\d{1}|[2]{1}[0-3]{1})(?::)?([0-5]{1}\d{1})(?::)?([0-5]{1}\d{1})~";	
				} elseif(strpos($date,'.') and count(explode('.',$date))==2) { 
		 			$format=	'm.Y';
		 			$pat="~[01]?[0-9]\.[0-9]{2,4}~";
				} elseif(strpos($date,'.') and count(explode('.',$date))==3) {
					$format=	'd.m.Y';
					$pat="~^(0?[1-9]|[12][0-9]|3[01])[\.](0[1-9]|1[012])[\.](19|20)\d\d$~";
				} elseif(strpos($date,'/')){
					$format=	'm/d/Y';
					$pat="~^((((0[1-9])|(1[0-2]))[\/]?(([0-2][0-9])|(3[01])))|(((0[469])|(11))[\/]?(([0-2][0-9])|(30)))|(02[\/]?[0-2][0-9]))[\/]?\d{4}$~";
				} elseif(strpos($date,'-')){
					$format=	'Y-m-d';
					$pat="~^[0-9]{4}-(((0[1-9]|(10|12))-(0[1-9]|[1-2][0-9]|3[0-1]))|(02-(0[1-9]|[1-2][0-9]))|((0[469]|11)-(0[1-9]|[1-2][0-9]|30)))$~";
				} else {
				return null;
			}
			if(!preg_match($pat, $date))return null;
	
	 		try {
	    	$d = DateTime::createFromFormat($format, $date);
	    		return $d;
				} catch (Exception $e) {
	    		return null;
			}
		
			
		}
	}
}	

class Config {
	static $conf;
	function __construct($typ){
		$dbObj=new $typ; 
		$tmp=$dbObj::getResult("SELECT * FROM config");
		foreach ($tmp as $value) {
			self::$conf[$value['nazev']]=$value['hodnota'];
		}
	}
	public function getVal($what){
		try {
				return self::$conf[$what];
		} catch (PDOException $e){
				exit ("neexistující konf. proměnná");
		}
	}
}


class Kalendar{
	private static $cnf; //config obj
	public $today; //DateTime obj
	public $startMenu; //DateTime obj
	public $stopMenu;  //DateTime obj
	public $dny = array();  //array of DateTime obj
	function __construct($confObj){
		self::$cnf=$confObj;	
	}
	//prvni a posledni datum zobrazovani menu
	public function nastavStartStop(){
		if (VYVOJ){
			if (isset($_GET["start"])){
				$a = htmlspecialchars(@$_GET["start"]);
				if (!empty($a))  $this->today = EuroDateTime::createFromFormat('Y-m-d:H', $a);
			}
		} if (!isset($this->today)) $this->today= new EuroDateTime();
		$this->startMenu=clone $this->today;

	
		//do objDoHod ukazuje nasledujici den, pak o den pozdeji
		if($this->today->format('H') <  self::$cnf->getVal('objDoHod')){
			$this->startMenu->modify('+1 day');
			}else{$this->startMenu->modify('+2 day');
		}
		//preskoci so a ne a ukazuje az pondeli
		if ($this->startMenu->format('N')>5){
			$this->startMenu = $this->startMenu->modify('next Monday');
		}
		$this->stopMenu = clone $this->startMenu;

		//kdyz aktualni cas prekroci menuodhodiny (hh:mm)a den bude dnem zverejneni menu na nasledujici tyden objNaslTyd (int)
    $datenewmenu=new EuroDateTime();
    $dclone=clone $datenewmenu;

		if (((int)$datenewmenu->format('N') >= (int)self::$cnf->getVal('objNaslTyd')) && 
		($datenewmenu > $dclone->modify(self::$cnf->getVal('menuodhodiny').':00')) &&
		($this->startMenu->format('W')==$this->today->format('W'))){
			$this->stopMenu = $this->stopMenu->modify('Friday next week');	
		} else{
			$this->stopMenu = $this->stopMenu->modify('Friday this week');
		}

		return $this;
	}
	//vyhodi so a ne
	public function nastavDny(){
		$d=clone $this->startMenu;

		while($d->diff($this->stopMenu)->format('%R%a') >= 0){
			
			if ($d->format('N')>=1 && $d->format('N')<=5){
				$e=clone $d;
				array_push($this->dny,$e);
			}  
			$d->modify('+1 day');
		}
		return $this;
	} 
	public function odeberVolDny(){
		return $this;
	}
	public function dejKalendar(){

		return $this->dny; // array DateTime obj.; 
	}
}

class Autent{
  public $authWay=INPUT_GET;
	public $authNameName='jm';
	function __construct(){
	}
	public function checkUser($us){
		if (session_status () == PHP_SESSION_DISABLED) session_start();
		if(isset($_SESSION[$authNameName])) {
			if ($_SESSION[$authNameName]===$us) return true;
		}elseif ($usget === filter_input($authWay, $authNameName)){
			if ($usget===$us){
				$_SESSION[$authNameName]=$us;
				return true;
			}
			return false;
		}else{
			return false;
		}
	}
	
	//http://stackoverflow.com/questions/18260537/how-to-check-if-the-request-is-an-ajax-request-with-php
	public function set($optional_salt='')
	{
	    return hash_hmac('sha256', session_id().$optional_salt, date("YmdG").'fox'.$_SERVER['REMOTE_ADDR']);
	}
	//$_SESSION['current_page'] = $_SERVER['SCRIPT_NAME'];	
	
	
}


class MySQL{
	private static $params;
	private static $services;
	public static function setDbParams($dbparametry){
    self::$params = $dbparametry;
    self::$params['options']=array(
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
  }
  private static function createConnection(){
		try{	self::$services['connection']= new PDO(
	                      "mysql:host=".self::$params['host'].";dbname=".self::$params['name'].";charset=utf8",
	                      self::$params['user'],
	                      self::$params['pass'],
	                      self::$params['options'] );

				return self::$services['connection'];
		} catch (PDOException $e) {
				exit("Chyba připojení k databázi!");
		}	
	       
	}
  private static function getConnection(){
          if (!isset(self::$services['connection']))self::$services['connection'] = self::createConnection();
          return self::$services['connection'];
  }		
  public function getResult($query){
          $con = self::getConnection();
					try{
					return $con->query($query);
					} catch (PDOException $e) {

					if(VYVOJ)exit("Chyba getResult!".$query);
					exit("Chyba getResult!");
					}	
  }
	public function getColNames($tablename){
          $con = self::getConnection();
					try{
						$tmp= $con->query('DESCRIBE '.$tablename);
					return $tmp->fetchAll(PDO::FETCH_COLUMN);
					} catch (PDOException $e) {
					if(VYVOJ)exit("Chyba getColNames!".$query);
					exit("Chyba getColNames!");
  				}
	}
	public function tableExists($tablename){
          $con = self::getConnection();
					$istab=self::getResult("SHOW TABLES LIKE '".$tablename."'");
					$x=$istab->fetch();
					return isset($x[0]); 
	}

	public function pdoMultiInsert($tableName, $data){
//http://thisinterestsme.com/pdo-prepared-multi-inserts/
// 			$rowsToInsert = array(
// 			    array(
// 			        'name' => 'John Doe',
// 			        'dob' => '1993-01-04',
// 			    ),
// 			    array(
// 			        'name' => 'Jane Doe',
// 			        'dob' => '1987-06-14',
// 			    ),
// 			    array(
// 			        'name' => 'Joe Bloggs',
// 			        'dob' => '1989-09-29',
// 			    )
// 			);
// 			 
// 			//An example of adding to our "rows" array on the fly.
// 			$rowsToInsert[] = array(
// 			    'name' => 'Patrick Simmons',
// 			    'dob' => '1972-11-12'
// 			);
// 			 
// 			//Call our custom function.
// 			pdoMultiInsert('people', $rowsToInsert);

	    
	    //Will contain SQL snippets.
	    $rowsSQL = array();
	 
	    //Will contain the values that we need to bind.
	    $toBind = array();
	    
	    //Get a list of column names to use in the SQL statement.
	    $columnNames = array_keys($data[0]);
	 
	    //Loop through our $data array.
	    foreach($data as $arrayIndex => $row){
	        $params = array();
	        foreach($row as $columnName => $columnValue){
	            $param = ":" . $columnName . $arrayIndex;
	            $params[] = $param;
	            $toBind[$param] = $columnValue; 
	        }
	        $rowsSQL[] = "(" . implode(", ", $params) . ")";
	    }
	 
	    //Construct our SQL statement
	    $sql = "INSERT INTO `$tableName` (" . implode(", ", $columnNames) . ") VALUES " . implode(", ", $rowsSQL);
	 
	    //Prepare our PDO statement.
	    $pd=self::getConnection();
			$pdoStatement = $pd->prepare($sql);
	 
	    //Bind our values.
	    foreach($toBind as $param => $val){
	        $pdoStatement->bindValue($param, $val);
	    }
			try{
				return $pdoStatement->execute();
			} catch (PDOException $e) {
			if(VYVOJ)exit("Chyba pdoMultiInsert!".$query);
			exit("Chyba pdoMultiInsert!");
			}


	    
	    //Execute our statement (i.e. insert the data).
	    
	}




}		

  


class Tovarna
{       private static $single=array();
        public static function getConfig($typ){
					if (!isset(self::$single['config'][$typ]))self::$single['config'][$typ]=new Config($typ);
					return self::$single['config'][$typ];
        }  
        public static function setDB($typ,$dbparametry){
						$typ::setDbParams($dbparametry);
						self::$single['db']=new $typ; 
        }
        public static function getDB(){
					if (!isset(self::$single['db']))return null;
					return self::$single['db'];
        }

//         public static function getResult($sql){
// 					if (!isset(self::$single['db']))return null;
// 					$tmp=self::$single['db'];
// 					return $tmp::getResult($sql);
//         }
        public static function dejJidlo(){
								return new Jidlo();
        }
        public static function dejKalendar($cf){
								return new Kalendar($cf);
        }
				public static function dejoneAutent($us){
								return new oneAutent($us);
        }
				  
}






// 	static function dSQLfull($DateObj){
// 		return $DateObj->format('Y-m-d H:i:s'); 
// 	}
// 	static function dSQLdate($DateObj){
// 		return $DateObj->format('Y-m-d'); 
// 	}
// 	static function dCzechSpaceDate($DateObj){
// 		return $DateObj->format('j. n. Y'); 
// 	}
// 	static function dCzechDate($DateObj){
// 		return $DateObj->format('j.n.Y'); 
// 	}
// 	static function dCzechSpaceMY($DateObj){
// 		return $DateObj->format('n. Y'); 
// 	}
// 	static function dCzechMY($DateObj){
// 		return $DateObj->format('n.Y'); 
// 	}		
// 	static function dSQLtimestamp($DateObj){
// 		return $DateObj->getTimestamp();
// 	}
//  if ($_SERVER["SERVER_NAME"]=='localhost') {
// $dbcnf = array("host"=>"localhost","name"=>"tosnis","user"=>"fox","pass"=>"foxovo");
// }else{
// $dbcnf = array("host"=>"127.0.0.1","name"=>"tosniscz1","user"=>"tosnis.cz","pass"=>"foxovo456");
// 
// }
// 
// Tovarna::setDb("MySQL",$dbcnf);
// $dbModel = Tovarna::getDb();
// $rowsToInsert[] = array(
// 			    'pol_id' => 1541,
// 			    'nazev'=>'na',
// 			    'alerg'=>'al',
// 			    'cena'=>15,
// 			    'pocet'=>2,
// 			    'dat'=>'2016-02-06'
// 			);
// 			 
// 			//Call our custom function.
// 			$dbModel->pdoMultiInsert('objednavky', $rowsToInsert);

// if ($_SERVER["SERVER_NAME"]=='localhost') {
// $dbcnf = array("host"=>"localhost","name"=>"tosnis","user"=>"fox","pass"=>"foxovo");
// }else{
// $dbcnf = array("host"=>"127.0.0.1","name"=>"tosniscz1","user"=>"tosnis.cz","pass"=>"foxovo456");
// 
// }
// 
// 			$rowsToInsert = array(
// 			    array(
// 			        'name' => 'John Doe',
// 			        'dob' => '1993-01-04',
// 			    ),
// 			    array(
// 			        'name' => 'Jane Doe',
// 			        'dob' => '1987-06-14',
// 			    ),
// 			    array(
// 			        'name' => 'Joe Bloggs',
// 			        'dob' => '1989-09-29',
// 			    )
// 			);
// Tovarna::setDb("MySQL",$dbcnf);
// $dbModel = Tovarna::getDb();
// $dbModel->pdoMultiInsert('pokus', $rowsToInsert);

?>