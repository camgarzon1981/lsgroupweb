<?php die("Access Denied"); ?>#x#a:2:{s:6:"output";a:3:{s:4:"body";s:0:"";s:4:"head";a:2:{s:11:"styleSheets";a:3:{s:44:"modules/mod_superfish_menu/css/superfish.css";a:3:{s:4:"mime";s:8:"text/css";s:5:"media";N;s:7:"attribs";a:0:{}}s:51:"modules/mod_superfish_menu/css/superfish-navbar.css";a:3:{s:4:"mime";s:8:"text/css";s:5:"media";N;s:7:"attribs";a:0:{}}s:53:"modules/mod_superfish_menu/css/superfish-vertical.css";a:3:{s:4:"mime";s:8:"text/css";s:5:"media";N;s:7:"attribs";a:0:{}}}s:7:"scripts";a:6:{s:46:"modules/mod_superfish_menu/js/superfish.min.js";a:3:{s:4:"mime";s:15:"text/javascript";s:5:"defer";b:0;s:5:"async";b:0;}s:50:"modules/mod_superfish_menu/js/jquery.mobilemenu.js";a:3:{s:4:"mime";s:15:"text/javascript";s:5:"defer";b:0;s:5:"async";b:0;}s:44:"modules/mod_superfish_menu/js/hoverIntent.js";a:3:{s:4:"mime";s:15:"text/javascript";s:5:"defer";b:0;s:5:"async";b:0;}s:42:"modules/mod_superfish_menu/js/supersubs.js";a:3:{s:4:"mime";s:15:"text/javascript";s:5:"defer";b:0;s:5:"async";b:0;}s:46:"modules/mod_superfish_menu/js/sftouchscreen.js";a:3:{s:4:"mime";s:15:"text/javascript";s:5:"defer";b:0;s:5:"async";b:0;}s:50:"templates/theme1981/js/jquery-scrolltofixed-min.js";a:3:{s:4:"mime";s:15:"text/javascript";s:5:"defer";b:0;s:5:"async";b:0;}}}s:13:"mime_encoding";s:9:"text/html";}s:6:"result";s:2484:"<nav class="moduletable navigation  span6"> 
<ul class="sf-menu   sticky" id="module-93">
<li class="item-216"><a href="/index.php/es/" >Home</a></li>
		<li class="item-197 active deeper dropdown parent"><span class="separator"Empresa>Empresa</span>

		<ul class="sub-menu">
		<li class="item-198"><a href="/index.php/es/empresa-3/direccionamiento-estrategico" >Direccionamiento estratégico</a></li>
		<li class="item-199 current active"><a href="/index.php/es/empresa-3/grupo-empresarial" >Grupo Empresarial</a></li>
		<li class="item-200"><a href="/index.php/es/empresa-3/afiliaciones-y-certificaciones" >Afiliaciones y certificaciones</a></li>
		</ul>
			</li>
			<li class="item-201"><a href="/index.php/es/alianzas-3" >Alianzas</a></li>
		<li class="item-202"><a href="/index.php/es/portafolio-de-servicios-3" >Servicios</a></li>
		<li class="item-203 deeper dropdown parent"><a href="/index.php/es/cobertura-3" >Contacto</a>
		<ul class="sub-menu">
		<li class="item-204"><a href="/index.php/es/cobertura-3/oficinas-en-colombia" >Oficinas en Colombia</a></li>
		<li class="item-220"><a href="/index.php/es/cobertura-3/servicio-cliente" >Servicio al cliente</a></li>
		<li class="item-221"><a href="/index.php/es/cobertura-3/herramientas-ayuda" >Herramientas de ayuda</a></li>
		</ul>
			</li>
			</ul>

<script>
	// initialise plugins
	jQuery(function($){
		$('#module-93')
			 
		.superfish({
			hoverClass:    'sfHover',         
	    pathClass:     'overideThisToUse',
	    pathLevels:    1,    
	    delay:         500, 
	    animation:     {opacity:'show', height:'show'}, 
	    speed:         'normal',   
	    speedOut:      'fast',   
	    autoArrows:    false, 
	    disableHI:     false, 
	    useClick:      0,
	    easing:        "linear",
	    onInit:        function(){},
	    onBeforeShow:  function(){},
	    onShow:        function(){},
	    onHide:        function(){},
	    onIdle:        function(){}
		})
				.mobileMenu({
			defaultText: "Navigate to...",
			className: "select-menu",
			subMenuClass: "sub-menu"
		});
		 
		var ismobile = navigator.userAgent.match(/(iPhone)|(iPod)|(android)|(webOS)/i)
		if(ismobile){
			$('#module-93').sftouchscreen();
		}
		$('.btn-sf-menu').click(function(){
			$('#module-93').toggleClass('in')
		});
				if (typeof $.ScrollToFixed == 'function') {
			$('#module-93').parents('[id*="-row"]').scrollToFixed({minWidth :768});
				}
					})
</script></nav>";}