//C 2016 Ing. Petr Liška petrhk@volny.cz
$(document).ready(function()
{
	
	function getInternetExplorerVersion()
// Returns the version of Internet Explorer or a -1
// (indicating the use of another browser).
	{
    var rv = -1; // Return value assumes failure.
    if (navigator.appName == 'Microsoft Internet Explorer')
    {
    var ua = navigator.userAgent;
    var re = new RegExp("MSIE ([0-9]{1,}[\.0-9]{0,})");
    if (re.exec(ua) != null)
    rv = parseFloat( RegExp.$1 );
    }
    return rv;
	}
	
	

	
	//nastaveni cookies z vyplnenych inputu 
	var setCookieFromInp=function($arinput){
		$arinput.each(function(){
			var tmp=$.trim($(this).val());	
			if (tmp!=''){
				$.cookie($(this).attr('id'),$(this).val());
			}			
		});
	};
	//nastaveni inputu z cookies
	var fillFromCookie=function($arinput){
		$arinput.each(function(){
			var tmp=$(this).attr('id');
			if($.cookie(tmp)!==undefined){
				$(this).val($.cookie(tmp));
			}
		});
	};  
	//kontrola mailu
  var valEmail = function (elem) {
      var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
      var ret = emailReg.test(elem.val());
      return ret;
  };  


  //nastav error ikony a error ohraniceni required inputu
  //nasledna kontrola chyb - prazdne $(this).find('.has-error')
  //$('[data-required="required"]')
	var reqcheck=function(elems){
		var noerror = true;
		elems.each(function(){
			$(this).val($.trim($(this).val()));
			if ($(this).val()===''){
				$(this).siblings('span.form-control-feedback').show();
				$(this).parent('div').addClass('has-warning');
				noerror = false;
			}else{
				$(this).siblings('span.form-control-feedback').hide();
				$(this).parent('div').removeClass('has-warning');
			}
		});
		return noerror; 
	};
    //zkontroluje kolekci elementu v parametru, ktery je prazdny, dostane tridu has-error
//   var checkReq = function(elems){
//       var noerror = true;
// 			elems.each(function(){
//       if ($(this).prop("tagName").toLowerCase()!='textarea') {   
//               if ($(this).val()==''){
//                   $(this).parent('div').addClass('has-error');
//                   noerror = false;
//               }else{
//                   $(this).parent('div').removeClass('has-error');
//               } 
//           }else{
//           if (!$(this).val()){
//                   $(this).parent('div').addClass('has-error');
//                   noerror = false;
//               }else{
//                   $(this).parent('div').removeClass('has-error');
//               }                
//           }
//       });
//       return noerror; 
//   };
	var $loader;
	$loader=$('<div class="overlay-loader" id="loader"><div class="loader"><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div></div>');

	//pocatecni nastaveni	
	var $htmlobj;
	var ajaxa=[];
  var kolikjidcelkem=0;
  var cenajidcelkem=0;
  var zrovnajdedatum=0;
	var necoobjednano=false;
	var defThanks ='<p class="well">Objednávku pro kontrolu zasíláme i na Vámi uvedený e-mail. Pokud se zde po odeslání nezobrazí potvrzení objednávky, prosíme volejte.</p>' ;
  
	//pro IE8 nefunguje CSS modifikace input radio
	if(getInternetExplorerVersion()=='8'){
		$('.dateinput').find('i').remove();
		$('.dateinput').find('label').removeClass('btn');
		$('.dateinput').removeClass('dateinput');
	}
	
	
	
	
	$("#checkv").val('28'); //kontrolni pole formulare
	$('[data-toggle="tooltip"]').tooltip();
  // $('#thanks').empty();
  // $('#thanks').append(defThanks) ;
  //$('#mailme').addClass("disabled");
	$('div.'+'dx-'+$('#startdate').val()).removeClass("hidden");  //zobraz startovni datum
  $('[data-required="required"]').parent('div').removeClass('has-warning');
  $('.form-control-feedback').hide();
	//klikani na input radio s datumy
	$("input[data-d='dat']").change(function(elem) {
		var ch;
		$("input[data-d='dat']").each(function( elem ) {
		  $(this).parent().removeClass('active');
		});
	  $(this).parent().addClass('active');
	  ch=$('input[name="datumy"]:checked').val();
		$('div.canhide').addClass("hidden");
		$('div.'+'dx-'+ch).removeClass("hidden");
	});
  fillFromCookie($('[data-required]'));

  //jakmile zmeni formularova data objednatele, povol tlac odeslat
 	$('[data-required="required"]').change(function(){
		$('#mailme').removeClass("disabled"); 
 	});
 	
 	//jakmile stiskne klavesu v inputu, odstran atributy chyby
	$('[data-required="required"]').keydown(function(){
		$(this).parent('div').removeClass('has-warning');
		$(this).siblings('span.form-control-feedback').hide();
		$('#thanks').empty();
 	});		 		 
// 	$('[data-required="required"]').change(function(){
// 		$('#mailme').removeClass("disabled"); //jakmile zacne vyplnovat formularova data objednatele, povol tlac odeslat 
// 		$('[data-required="required"]').each(function(){
// 			$(this).val($.trim($(this).val()));
// 			if ($(this).val()===''){
// 				$(this).siblings('span.form-control-feedback').show();
// 				$(this).parent('div').addClass('has-error');
// 			}else{
// 				$(this).siblings('span.form-control-feedback').hide();
// 				$(this).parent('div').removeClass('has-error');
// 			}
// 		});
// 	});

	
	
	
	//pri zmene mail inputu kontroluj mail syntax
  $('#mail').change( function() {
			if (!valEmail($(this))) {
				$(this).siblings('span.form-control-feedback').show();
				$(this).parent('div').addClass('has-warning');
			}else{
				$(this).siblings('span.form-control-feedback').hide();
				$(this).parent('div').removeClass('has-warning');
			}
  });


	$('body [data-toggle="tooltip"]').on('shown.bs.tooltip', function () {
		$('body [data-toggle="tooltip"]').tooltip('disable');//vypnuti tooltipu hned po prvnim zobrazeni kterehokoli inputu s tooltipem
	})
	$('input').on('change', function(){ //vypnuti tooltipu hned po prvnim zapsani do kterehokoli inputu
		$('body [data-toggle="tooltip"]').tooltip('disable');
	});
	$('.clockpicker-with-callbacks').clockpicker(); //hodiny
  
  
  
  

	var flagis = false;
	//vysosani dat z formulare a vytvoreni objednavky a retezce pro mail
	var dejDatanew = function(clstr){
		ajaxa=[];
		fordb=[];
		flagis = false;
		var zrovnajdedatum=0;
		var tmpd;
	  $('#thanks').empty();
		$('#thanks').append(defThanks) ;
		$("#fieldsetobj").remove();
		$('#mailme').removeClass("disabled");
		$htmlobj = $('<fieldset id="fieldsetobj"></fieldset>');
		//plneni hlavicky mailu
		ajaxa.push('Rekapitulace objednávky tosnis.cz:'); 
		ajaxa.push('Praha '+$('#ctvrt').val());
		ajaxa.push($('#ulice').val()+' '+$('#cp').val());
		ajaxa.push('Jméno: '+$('#osoba').val());
		ajaxa.push('Tel: '+$('#telefon').val());
		ajaxa.push('e-mail: '+$('#mail').val());
		$('#poz').val($.trim($('#poz').val()));
		if ($('#poz').val()!=='') {
			ajaxa.push('poz: '+$('#poz').val());
		}
		ajaxa.push('nl');
		ajaxa.push('nl');
		$('.radiodat').each(function (){//pres datumy
			var kolikjidden=0;
	    var cenajidden=0;		
			var todat=$(this).data('den');
			//for MSIE8
			
			if(window.navigator.userAgent.indexOf('MSIE ')>0){
    		var msdat=todat.split('-');
    		tmpd = new Date(parseInt(msdat[0]),parseInt(msdat[1])-1,parseInt(msdat[2]));
			}
			else {
				tmpd= new Date(todat);
			}
			//var tmpd= new Date(todat);
			var objcas= $('.dx-'+todat).find('.timepicker').first().val();
			var czdate=tmpd.getDate()+'. '+ (tmpd.getMonth()+1)+'. '+tmpd.getFullYear();
	
			var $datheader;
			var $radekjidel;
			var tomailpart=[];
			var fordbpart=[];
			if((zrovnajdedatum==0) || (zrovnajdedatum!==todat)) { //vlozeni hlavicky daneho dne do objednavky jen pri zmene datumu
				$datheader=$('<div class="row"><div class="col-xs-11 col-xs-offset-1">Na '+czdate+' <span>'+objcas+'</span> objednávám:</div></div>');
				$radekjidel=$('<div class="row"></div>');
				//ajaxa.push('Na '+czdate+' '+objcas+' objednávám:');
				$radekjidel.append  ($datheader);
				tomailpart.push('Na '+czdate+' '+objcas+' objednávám:');
				fordbpart[todat]=[];
				zrovnajdedatum=todat;
			};
			$('.dx-'+todat).each(function(){//pres divy s prislusnym datem
  				var kategorie=$(this).find('.katdoobj').first().text();
          var flagjekateg=false;
					$(this).find("div.jednojidlo").each(function (){//pres jidla v divu s datem
  					var nazev = $(this).find(".nazev").first().text();//nazev jidla
  		  	  $jid=$(this);
						$(this).find('input').each(function (){//pres inputy v jidle
			 				if($(this).val()>0){ //vyplneny input
								if(flagjekateg==false){
									$radekjidel.append('<div class="row"><div class="col-xs-11 col-xs-offset-2"><h4>'+kategorie+'</h4></div></div>');
// 									ajaxa.push('nl');
// 									ajaxa.push(kategorie);
									tomailpart.push('nl');
									tomailpart.push(kategorie);
									flagjekateg=true;
									flagis = true;
								}
								
								if ($(this).attr("data-input")=='pocetmal') {
									var cenapolmal=parseFloat( $jid.find('span.malacena').first().text());
									var kolikpolmal=parseFloat($(this).val());
									cenajidden=cenajidden+(cenapolmal*kolikpolmal);
									kolikjidden=kolikjidden+ kolikpolmal;
									$radekjidel.append($('<div class="row"><div class="col-xs-5 col-xs-offset-3">'+nazev+' (malá porce)</div><div class="col-xs-2 text-right">'+cenapolmal+' Kč/porci</div><div class="col-xs-1 text-right">'+kolikpolmal+' ks</div></div></div>'));		
  
									tomailpart.push(nazev+' (malá porce)......'+cenapolmal+' Kč/porci...'+kolikpolmal+' ks');
									fordbpart[todat][$(this).attr("data-id")]['pocetmal']=$(this).val();									
								}
								if ($(this).attr("data-input")=='pocetvel') {
									var cenapolvel=parseFloat( $jid.find('span.velkacena').first().text());
									var kolikpolvel=parseFloat($(this).val());
									kolikjidden=kolikjidden+ kolikpolvel;
									$radekjidel.append($('<div class="row"><div class="col-xs-5 col-xs-offset-3">'+nazev+'</div><div class="col-xs-2 text-right">'+cenapolvel+' Kč/porci</div><div class="col-xs-1 text-right">'+kolikpolvel+' ks</div></div></div>'));		

									tomailpart.push(nazev+'......'+cenapolvel+' Kč/porci...'+kolikpolvel+' ks');
									cenajidden=cenajidden+(cenapolvel*kolikpolvel);
									fordbpart[todat][$(this).attr("data-id")]['pocetvel']=$(this).val();
								}
	   						if ($(this).attr("data-input")=='pocet') {
									var cena=parseFloat( $jid.find('span.cena').first().text());
									var kolik=parseFloat($(this).val());
									kolikjidden=kolikjidden+ kolik;
									$radekjidel.append($('<div class="row"><div class="col-xs-5 col-xs-offset-3">'+nazev+'</div><div class="col-xs-2 text-right">'+cena+' Kč/porci</div><div class="col-xs-1 text-right">'+kolik+' ks</div></div></div>'));		

									tomailpart.push(nazev+'......'+cena+' Kč/porci...'+kolik+' ks');
									cenajidden=cenajidden+(cena*kolik);
									fordbpart[todat][$(this).attr("data-id")]['pocet']=$(this).val();
								}
							} //end vyplneny input
  		  		}); //end pres inputz v jidle
  		  	}); //end pres jidla v divu s datem
 			}); //end pres divy s prislusnym datem
		  if (kolikjidden>0){          
				var tmpr= '<div class="col-xs-12"><hr><div class="col-xs-5 col-xs-offset-2"><small>Na den '+czdate+' celkem položek: '+kolikjidden+'.</small></div><div class="col-xs-2 "><small> Cena celkem</small></div><div class="col-xs-3 text-right"><small>'+cenajidden+' Kč</small></div></div>';
				var tmprmailpart= 'Na den '+czdate+' celkem položek: '+kolikjidden+'. Cena celkem '+cenajidden+' Kč';
				kolikjidden=parseFloat(kolikjidden);
				cenajidden = parseFloat(cenajidden);
				var cenadopravy = parseFloat($('#cenadopravy').val());
				var cenaobjsdop=cenajidden+cenadopravy;
				if((cenajidden > $('#minobjnaden').val()) && (cenajidden<$('#doprzdarmaod').val())){
					  tomailpart.push('nl');
					  tomailpart.push('nl');
						tomailpart.push(tmprmailpart);
						tmpr = tmpr + '<br><div class="col-xs-12 text-right"><small>Cena včetně dopravy: <strong>' + cenaobjsdop +' Kč</strong></small></div><hr>';
						$radekjidel.append($(tmpr));
			      tomailpart.push('Cena včetně dopravy: ' + cenaobjsdop +' Kč');
					  tomailpart.push('nl');
					  tomailpart.push('nl');
					 	$radekjidel.wrap('<div class="col-xs-12 martop2 alert alert-success"></div>');
					 
				} else if (cenajidden < $('#minobjnaden').val()){
						var rozdil=$('#minobjnaden').val()-cenajidden;
						tmpr= '<div class="col-xs-12"><hr><div class="col-xs-5 col-xs-offset-2 text-danger">Objednávka je o '+rozdil+' Kč pod limitem a nemůže být přijata.</div><br><br></div>';
						//tomailpart se neresi, protoze mail neodejde - podlimitni objednavka
						$radekjidel.append($(tmpr));
						$radekjidel.wrap('<div class="row martop2 marbot3 alert alert-success" role="alert"></div>');
					  $('#thanks').empty();
					  $('#thanks').append('<p class="well text-danger">Podlimitní objednávka neumožňuje odeslání.</p>') ;
		  			$('#mailme').addClass("disabled");
				} else{ //doprava zdarma
						$radekjidel.append($(tmpr));
						$radekjidel.wrap('<div class="row martop2 marbot3 alert alert-success" role="alert"></div>');
						$('#mailme').removeClass("disabled");			
					  tomailpart.push('nl');
					  //tomailpart.push('nl');
					  tomailpart.push(tmprmailpart);
					  tomailpart.push('nl');
					  tomailpart.push('nl');
				}
				ajaxa=ajaxa.concat(tomailpart);
				fordb=fordb.concat(fordbpart);
				$htmlobj.append($radekjidel);
			}else{
				//$('#mailme').addClass("disabled");
			}
		});//end pres datumy
	  if (flagis == false){
			  $('#thanks').empty();
  			$('#thanks').append('<p class="well text-danger">Prázdná objednávka. Bude hlad.</p>') ;
        //$('#mailme').addClass("disabled");	
		}
		alert(JSON.stringify(fordb));
	}
	//klik na kontrolu objednavky
	$('#obj').on('click', function(e) {
    // e.preventDefault(); // prevent default form submit
		//if (halfchecknoro($("#noro")))$('#mailme').removeClass("disabled");
		
		$('#thanks').empty();
	
		if (!reqcheck($('[data-required="required"]')) || !valEmail($('#mail'))){
			$('#thanks').append('<p class="well text-danger">Prosíme o správné vyplnění potřebných adresních údajů.</p>');
			
			return false;
		}
		dejDatanew('div.canhide'); //sestaveni dat y divu canhide 
		$('#pata').append($htmlobj);
    $("html, body").animate({ scrollTop: $(document).height()-$(window).height() });
    return false;
	});
	//klik na odeslani objednavky	
	$('#mailme').on('click', function(e) {
  
	if (flagis && reqcheck($('[data-required="required"]')) && valEmail($('#mail'))){	

		 $('#thanks').empty();
		 setCookieFromInp($('[data-required]'));

	   var objajax={
		 	mail:$('#mail').val(),
		 	osoba:$('#osoba').val(),
		 	telefon:$('#telefon').val(),
		 	ulice:$('#ulice').val(),
	 	 	cp:$('#cp').val(),
	 	 	ctvrt:$('#ctvrt').val(),
		 	age:$("#age").val(),
		 	checkv:$("#checkv").val(),
		 	mailpodpis:$("#mailpodpis").val(),
		 	mailpodekovani:$("#mailpodekovani").val(),
		 	mailreklama:$("#mailreklama").val(),
		 	mailkontaktmenu:$("#mailkontaktmenu").val(),
		 	dat:ajaxa,
		 	inputs:fordb
		 };
		 var daj=JSON.stringify(objajax);
		 $.ajax({
	        type: "POST",
	        url: 'http://www.tosnis.cz/sendmail.php',
	        //url: 'http://www.newsroom.cz/pokus-receive-ajax.php',
	        //processData: false,
					//dataType: 'json',
					data: JSON.stringify(objajax),
	        complete:  function(){
	                //alert(daj);
					},
	        beforeSend: function(){
						$('#thanks').append($loader);
					},
	        success: function(d){
                   if(d.indexOf('~#')===0){
														$('#thanks').empty();
														$('#thanks').append('<p class="well text-danger">'+d+'</p>');
                            $('#thanks').removeClass( "text-success" );
                            $('#thanks').addClass( "text-warning" );					 	 	  
                            //alert("b"+d);
                    } else {
														$('#thanks').empty();
														$('#thanks').append('<p class="well">'+d+'</p>');
														$('#thanks').removeClass("text-warning");
                            $('#thanks').addClass( "text-success" );
                            //alert("a"+d);
 
                    }



	           
	        },
	        error: function(d){

							$('#thanks').append('<p class="well text-danger">Auvajs, nějaká chyba. Pro objednání prosíme volejte.</p>');
		          //alert(JSON.stringify(d));
	        }       
    	});
		}else{
			$('#thanks').append('<p class="well text-danger">Nesprávně nebo nedostatečně vyplněná objednávka.</p>') ;
		}
  	return false;
	
	});
	

	
//   $("button[type='submit']").click (function (e) {
// 		event.preventDefault();
// 		polozeknaden=0;
// 		dejDatanew('div.canhide');
// 		$('.container').append($htmlobj);
// 		alert('click');
// 	  return false;
// 	});

    //kontroluje obsah rucne vyplnene anti robotove kolonky 
    var checknoro = function (elem) {
        
				if (elem.val()!=7){
                elem.parent('div').addClass('has-error');
                elem.parent('div').append('<div id="seven" />');
                $("#norolabel").text("Ale ale, co takhle 7?");
                 //$('#mailme').addClass("disabled");
                return false;
        }else{
            $("#norolabel").text("Prosíme, kvůli ochraně před roboty napište číslicí výsledek součtu tři plus čtyři.");
						elem.parent('div').removeClass('has-error');
            $("#seven").remove();
             $('#mailme').removeClass("disabled");
            return true;
        }            
    };
    var halfchecknoro = function (elem) {
        if (elem.val()!=7){
                elem.parent('div').addClass('has-error');
                
                 //$('#mailme').addClass("disabled");
                return false;
        }else{
            elem.parent('div').removeClass('has-error');

            $('#mailme').removeClass("disabled");
            return true;
        }            
    };
//     $('#noro').change( function() {
//         checknoro($(this));
//     });
	 

	 
	 

//    $('.timepicker').change(function(){
// 
// 	 		  var armin = $(this).data('mincas').split(":");
// 				var armax = $(this).data('maxcas').split(":");
// 				var arincas = $(this).val().split(":");
// 				foreach
// 				if Number.isInteger(0)
// 				
// 				
// 				var dmin = new Date();
// 				var dmax = new Date();
// 				var dinp = new Date();
// 				dmin.setHours(min);
// 				dmax.setHours(max);
// 				var casinp=
// 				
// 			 	d.setHours()
// 			 $(this).data('mincas')		
// 	 });
	 

});
