<?php
/*******************************************************
Get Post Gallery 

Get's all the images from a page/posts gallery and returns as an array.
**********************************************************/
if ( ! function_exists( 'sic_post_gallery' ) ) :

function sic_post_gallery(){
	
	$content = array();
	$attachments = get_children( array('post_parent' => get_the_ID(), 'post_type' => 'attachment', 'post_mime_type' =>'image') );
	$image = array();
	foreach ( $attachments as $attachment_id => $attachment ) {
								
		if($attachment_id != $thumb_ID){ //don't include the main page thumbnail'
								 
		$image[] = get_image_path(wp_get_attachment_url( $attachment_id, 'FULL' ));
									
		$thumb = get_bloginfo('stylesheet_directory') . '/framework/packages/timthumb/timthumb.php?src=' . $image . '&h=350&w=500' ;
		$overlay = get_bloginfo('stylesheet_directory') . '/framework/packages/timthumb/timthumb.php?src=' . $image . '&w=800' ;
	}
	}
						
						
	foreach($image as $key => $url){
	$content[] = '<img class="" src="' . $url .'"/>';
								
	}
	return($content);	
}
endif;


function get_image_path($gp_image) {
$theImageSrc = $gp_image;
global $blog_id;
if (isset($blog_id) && $blog_id > 0) {
	$imageParts = explode('/files/', $theImageSrc);
	if (isset($imageParts[1])) {
		$theImageSrc = '/blogs.dir/' . $blog_id . '/files/' . $imageParts[1];
	}
}
return $theImageSrc;
}

/*******************************************************
Article Featured Image

This function is for outputting the featured image of a post or page from within the loop. 
You can set the height or width independently. 
There is a unique ID and a general featured-image class. 
It would be nice in the future to let TimThumb re-size these images on the fly.

*******************************************************/

if ( ! function_exists( 'sic_featured' ) ) :
function sic_featured($width = '', $height = ''){
	
	$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'large'); 
	
	if($large_image_url) { ?>
		
		<img src="<?php echo $large_image_url[0];?>" alt="<?php the_title();?> Featured Image" <?php if($width){echo "width='" .  $width . "'";}?> <?php if($height){echo "height='" .  $height . "'";}?> id="featured_<?php the_ID(); ?>" class="featured_image" />
	
		<?php }
}
endif;


/*******************************************************
Sic Logo

Outputs the site title and description if no logo image is present

********************************************************/

if (!function_exists('sic_logo')){
		
		function sic_logo(){
			if(sic_option('header_logo')){
				return'<div class="logo"><img src="' . sic_option('header_logo') . '"/></div>';

	}else{

			return'<h1 id="site_title"><a href="/">' . get_bloginfo('name') . '</a></h1>
			<div id="site_description">
			<h2>' .  get_bloginfo('description') . '</h2>
			</div><!--site_description-->';}
	}//sic_logo
}//!exists



/*******************************************************
Article Meta

This function outputs the meta fields of the post. 
Would like to add microformats.

********************************************************/

if ( ! function_exists( 'sic_meta' ) ) :
function sic_meta($classes = ""){ ?>
	
		<div class="article_meta<?php if($classes){echo " " . $classes;}?>" id="meta_<?php the_ID(); ?>">
				<ul>
				<?php $year = get_the_time('Y'); ?>
				<?php $month = get_the_time('m'); ?>
				<li class="author">Posted by <?php the_author_posts_link();?> on 
				<?php if(!is_page()){ ?><li>in <?php the_category(' ');?></li> <?php } ?>
				<li class="comment_link"><a href="<?php comments_link();?>">Leave a comment</a></li>
				</ul>
		</div>
<?php

}
endif;


		

function sidebar_position_class(){
	global $sic_theme;
	$sidebars = rwmb_meta('page_layout');
	if($sidebars == ""){
		$sidebars = $sic_theme->settings['pages']['layout'];
	}
	//Checks what the meta is set to and outputs the correct class
	switch ($sidebars) {
	case 'rsidebar':
	echo 'right';
	break;	

	case 'lsidebar':
	echo 'left';
	break;	

	case 'full':
	echo 'full';
	break;
	
	default:
	echo 'right';
	break;
	}
	

}