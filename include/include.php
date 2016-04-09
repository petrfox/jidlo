<?php
defined('_TOSNIS') or die("robot");

$istab=$dbModel::getResult("SHOW TABLES LIKE 'zakaznici'");
$x=$istab->fetch();
if(!isset($x[0])) $dbModel::getResult("CREATE TABLE zakaznici (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
datprvobj TIMESTAMP NOT NULL,
mail VARCHAR(60) NOT NULL UNIQUE,
osoba VARCHAR(100) NOT NULL,
telefon VARCHAR(15) NOT NULL,
ulice VARCHAR(100) NOT NULL,
cp VARCHAR(10) NOT NULL,
ctvrt VARCHAR(5) NOT NULL     
)");




$istab=$dbModel::getResult("SHOW TABLES LIKE 'kategorie'");
$x=$istab->fetch();
if(!isset($x[0])) $dbModel::getResult("CREATE TABLE kategorie (
id int(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
poradi INT(6) UNSIGNED DEFAULT 1,
zap BOOL DEFAULT TRUE,
nazev VARCHAR(30) NOT NULL
) ");

$istab=$dbModel::getResult("SHOW TABLES LIKE 'polozka'");
$x=$istab->fetch();
if(!isset($x[0])) $dbModel::getResult("CREATE TABLE polozka (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
kat_id INT(6) UNSIGNED NOT NULL,
poradi INT(6) UNSIGNED DEFAULT 1,
zap BOOL DEFAULT TRUE,  
nazev VARCHAR(500) NOT NULL,
cena FLOAT NOT NULL,
objem FLOAT NOT NULL DEFAULT 0.45,
malacena FLOAT DEFAULT 35,
malyobjem FLOAT NOT NULL DEFAULT 0.25,
od DATE NOT NULL,
do DATE, 
INDEX par_ind (kat_id),
    FOREIGN KEY (kat_id) 
        REFERENCES kategorie(id)      
)");


$istab=$dbModel::getResult("SHOW TABLES LIKE 'objednavky'");
$x=$istab->fetch();
if(!isset($x[0])) $dbModel::getResult("CREATE TABLE objednavky (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
pol_id INT(6) UNSIGNED NOT NULL,
nazev VARCHAR(500) NOT NULL,
cena FLOAT NOT NULL,
pocet INT(5) NOT NULL,
porce VARCHAR(5) NOT NULL DEFAULT 'velka',
dat DATETIME NOT NULL,
ondate DATE NOT NULL,
zak_id INT(6) UNSIGNED NOT NULL,
    FOREIGN KEY (pol_id) 
        REFERENCES polozka(id),
		FOREIGN KEY (zak_id) 
        REFERENCES zakaznici(id)      
)");




$istab=$dbModel::getResult("SHOW TABLES LIKE 'config'");
$x=$istab->fetch();
if(!isset($x[0])) $dbModel::getResult("CREATE TABLE config (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
nazev VARCHAR(20) NOT NULL,
hodnota VARCHAR(500) NOT NULL)
");

$istab=$dbModel::getResult("SHOW TABLES LIKE 'svatky'");
$x=$istab->fetch();
if(!isset($x[0])) $dbModel::getResult("CREATE TABLE svatky (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
svatek DATE NOT NULL)
");

$istab=$dbModel::getResult("SHOW TABLES LIKE 'pozn_pod_kat'");
$x=$istab->fetch();
if(!isset($x[0])) $dbModel::getResult("CREATE TABLE pozn_pod_kat (
id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
kat_id INT(6) UNSIGNED NOT NULL,
pozn VARCHAR(500),
zap BOOL DEFAULT TRUE, 
INDEX par_ind (kat_id),
    FOREIGN KEY (kat_id) 
        REFERENCES kategorie(id)    )
");
?>