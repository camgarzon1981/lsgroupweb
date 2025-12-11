<?php die("Access Denied"); ?>#x#a:2:{s:6:"output";a:3:{s:4:"body";s:0:"";s:4:"head";a:2:{s:11:"styleSheets";a:3:{s:44:"modules/mod_superfish_menu/css/superfish.css";a:3:{s:4:"mime";s:8:"text/css";s:5:"media";N;s:7:"attribs";a:0:{}}s:51:"modules/mod_superfish_menu/css/superfish-navbar.css";a:3:{s:4:"mime";s:8:"text/css";s:5:"media";N;s:7:"attribs";a:0:{}}s:53:"modules/mod_superfish_menu/css/superfish-vertical.css";a:3:{s:4:"mime";s:8:"text/css";s:5:"media";N;s:7:"attribs";a:0:{}}}s:7:"scripts";a:6:{s:46:"modules/mod_superfish_menu/js/superfish.min.js";a:3:{s:4:"mime";s:15:"text/javascript";s:5:"defer";b:0;s:5:"async";b:0;}s:50:"modules/mod_superfish_menu/js/jquery.mobilemenu.js";a:3:{s:4:"mime";s:15:"text/javascript";s:5:"defer";b:0;s:5:"async";b:0;}s:44:"modules/mod_superfish_menu/js/hoverIntent.js";a:3:{s:4:"mime";s:15:"text/javascript";s:5:"defer";b:0;s:5:"async";b:0;}s:42:"modules/mod_superfish_menu/js/supersubs.js";a:3:{s:4:"mime";s:15:"text/javascript";s:5:"defer";b:0;s:5:"async";b:0;}s:46:"modules/mod_superfish_menu/js/sftouchscreen.js";a:3:{s:4:"mime";s:15:"text/javascript";s:5:"defer";b:0;s:5:"async";b:0;}s:50:"templates/theme1981/js/jquery-scrolltofixed-min.js";a:3:{s:4:"mime";s:15:"text/javascript";s:5:"defer";b:0;s:5:"async";b:0;}}}s:13:"mime_encoding";s:9:"text/html";}s:6:"result";s:1901:"<nav class="moduletable navigation  span6"> 
<ul class="sf-menu   sticky" id="module-119">
<li class="item-196"><a href="/index.php/en/" >Home </a></li>
		<li class="item-207 active deeper dropdown parent"><span class="separator"Who we are>Who we are</span>

		<ul class="sub-menu">
		<li class="item-209"><a href="/index.php/en/empresa-4/grupo-empresarial" >Business group</a></li>
		<li class="item-210 current active"><a href="/index.php/en/empresa-4/afiliaciones-y-certificaciones" >Quality Certifications and Membership</a></li>
		</ul>
			</li>
			<li class="item-211"><a href="/index.php/en/alianzas-4" >Alliances</a></li>
		<li class="item-212"><a href="/index.php/en/portafolio-de-servicios-4" >Services</a></li>
		</ul>

<script>
	// initialise plugins
	jQuery(function($){
		$('#module-119')
			 
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
			$('#module-119').sftouchscreen();
		}
		$('.btn-sf-menu').click(function(){
			$('#module-119').toggleClass('in')
		});
				if (typeof $.ScrollToFixed == 'function') {
			$('#module-119').parents('[id*="-row"]').scrollToFixed({minWidth :768});
				}
					})
</script></nav>";}