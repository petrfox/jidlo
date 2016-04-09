<?php
//header('Content-type: text/html');
header('Content-type: text/html');
iconv_set_encoding("internal_encoding", "UTF-8");
date_default_timezone_set('Europe/Prague');
define('ONE','objednávka neodeslána');
require 'classes/phpmailer/phpmailer.php';
require 'classes/phpmailer/smtp.php';
require 'classes/tosniscls.php';

$inp=file_get_contents('php://input');
// file_put_contents('debuga.txt',$inp,FILE_APPEND);
$inp=JSON_decode($inp);

if($inp === NULL || $_SESSION["check"]!= 'foxes'){
		exit('~#Chyba systému, '.ONE);  
}else{
	 if (!isset($inp->{"mail"}) || (!filter_var($inp->{"mail"}, FILTER_VALIDATE_EMAIL))||($inp->{"age"}!=="")|| ($inp->{"checkv"}!='28'))exit('~#Systém se domnívá, že jde o robota, '.ONE);
}

//konfigurace
if ($_SERVER["SERVER_NAME"]=='localhost') {
$dbcnf = array("host"=>"localhost","name"=>"tosnis","user"=>"fox","pass"=>"foxovo");
}else{
$dbcnf = array("host"=>"127.0.0.1","name"=>"tosniscz1","user"=>"tosnis.cz","pass"=>"foxovo456");
}

Tovarna::setDb("MySQL",$dbcnf);
$dbModel = Tovarna::getDb();

$d=$dbModel::getResult("SELECT id,nazev,cena,malacena FROM polozky");

foreach($d->fetchall() as $poind){
	$menu[$poind['id']]=array('nazev' => $poind['nazev'],'cena' => $poind['cena'],'malacena' => $poind['malacena']);
}
if (!isset($menu)) exit('~#Chyba přístupu k databázi jídel, '.ONE);


$DateObj= new EuroDateTime();
$dat = $DateObj->format('Y-m-d H:i:s');
//filter
function rekurFilter($in){
		if(is_array($in))return array_map('rekurFilter',$in);
		return htmlspecialchars(trim($in),ENT_COMPAT | ENT_HTML5,'UTF-8');
}
$inp = array_map('rekurFilter',$inp);
$inp->{"mail"} = strtolower($inp->{'mail'});
$d=$dbModel::getResult("SELECT id FROM zakaznici WHERE mail = '".$inp->{"mail"}."'");
$id=$d->fetch(PDO::FETCH_ASSOC);
if (!$id){
	$rowsToInsert[] = array(
			    'mail' => $inp->{'mail'},
			    'osoba' => $inp->{'osoba'},
			    'telefon'=>$inp->{'telefon'},
			    'ulice'=>$inp->{'ulice'},
			    'cp'=>$inp->{'cp'},
			    'ctvrt'=>$inp->{'ctvrt'},
	);	
	$dbModel::pdoMultiInsert('zakaznici', $rowsToInsert);
	$d=$dbModel::getResult("SELECT id FROM zakaznici WHERE mail = '".$inp->{"mail"}."'");
	$id=$d->fetch(PDO::FETCH_ASSOC);
}
unset($rowsToInsert);
$id = $id['id']; 
foreach ($inp['inputs'] as $datkey=>$arrval){//pole s datumovym indexem
	foreach ($arrval as $id=>$poc){//pole s jednim clenem - index = id jidla
		$cena = $menu[$id]['cena'];
		if(isset($poc->{'pocetmal'})){
			$porce = 'mala';
			$pocet = $poc->{'pocetmal'};
			$cena = $menu[$id]['malacena'];            
		}elseif(isset($poc->{'pocetvel'})){
			$porce = 'velka';
			$pocet = $poc->{'pocetvel'};
		}else{
			$porce = 'velka';
			$pocet = $poc->{'pocet'};	
		}
		$rowsToInsert[] = array(
				    'zak_id' => $id,
				    'porce'=>$porce,
				    'pocet'=>$pocet,
				    'nazev'=>$menu[$id]['nazev'],
				    'cena'=>$cena,
				    'dat'=>$dat,
				    'ondate'=>$datkey,
		);	
	}
}
$dbModel::pdoMultiInsert('objednavka', $rowsToInsert);


// nl to \r\n
$outstr=	'';	
foreach ($inp->{'dat'} as $val){
	if ($val=="nl") {
		$outstr.=	"\r\n";
	}else{
	  $outstr.=	$val."\r\n";
	}	
}
//konfigurace tela mailu
$outstr.=	"\r\n\r\n";
$outstr.=	$inp->{"mailpodekovani"}."\r\n\r\n";
$outstr.=	$inp->{"mailpodpis"}."\r\n\r\n"; 
$outstr.=	$inp->{"mailkontaktmenu"}."\r\n"; 
$outstr.=	"--------------------------------------------------------\r\n";
$outstr.=	$inp->{"mailreklama"};  

// file_put_contents('debuga.txt',"-----------budeoutrstr------------\n",FILE_APPEND);
// file_put_contents('debuga.txt',$outstr,FILE_APPEND);
// file_put_contents('debuga.txt',"-----------byloutrstr------------\n",FILE_APPEND);


$mail = new PHPMailer();
//$mail->SetLanguage('cz',dirname(__FILE__) . '/classes/language/');
$mail->CharSet = "iso-8859-2";
$mail->Encoding = "quoted-printable";
$mail->Subject =  iconv('UTF-8', 'ISO-8859-2', 'Objednávka menu tosnis.cz');
//$mail->addAddress('pavlatosniscatering@gmail.com', '');
//$mail->addAddress('petrhk@volny.cz', '');
$mail->addAddress('p.vojancova@seznam.cz', '');

$mail->AddBCC($inp->{"mail"}, '');



/****************************************************************************/


$mail->setFrom('systemtosnis@gmail.com', 'Systém tosnis.cz, neodpovídat');
$mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 0;

$mail->Host = 'smtp.gmail.com';
$mail->Port = 587;
$mail->SMTPSecure = 'tls';
$mail->SMTPAuth = true;
$mail->Username = "systemtosnis@gmail.com";
$mail->Password = "foxovo456";
$mail->addReplyTo(iconv('UTF-8', 'ISO-8859-2', $inp->{"mail"}), iconv('UTF-8', 'ISO-8859-2', $inp->{"osoba"}));

//Set an alternative reply-to address
//$mail->addReplyTo('replyto@example.com', 'First Last');

//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
//$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
//Replace the plain text body with one created manually
//$mail->AltBody = 'This is a plain-text message body';
//$vals['mailtext'] = str_replace("\r\n", "\n", $vals['mailtext']);
//$mailBody=wordwrap($mailBody, 70, "\r\n");
$outstrconv=iconv('UTF-8', 'ISO-8859-2',$outstr);
$mail->Body = $outstrconv;
// file_put_contents('debuga.txt',"-----------------------\n",FILE_APPEND);
// file_put_contents('debuga.txt',$outstrconv,FILE_APPEND);
/******************************************************************************/

//poslani mailu
if (!$mail->send()) {
    $msg= "~#Chyba systému při odeslání mailu: ";
} else {
    $msg='Děkujeme za objednávku. Byla odeslána do systému a v kopii na Váš e-mail.';
}
exit($msg);
 




?>