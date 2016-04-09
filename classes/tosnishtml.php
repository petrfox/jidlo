<?php
class Html{
	public $content;
	public $out='';
	public $counterjidlo=0;
	public $counterdatum=0;
	private static $conf;
	function __construct(){
		//self::$conf=$confname;
	}
	public function addNadpis(){
		
	}
	public function closeKat(){
		$this->out=$this->out.'<hr></div> <!-- //row -->';			
	}	
	
	public function addPol($kat,$datum,$polevky){
      $this->counterjidlo++;
      $this->counterdatum++;
			$this->out.= <<<EOT
			<div class="row pol dx-$datum hidden canhide" data-counter="$this->counterdatum">
	
				<h3 class="col-xs-12">
				<span class="katdoobj">$kat</span> <span class="hidden">$datum</span>
				</h3>				      
EOT;
			foreach($polevky as $pol){ 
		  
			$this->out.= <<<EOT
			    <div  class="jednojidlo form-inline" data-counter="$this->counterjidlo">
							<div class="col-md-11 col-md-offset-1 col-xs-12">
								<strong class="nazev">{$pol["nazev"]} </strong><small><em>({$pol["alerg"]})</em></small><br><br>
							</div>
              <div class="col-md-5 col-md-offset-1 col-xs-12">
				        <div class="form-group">
				        	<label for="inpv{$pol["id"]}"><span class="smin10">Porce {$pol["objem"]} l </span><span class="velkacena smin10">{$pol["cena"]}</span><span class="smin10"> Kč</span></label>
									<input min="0" max="50" type="number" 
										data-input="pocetvel" data-bind="bi" data-id="{$pol["id"]}" name = "inpv{$pol["id"]}" 
										class="form-control input-sm velkapol jidlo smin10" 
										data-toggle="tooltip" title="Zadejte počet porcí">	
								</div>
              </div>

							<div class="col-md-6 col-xs-12">
				        <div class="form-group ">
				        	<label for="inpm{$pol["id"]}" ><span class="smin10">Malá porce {$pol["malyobjem"]} l pouze k hlavnímu jídlu </span>
									<span class="malacena smin10">{$pol["malacena"]}</span> <span class="smin10">Kč</span> </label>
									<input min="0" max="50" type="number" data-input="pocetmal" data-bind="bi" data-id="{$pol["id"]}" name = "inpm{$pol["id"]}" 
									class="form-control smin10 input-sm malapol jidlo"
									data-toggle="tooltip" title="Zadejte počet porcí">	
								</div>
							</div>		
				     <!-- </div> //col 12 -->
		      </div>  <!--//idjidla  -->
	  	
EOT;

		
		}
	}	
		
	public function addJid($kat,$datum,$jid){
			$this->counterdatum++; 
			$this->out.= <<<EOT
			<div class="row form-horizontal jid dx-$datum hidden canhide" data-counter="$this->counterdatum">
	
				<h3 class="col-xs-12">
				<span class="katdoobj">$kat</span> <span class="hidden">$datum</span>
				</h3>				      
EOT;

			foreach($jid as $pol){ 
		    $this->counterjidlo++;
				$intin=@$pol["alerg"]? "<small><em>(".$pol["alerg"].")</em></small>":'';
				$tmp= <<<EOT
				    <div  class="jednojidlo" data-counter="$this->counterjidlo">
				      	<div class="col-xs-12"> <!--  -->  
									<div class="col-md-9 col-md-offset-1 col-xs-12">
									<strong class="nazev">{$pol["nazev"]}</strong> {$intin}<br><br>
									</div>					        
									<div class="form-group col-md-1 col-xs-3 ">
					        	<label for="inp{$pol["id"]}" class="sr-only">Porcí</label>
										<input min="0" max="50" type="number" name = "inp{$pol["id"]}" data-bind="bi" data-input="pocet" data-id="{$pol["id"]}" class="form-control input-sm jidlo">	
									</div>
									<div class="col-md-1 col-xs-3 ">
									<span class="cena">{$pol["cena"]}</span> Kč 
									</div>
				      	</div> <!--  -->  
			      </div>  <!--//jednojidlo  -->
EOT;

				$this->out.= $tmp;
		
			}

	}	


	public function addPoz($po){
				$this->out.= <<<EOT
					<div class="row">
					<div class="col-xs-12">&nbsp;</div>
					<div class="col-md-8 col-md-offset-2 col-xs-8 col-xs-offset-2 alert alert-success small" role="alert">{$po['pozn']}</div> </div>
EOT;

	}
	public function addHidInp($id,$val){
			
				return <<<EOT
					<input type="hidden" id="$id" value="$val">	
					
EOT;

	}
	public function addInp($id,$val,$class){
			
				return <<<EOT
					<input type="text" id="$id" value="$val" class="$class">	
					
EOT;

	}
	public function getMenu($days,$minobjnaden,$doprzdarmaod,$cenadop){
		
			$tmp= <<<EOT
				<div class="row">
					<div class="col-md-1 col-xs-12">Vyberte den:</div><div class="col-md-8 col-xs-12">
						<div class="dateinput">
							<div class="btn-group" data-toggle="buttons">
EOT;
			$flag=true;
			foreach($days as $d){
				$t=$flag?'active':'';
				$h=$flag?'checked':'';
				$den = EuroDateTime::czechDay($d->format('N'),'krVel');
				$den= $den.' '.$d->format('j. n.');
				$dd=$d->format('Y-m-d');
				$tmp.= <<<EOT
				<label class="btn $t">
          <input  type="radio" name="datumy" value="$dd" class="radiodat" data-den="$dd" data-d="dat" $h>
					<i class="fa fa-circle-o fa-2x"></i><i class="fa fa-check-circle-o fa-2x"></i>
					<span> $den</span>
        </label>				
EOT;
				$flag=false;	
			}
			
			
			$tmp.= <<<EOT
				</div></div></div><div class="col-md-3 col-xs-12 small">Min. objednávka na den $minobjnaden Kč s dopravou za $cenadop Kč.<br>Doprava zdarma nad $doprzdarmaod Kč/den </div></div>
EOT;
			return $tmp;
	}

	public function addTimePicker($dat,$dorucenido,$mincas,$maxcas){
	  $f=new DateTime($dat);
	  $ff=$f->format('j. n.');
		$this->counterdatum++; 
		$this->out.= <<<EOT
			<div class="row form-horizontal clock dx-$dat hidden canhide small form-group" data-counter="$this->counterdatum" >
					<hr>
					<div class="col-md-4 col-md-offset-4 well">Zadávejte čas od $mincas do $maxcas.<br> Doručíme $ff do $dorucenido min. od zadaného času.<br> Před příjezdem kurýr zavolá.
						
							<div class="pickerinp" >
									<div class="input-group clockpicker-with-callbacks" data-placement='top'
		    data-align= 'left'
		    data-donetext= 'Vložit'>
		    							
											<input type="text" class="form-control timepicker" value="11:30" date-mincas="$mincas" date-maxcas="$maxcas">
							    		<span class="input-group-addon">
							        <span class="fa fa-clock-o"></span>
							    		</span>
									</div>
				     </div>
					</div>

		</div>
EOT;

		}
}
?>   
