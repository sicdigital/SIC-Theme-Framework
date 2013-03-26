<?php

function sic_footer(){
					GLOBAL $sic_theme;

			$footer_layout = $sic_theme->settings['footer']['layout'];

			if($footer_layout == 'none'){
				$footer = "";
			}
			
			if($footer_layout == '4' ){
			echo '<footer id="footer" class="cf">
			<div class="inner">
			
			<div class="one_fourth">';
	dynamic_sidebar( 'Footer Area 1' );	
	
			echo '</div>
			
			<div class="one_fourth">';
	dynamic_sidebar( 'Footer Area 2' );	
		echo '</div>
			
			<div class="one_fourth">';
	dynamic_sidebar( 'Footer Area 3' );	
				echo '</div>	
			
			<div class="one_fourth last">';
	dynamic_sidebar( 'Footer Area 4' );	
			echo'</div>
			</div>
			</footer><!--footer-->
			';
	
	}
	
	if($footer_layout == '3' ){
	echo '<footer id="footer" class="cf">
	<div class="inner">
			
	<div class="one_third">';
	dynamic_sidebar( 'Footer Area 1' );	
	
	echo '</div>
			
	<div class="one_third">';
	dynamic_sidebar( 'Footer Area 2' );	
		echo '</div>
			
	
	<div class="one_third last">';
	dynamic_sidebar( 'Footer Area 3' );	
		echo'</div>
	</div>
	</footer><!--footer-->
	';
	
}

	if($footer_layout == '2' ){
	echo '<footer id="footer" class="cf">
	<div class="inner">
			
	<div class="one_half">';
	dynamic_sidebar( 'Footer Area 1' );	
	
	echo '</div>
			
	<div class="one_half last">';
	dynamic_sidebar( 'Footer Area 2' );	

	echo '</div>
			
	</div>
	</footer><!--footer-->
	';
	
}
	if($footer_layout == '1' ){
	echo '<footer id="footer" class="cf">
	<div class="inner">';
	dynamic_sidebar( 'Main Sidebar' );

	

	echo '</div>
	</div>
	</footer><!--footer-->
	';
} 

	if($footer_layout = $sic_theme->settings['footer']['display_copyright']){
	echo'<footer id="sub_footer">Copyright SIC Digital LLC All Rights Reserved Etc</footer>';
	}

}	
			
		
