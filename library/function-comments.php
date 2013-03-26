<?php
/*******************************************************
Comments
********************************************************/
if ( ! function_exists( 'twentyeleven_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own twentyeleven_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Twenty Eleven 1.0
 */
function twentyeleven_comment( $comment, $args, $depth ) {
	
	$GLOBALS['comment'] = $comment;
	
	switch ( $comment->comment_type ) :
		case 'pingback' :
		case 'trackback' :?>
	
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'twentyeleven' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?></p>
	<?php
			break;
		default :
	?>
	
	<li <?php comment_class('cf'); ?>  id="li-comment-<?php comment_ID(); ?>">
	
		<div class="comment-meta">		
		<?php $avatar_size = 120; if ( '0' != $comment->comment_parent ) $avatar_size = 120;
			echo get_avatar( $comment, $avatar_size );?>
			<div class="author-link"><?php echo get_comment_author_link();?></div>
			
			<div class="date"><?php echo get_comment_date('F j');?></div>
			<!--<div class="adminIcon">ADMIN</div>-->
		</div><!--end commentMeta-->
	
		<div class="comment-body">
			<?php if ( $comment->comment_approved == '0' ) : ?>
			<em class="comment-awaiting-moderation">
				<?php _e( 'Your comment is awaiting moderation.', 'twentyeleven' ); ?></em>
			<br />
			<?php endif; ?>
			<?php comment_text(); ?>
			<?php edit_comment_link( __( 'Edit', 'twentyeleven' ), '<span class="edit-link">', '</span>' ); ?>					
		
			<?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'REPLY', 'twentyeleven' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
	
		</div>
</li>
	<?php
		break;
	endswitch;
}
endif; // ends check for twentyeleven_comment()




/*******************************************************
HTML 5 Navigation Fallback 
(if there isn't a menu present this is whats shown by default IE List of Pages)
********************************************************/

function sic_page_menu_args( $args ) {
	$args['menu_class'] = 'nav';
	$args['container'] = FALSE;


	return $args;
}
add_filter( 'wp_page_menu_args', 'sic_page_menu_args' );



