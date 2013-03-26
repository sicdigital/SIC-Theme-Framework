<?php

//$metaboxes stores all of the metabox options and fields


	
	$sic_sidebars = get_option('twenty_eleven');
	$sic_sidebars = $sic_sidebars['multi_sidebar'];

//hooks the add_meta_boxes function to WP admin_init
add_action('admin_init', 'add_meta_boxes');

//takes $metaboxes var and creates each metabox and field
function add_meta_boxes() {

		global $metaboxes;

$metaboxes = array( 
	'portfolio' => array(
			'id' => 'slider_meta_box',
			'title' => 'Page Layout',
			'page' => 'page',
			'context' => 'normal',
			'priority' => 'high',
			'fields' => array(
				
					array(
						'name' => 'Text',
						'desc' => '',
						'id' => 'text1',
						'type' => 'text',
						'std' => '',
						'options' => array('Right' =>'rsidebar','Left' => 'lsidebar','None'=>'full')),//endfield					
					array(	
						'name' => 'Textarea',
						'desc' => '',
						'id' => 'textarea2',
						'type' => 'textarea',
						'std' => '',
						'options' => array('Right' =>'rsidebar','Left' => 'lsidebar','None'=>'full')),//endfield
									array(	
						'name' => 'Date',
						'desc' => '',
						'id' => 'date2',
						'type' => 'date',
						'std' => '',
						'options' => array('Right' =>'rsidebar','Left' => 'lsidebar','None'=>'full')),//endfield
									array(	
						'name' => 'Editor',
						'desc' => '',
						'id' => 'editor2',
						'type' => 'editor',
						'std' => '',
						'options' => array('Right' =>'rsidebar','Left' => 'lsidebar','None'=>'full')),//endfield
						
						array(
						'name' => 'Select',
						'desc' => '',
						'id' => 'radio2',
						'type' => 'select',
						'std' => '',
						'options' => array('Right' =>'rsidebar','Left' => 'lsidebar','None'=>'full')),//endfield
					
						array(
						'name' => 'Radio2',
						'desc' => '',
						'id' => 'rrrr',
						'type' => 'radio',
						'std' => '',
						'options' => array('radd2' => 'radd2', 'raddd333' => 'raddd333' , '3arraa' => '3arraa')),//endfield
					

						// array(
						// 	'name' => 'Sidebar',
						// 	'desc' => '',
						// 	'id' => 'selected_sidebar',
						// 	'type' => 'sidebars',
						// 	'std' => '',
						// 	'options' => $sic_sidebars, 	
						// 				),//endfield
				
				),//end fields
				),//end portfolio		
);//end primary array
	
	//loops through each metabox and value uses show_the_meta_box to display fields
		foreach ($metaboxes as $metabox => $values) {
	
					add_meta_box(
								$values['id'], 
								$values['title'], 
								'show_the_meta_box', 
								$values['page'], 
								$values['context'], 
								$values['priority'], 
								$values['fields']);

		}//add_meta_box( $id, $title, $callback, $page, $context, $priority, $callback_args );
	
	
	//displays the form fields in all of the newly formed meta boxes
	function show_the_meta_box ($post, $metabox) {
			global $forms;


		echo '<form><input type="hidden" name="meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
		
		
		
		
			foreach ($metabox['args'] as $key => $field) {
		
				// get current post meta data
				$meta = get_post_meta($post->ID, $field['id'], true);
		
if($field['type'] == 'radio'){
	
if($field['options'][$meta] != ""){

$field['std'] = $meta;
var_dump($field['options']);
		 $forms->getField( $field['type'], array('id' => $field['id'], 'value' => 'new-value', 'options' => $field['options']));


}

}				
else{
		 $forms->getField( $field['type'], array('id' => $field['id'], 'value' => $meta ? $meta : $field['std'],  'options' => $field['options']));
}
		echo '<form>';

// 				switch ($field['type']) {
			

// 				//If Text		
// 			case 'text':
// 			echo '<input type="text" name="', $field['id'], '" id="datepicker" value="', $meta ? $meta : $field['std'], '" size="30" style="width:97%" />',
// 					'<br />', $field['desc'];
// 				break;
			



// //If Text Area			
// 			case 'textarea':
// 				echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" style="width:97%">', $meta ? $meta : $field['std'], '</textarea>',
// 					'<br />', $field['desc'];
// 				break;
				
// 				case 'rich_textarea':
				
// 				if (function_exists('wp_tiny_mce'))
// 				    wp_tiny_mce(false, array(
// 				        'mode' => 'exact',
// 				        'elements' => $field[id],
// 				        'height' => 200,
// 				        'plugins' => 'inlinepopups,wpdialogs,wplink,media,wpeditimage,wpgallery,paste,tabfocus',
// 				        'forced_root_block' => false,
// 				        'force_br_newlines' => true,
// 				        'force_p_newlines' => false,
// 				        'convert_newlines_to_brs' => true
// 				    ));
						
						
// 							echo '<div id="editorcontainer"><textarea id="' . $field['id'] . '" name="' . $field['id'] .'" cols="60" rows="5" style="width:100%; height: 200px">' . $field['id'] . '</textarea> </div>';
				
// 					break;
			
			
// //If Drop Down		
// 			case 'select':
// 				echo '<select name="', $field['id'], '" id="', $field['id'], '">';
// 				foreach ($field['options'] as $option => $value) {
// 					echo '<option', $meta == $option ? ' selected="selected"' : '', '>', $option, '</option>';
// 				}
// 				echo '</select>';
// 				break;
			
// 	//If Drop Down		
// 			case 'sidebars':
// 				echo '<select name="', $field['id'], '" id="', $field['id'], '">';
// 				echo '<option>Default</option>';
// 				foreach ($field['options'] as $option => $value) {
// 					echo '<option', $meta == $value ? ' selected="selected"' : '', '>', $value, '</option>';
// 				}
// 				echo '</select>';
// 				break;
				
// //If Radio			
				
// 			case 'radio':
// 				foreach ($field['options'] as $option => $value) {
// 					echo '<input type="radio" name="' . $field['id'] . '" value="', $value , '"', $meta == $value ? ' checked="checked"' : '', ' />', $option . "<br  />";
// 				}
// 				break;
			
			
// //If Checkbox		
			
// 			case 'checkbox':
// 				echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />';
// 				break;
				
// //If Button				
// 				case 'button':
// 				echo '<input type="button" name="', $field['id'], '" id="', $field['id'], '"value="', $meta ? $meta : $field['std'], '" /><br  />';
// 				break;
				
// 				//If Button				
// 				default:
// 				do_action("form_" . $field['type'], $field);
				
// 				break;
// 		}
// 		echo 	'<td>',
// 			'</tr>';
	
	}

	 echo '</table>';


}	}

add_action('save_post', 'save_meta_box');


// Save data from meta box
function save_meta_box($post_id) {
	global $metaboxes;
	global $post_id;
	
	if (!wp_verify_nonce($_POST['meta_box_nonce'], basename(__FILE__))) {
		return $post_id;
	}

	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}

	// check permissions
	if ('page' == $_POST['post_type']) {
		if (!current_user_can('edit_page', $post_id)) {
			return $post_id;
		}
	} elseif (!current_user_can('edit_post', $post_id)) {
		return $post_id;
	}

		foreach ($metaboxes as $metabox => $values) {
	

	foreach ($values['fields'] as $field) {
		$old = get_post_meta($post_id, $field['id'], true);
		$new = $_POST[$field['id']];
		
		if ($new && $new != $old) {
			update_post_meta($post_id, $field['id'], $new);
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
}
}
