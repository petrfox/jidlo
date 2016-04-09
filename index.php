<?php
define('_TOSNIS',"1");
include 'model.php';


?><!DOCTYPE html>
<html lang="cs">
  <head>
    <meta charset="utf-8">
    <title>Nabídka menu tosnis.cz</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="Petr Liška">

    <meta name="copyright" content="petrhk@volny.cz">

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">
    <link href="css/font-awesome.min.css" rel="stylesheet">      
    <link href="css/bootstrap-clockpicker.min.css" rel="stylesheet"> 
    <link rel="apple-touch-icon" href="img/apple-touch-icon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="img/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="img/iconsapple-touch-icon-114x114.png">
 
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="jscript/html5shiv.min.js"></script>
      <script src="jscript/respond.min.js"></script>
    <![endif]-->
  </head>
<body>
<form  id="tosnisform" data-target="post" role="form">
	<div class="container" id="telo">
		<h3 class="page-header"> <i class="fa fa-icon-food"></i> Objednávka denního menu - tosnis.cz </h3>
	  <fieldset class="oranz well">
			<div class="row">       
				<div class="col-xs-12 col-md-4">  
				  <div class="form-group has-warning has-feedback">
				    <label class="control-label" for="mail">Email</label>
				    <input type="mail" class="email form-control" id="mail" name="mail"  data-required="required" required >
				    <span class="fa fa-warning form-control-feedback" aria-hidden="true"></span>
				  </div>
				</div>
				<div class="col-xs-12 col-md-5">
				  <div class="form-group has-warning has-feedback">
				    <label class="control-label" for="osoba">Jméno (případně i firma)</label>
				    <input type="text" class="form-control" id="osoba" name="osoba" data-required="required" required>
				    <span class="fa fa-warning form-control-feedback" aria-hidden="true"></span>
				  </div>
				</div>  
	
				<div class="col-xs-12 col-md-3">  
				  <div class="form-group has-warning has-feedback">
				    <label class="control-label" for="telefon">Telefon</label>
				    <input type="tel" class="form-control" id="telefon" name="telefon" data-required="required" required>
				    <span class="fa fa-warning form-control-feedback" aria-hidden="true"></span>
				  </div>
				</div>
			</div>
					  
			<div class="row ">    
				<div class="col-xs-12 col-md-3">  
				  <div class="form-group has-warning has-feedback">
				    <label class="control-label" for="ulice">Ulice</label>
				    <input type="text" class="form-control" name="ulice" id="ulice" data-input="ulice" data-required="required" required>
				    <span class="fa fa-warning form-control-feedback" aria-hidden="true"></span>
				  </div>  
				</div>
				<div class="col-xs-6 col-md-2">  			
				  <div class="form-group has-warning has-feedback">
				    <label class="control-label" for="cp">Č.p.</label>
				    <input type="text" class="form-control" id="cp" data-required="required" required>
						<span class="fa fa-warning form-control-feedback" aria-hidden="true"></span> 
				  </div> 
				</div>
				<div class="col-xs-6 col-md-2">  			
				  <div class="form-group has-warning has-feedback">
				    <label class="control-label" for="ctvrt">Praha číslo</label>
				    <input type="number"  min="2" max="50"  class="form-control" id="ctvrt" data-required="required" required>
						<span class="fa fa-warning form-control-feedback" aria-hidden="false"></span> 
				  </div> 
				</div>   

				<div class="col-xs-12 col-md-5">  			
				  <div class="form-group">
				    <label for="ctvrt">Poznámka</label>
				    <input type="text"  class="form-control" id="poz" > 
				  </div>
				</div> 
			</div> 
			<div class="row ">
				<div class="col-xs-12 col-md-9 text-left">
					 Až na poznámku je nutné vyplnit všechny údaje pro objednání. Objednávky nejpozději do <?php
echo $objDoHod;
?>:00 předcházejícího dne. Menu na další týden dostupné od čtvrtka <?php
echo $menuodhodiny;
?>:00.<br>
				</div> 
				<div class="col-xs-12 col-md-3 text-right">
					 Hotline 9-19 hod: <strong><?php
							echo $hotline;
						?></strong>
				</div> 
			</div>    
		</fieldset> 
		<br />
					<?php
echo $menuli;
?>	
			<fieldset class="zel well">
			<?php
				echo $obsah->out;
			?>

	 		<?php
				echo $hi;
				echo $podpis;
				echo $podek;
				echo $rekl; 
				echo $telmenu;
				echo $minobjnaden;
				echo $doprzdarmaod;	
				echo $cenadopravy;

			?>
			<input type="text" class="hidden" id="age" name="age">
			<input type="text" class="hidden" id="checkv" name="checkv">
		
		</fieldset>
		<fieldset>
			<div class="row">
				<div class="col-xs-12 col-md-12 text-center"> Nejprve <a  class="btn btn-success" href="#pata" id="obj" name="obj">zkontrolujte</a>
			 a pak <a  class="btn btn-danger" id="mailme">odešlete</a>  objednávku
			 </div>
			</div> 













	    <div class="row">
				<br><div class="col-xs-12 col-md-12" id="thanks"><p class="well">Objednávat můžete najednou na několik dní. Objednávku pro kontrolu zasíláme i na Vámi uvedený e-mail. 
				Pokud se zde po odeslání nezobrazí potvrzení objednávky, prosíme volejte.</p>
				</div>
			</div>

		</fieldset>

  	<fieldset class="well"> <div id="pata"></div> 		
		</fieldset>
  </div>
  
</form>
<footer role="contentinfo" class="hidden-print">
     <div class="footerx"><div class="container"> 
             <div class="row">		<div class="moduletable col-xs-3">
              <p>
               Objednávky na následující den prosím posílejte nejpozději do <?php echo $objDoHod;?> hodin předchozího dne. 
              </p>
              <p>
               Menu na následující týden zvěřejňujeme ve <?php echo $patadennovemenu;?>. 
              </p>
						</div>

			<div class="moduletable col-xs-3">
  Společnost zapsaná v Obchodním rejstříku<br>Spisová značka C 182262 vedená u Městského soudu v Praze<br>IČ 26402335 <br>DIČ CZ26402335<br> &nbsp;
				            

		</div>

	<div class="col-xs-3">
<address>
<div>Sídlo firmy:</div>
<div class="footerheads">Bistro-Catering-kuchyně s.r.o.</div>
<div class="divinaddr"><i class="fa fa-building-o"></i>&nbsp;Mánesova 79 
<div>&nbsp;&nbsp; 120 00 Praha 2-Vinohrady </div>
</div>
</address>
</div>
<div class="col-xs-3">
<div class="divinaddr"><i class="fa fa-mobile-phone"></i> +420 728 033 323<br></div>
<div><i class="fa fa-envelope"></i> <span id="cloak74488"><a href="mailto:pavlatosniscatering@gmail.com">info@tosnis.cz</a></span></div>
<p><i class="fa fa-globe"></i> <a href="http://www.tosnis.cz">www.tosnis.cz</a></p>

<p>Chytré formuláře programuje <br><a href="http://www.newsroom.cz">© newsroom 2016</a></p></div>

</div>
</div>
</div>
</footer> 
    <script src="jscript/jquery-1.10.2.min.js"></script>
    <script src="jscript/bootstrap.min.js"></script>

    <script src="jscript/bootstrap-clockpicker.min.js"></script>

  <script src="jscript/jquery.cookie.js"></script> 
      <script src="jscript/tosnis.js"></script>


  </body>
</html>
