<?php
defined('_TOSNIS') or die("robot");
define('VYVOJ',true);
require "classes/tosniscls.php";
require "classes/tosnishtml.php";
if ($_SERVER["SERVER_NAME"]=='localhost') {
$dbcnf = array("host"=>"localhost","name"=>"tosnis","user"=>"fox","pass"=>"foxovo");
}else{
$dbcnf = array("host"=>"127.0.0.1","name"=>"tosniscz1","user"=>"tosnis.cz","pass"=>"foxovo456");

}
session_start();
$_SESSION["check"]= 'foxes';
Tovarna::setDb("MySQL",$dbcnf);
$dbModel = Tovarna::getDb();
include "include/include.php";
$cnf=Tovarna::getConfig("MySQL");
$k = Tovarna::dejKalendar($cnf);
$dny = $k->nastavStartStop()->nastavDny()->odeberVolDny()->dejKalendar();
$obsah=new HTML();

$kat=$dbModel->getResult("SELECT kat.id AS id, kat.nazev AS nazev 
FROM kategorie AS kat where zap = true ORDER BY kat.poradi
");

$db= $kat->fetchall();
//var_dump($db);
//where zap = true 
$obsah->out='';
foreach ($dny as &$dnyvalue) {
	$dat=$dnyvalue->format('Y-m-d');
	foreach($db as $keykat=>$katval){
		$jid=$dbModel->getResult("SELECT pol.id, 
												kat.nazev AS kateg, 
												pol.nazev AS nazev, 
												pol.alerg AS alerg,
												pol.cena AS cena,
												pol.objem AS objem,
												pol.malacena AS malacena,
												pol.malyobjem AS malyobjem,
												pol.od AS od,
												pol.do AS do 
														FROM polozka AS pol,kategorie AS kat 
															where pol.kat_id=".$katval['id']." AND
															pol.kat_id=kat.id AND 
															pol.od <= date('".$dat."') AND
															(pol.do >= date('".$dat."') OR pol.do IS NULL) AND
															pol.zap = true ORDER BY kat.poradi,pol.poradi 
		");
		$dba= $jid->fetchall();

		if ($katval['nazev']=='Polévka'){
			$obsah->addPol($katval['nazev'],$dnyvalue->format('Y-m-d'),$dba);
		}else {
			$obsah->addJid($katval['nazev'],$dnyvalue->format('Y-m-d'),$dba);
		}
		$po=$dbModel->getResult("SELECT  
												pozn 
 													FROM pozn_pod_kat 
															where kat_id=".$katval['id']." AND
															zap = true 
		");

    $pozdb= $po->fetchall();
		if(!empty($pozdb)){
			foreach($pozdb as $keyp=>$pval){
				$obsah->addPoz($pval);	
			}
		}
		$obsah->closeKat();
	} //endforeach
	$obsah->addTimePicker($dat,$cnf->getVal('dorucenido'),$cnf->getVal('mincas'),$cnf->getVal('maxcas'));
}

$menuli= $obsah->getMenu($dny,$cnf->getVal('minobjnaden'),$cnf->getVal('doprzdarmaod'),$cnf->getVal('cenadopravy'));
$menuodhodiny= $cnf->getVal('menuodhodiny');
$objDoHod= $cnf->getVal('objDoHod');
$hi= $obsah->addHidInp('startdate',$k->startMenu->format('Y-m-d'));
$podpis= $obsah->addHidInp('mailpodpis',$cnf->getVal('mailpodpis'));
$podek= $obsah->addHidInp('mailpodekovani',$cnf->getVal('mailpodekovani'));
$rekl= $obsah->addHidInp('mailreklama',$cnf->getVal('mailreklama'));
$telmenu= $obsah->addHidInp('mailkontaktmenu',$cnf->getVal('mailkontaktmenu'));
$minobjnaden= $obsah->addHidInp('minobjnaden',$cnf->getVal('minobjnaden'));
$doprzdarmaod= $obsah->addHidInp('doprzdarmaod',$cnf->getVal('doprzdarmaod'));
$cenadopravy= $obsah->addHidInp('cenadopravy',$cnf->getVal('cenadopravy'));
$hotline= $cnf->getVal('dispecertelef');
$patadennovemenu=EuroDateTime::czechDay($cnf->getVal('objNaslTyd'),'celeMal6p');


?>