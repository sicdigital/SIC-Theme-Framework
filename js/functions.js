


				jQuery(document).ready(function(){
					
			
					jQuery(".item").hover(
						
						function(){
							
							jQuery(this).addClass('active');
					}
					,
					function(){
						jQuery(this).removeClass('active');
						
					}//.overlay








				);
			
			
   
					//Hides address bar of mobile browsers by scrolling to the top.
					   function hideAddressBar(){  
					     setTimeout(function(){window.scrollTo(0, 1);}, 0);
					   }
					   //hideAddressBar
    

					  //Calculates the height of the navigation and applies to the body and navigation container. 
					  //This makes the page only as tall as the navigation
					   function adjustHeight(){
						  var viewportHeight = jQuery(window).height();
					      var canvasHeight = jQuery('nav.full_vertical').height();  
						  var newHeight = canvasHeight;
						  
						  if(canvasHeight < viewportHeight) {
							  
							  var newHeight = viewportHeight
						  }
						  
					      jQuery("body, .off-canvas, #responsive-nav-overlay").css({'height': newHeight, 'overflow': 'hidden'});
					   }
 
					   function resizeBody(){
							jQuery("body").css({'height': '100%', 'overflow': 'visible'});
					   }
   
					   function resizeOverlay(){
						   jQuery('#responsive-nav-overlay').css({
							   'height': '100%'
						   })
					   }
 
					    hideAddressBar();
 
 
					  //Show sub-menus on click for second and third level navigation
					   jQuery('.full_vertical li.menu-item a').click(function(e){
     
						   if(jQuery(this).parent().children(".sub-menu").length > 0){
					  		 e.preventDefault();
						   }
	   
					     jQuery(this).parent().children(".sub-menu").slideToggle('slow', function(){
  
							  adjustHeight();
						 });
		 

					   });	
   
   

					   //Adds the body class that activates the nav and the overlay
					   jQuery( '#responsive-nav-overlay, .responsive-nav').click(
		
					     function(){
			 
							 if(jQuery("body").hasClass("off-canvas-active"))
								 {
									 jQuery("body").removeClass("off-canvas-active");
									 resizeBody();
									 resizeOverlay()
								 }
		    
							else {  jQuery("body").addClass("off-canvas-active");
             
								  adjustHeight();
							  }
					      });//.toggle

				// 	Cufon.replace('h1');
				// 	Cufon.replace('h2');
				// 	Cufon.replace('h3');
				// 	Cufon.replace('h4');
				// 	Cufon.replace('h5');
				// 	Cufon.replace('nav > ul > li > a', {fontSize:'15px'});		
				 });
	




				jQuery(window).scroll(function() {
				    var y_scroll_pos = window.pageYOffset;
				    var scroll_pos_test = 150;             // set to whatever you want it to be

			   
			    if(y_scroll_pos > scroll_pos_test) {
			        jQuery("#responsive-nav h4").addClass("active");
			    }//if

			   
			    if(y_scroll_pos < scroll_pos_test){
				     jQuery("#responsive-nav h4").removeClass("active");
				 }//if
				 
				});//scroll
				
				
				