<?php
session_start();

/**
 * JW Post Types
 * @author Jeffrey Way
 * @link http://jeffrey-way.com
 */
 
 
//Jcrop and Jquery dependencies for cropable images 


// add_action('admin_init', 'jcrop_init');
// function jcrop_init(){
//     wp_enqueue_script( 'jcrop');
//     wp_enqueue_style('jcrop');
// }

 
class PostType
{

    /**
     * The name of the post type.
     * @var string
     */
    public $post_type_name;

    /**
     * A list of user-specific options for the post type.
     * @var array
     */
    public $post_type_args;


    /**
     * Sets default values, registers the passed post type, and
     * listens for when the post is saved.
     *
     * @param string $name The name of the desired post type.
     * @param array @post_type_args Override the options.
     */
    function __construct($name, $post_type_args = "")
    {
        if (!isset($_SESSION["taxonomy_data"])) {
            $_SESSION['taxonomy_data'] = array();
        }

        $this->post_type_name = strtolower($name);
        $this->post_type_args = (array)$post_type_args;
    
        // First step, register that new post type
        $this->init(array(&$this, "register_post_type"));

        $this->save_post();

    }

    /**
     * Helper method, that attaches a passed function to the 'init' WP action
     * @param function $cb Passed callback function.
     */
    function init($cb)
    {
        add_action("init", $cb);
    }

    /**
     * Helper method, that attaches a passed function to the 'admin_init' WP action
     * @param function $cb Passed callback function.
     */
    function admin_init($cb)
    {
        add_action("admin_init", $cb);

    }


    /**
     * Registers a new post type in the WP db.
     */
    function register_post_type()
    {
        $n = ucwords($this->post_type_name);

        $args = array(
            "label" => $n . 's',
            'singular_name' => $n,
            "public" => true,
            "publicly_queryable" => true,
            "query_var" => true,
            #"menu_icon" => get_stylesheet_directory_uri() . "/article16.png",
            "rewrite" => true,
            "capability_type" => "post",
            "hierarchical" => false,
            "menu_position" => null,
            "supports" => array("title", "editor", "thumbnail"),
            'has_archive' => true
        );

        // Take user provided options, and override the defaults.
        $args = array_merge($args, $this->post_type_args);
if($this->post_type_name !== 'page' && $this->post_type_name !== 'post'){
        register_post_type($this->post_type_name, $args);}

    }



    /**
     * Registers a new taxonomy, associated with the instantiated post type. 
     *
     * @param string $taxonomy_name The name of the desired taxonomy
     * @param string $plural The plural form of the taxonomy name. (Optional)
     * @param array $options A list of overrides
     */
    function add_taxonomy($taxonomy_name, $plural = '', $options = array())
    {
        // Create local reference so we can pass it to the init cb.
        $post_type_name = $this->post_type_name;

        // If no plural form of the taxonomy was provided, do a crappy fix. :)

        if (empty($plural)) {
            $plural = $taxonomy_name . 's';
        }

        // Taxonomies need to be lowercase, but displaying them will look better this way...
        $taxonomy_name = ucwords($taxonomy_name);

        // At WordPress' init, register the taxonomy
        $this->init(
            function() use($taxonomy_name, $plural, $post_type_name, $options)
            {
                // Override defaults with user provided options

                $options = array_merge(
                    array(
                         "hierarchical" => false,
                         "label" => $taxonomy_name,
                         "singular_label" => $plural,
                         "show_ui" => true,
                         "query_var" => true,
                         "rewrite" => array("slug" => strtolower($taxonomy_name))
                    ),
                    $options
                );

                // name of taxonomy, associated post type, options
                register_taxonomy(strtolower($taxonomy_name), $post_type_name, $options);
            });
    }



    /**
     * Creates a new custom meta box in the New 'post_type' page.
     *
     * @param string $title
     * @param array $form_fields Associated array that contains the label of the input, and the desired input type. 'Title' => 'text'
     */
    function add_meta_box($title, $form_fields = array())
    {
        $post_type_name = $this->post_type_name;

        // end update_edit_form
        add_action('post_edit_form_tag', function()
            {
                echo ' enctype="multipart/form-data"';
            });


        // At WordPress' admin_init action, add any applicable metaboxes.
        $this->admin_init(function() use($title, $form_fields, $post_type_name)
            {
                add_meta_box(
                    strtolower(str_replace(' ', '_', $title)), // id
                    $title, // title
                    function($post, $data)
                    { // function that displays the form fields
                        global $post;

                        wp_nonce_field(plugin_basename(__FILE__), 'jw_nonce');

                        // List of all the specified form fields
                        $inputs = $data['args'][0];

                        // Get the saved field values
                        $meta = get_post_custom($post->ID);

                        // For each form field specified, we need to create the necessary markup
                        // $name = Label, $type = the type of input to create
                        foreach ($inputs as $name => $type) {
                            #'Happiness Info' in 'Snippet Info' box becomes
                            # snippet_info_happiness_level
                            $id_name = $data['id'] . '_' . strtolower(str_replace(' ', '_', $name));

                            if (is_array($inputs[$name])) {
                                // then it must be a select or file upload
                                // $inputs[$name][0] = type of input

                                if (strtolower($inputs[$name][0]) === 'select') {
                                    // filter through them, and create options
                                    $select = "<select name='$id_name' class='widefat'>";
                                    foreach ($inputs[$name][1] as $option) {
                                        // if what's stored in the db is equal to the
                                        // current value in the foreach, that should
                                        // be the selected one

                                        if (isset($meta[$id_name]) && $meta[$id_name][0] == $option) {
                                            $set_selected = "selected='selected'";
                                        } else $set_selected = '';

                                        $select .= "<option value='$option' $set_selected> $option </option>";
                                    }
                                    $select .= "</select>";
                                    array_push($_SESSION['taxonomy_data'], $id_name);
                                }
                            }

                            // Attempt to set the value of the input, based on what's saved in the db.
                            $value = isset($meta[$id_name][0]) ? $meta[$id_name][0] : '';

                            $checked = ($type == 'checkbox' && !empty($value) ? 'checked' : '');

                            // Sorta sloppy. I need a way to access all these form fields later on.
                            // I had trouble finding an easy way to pass these values around, so I'm
                            // storing it in a session. Fix eventually.
                            array_push($_SESSION['taxonomy_data'], $id_name);

                            // TODO - Add the other input types.
                            $lookup = array(
                                "text" => "<input type='text' name='$id_name' value='$value' class='widefat' />",
                                "textarea" => "<textarea name='$id_name' class='widefat' rows='10'>$value</textarea>",
                                "checkbox" => "<input type='checkbox' name='$id_name' value='$name' $checked />",
                                "select" => isset($select) ? $select : '',
                                "file" => "<input type='file' name='$id_name' id='$id_name' />",
                                "croppedImage" => "<input type='file' name='$id_name'  id='$id_name' />",
                                "editor" => "",
                                "date" => "<input type='text' name='$id_name' value='$value' class='widefat datepicker' style='position: relative; z-index: 100000;' /> ",
                                "slideshow" => "<ul id='sortable'><li id='id' class='sic_form_field'><input type='text' class='ui-state-default' name='' id='imageURL'><input class='button' value='Upload' name=''  id='upload'></li><a id='cloner' href='#'>Click to Clone</a></ul>",
                                
                            );
                            

                            echo '<p>';
                            echo   '<label>';
                            echo ucwords($name) . ':'; 
                            echo '</label>';
                            echo $lookup[is_array($type) ? $type[0] : $type]; 
                            echo '</p>';
                            echo '<p>';
                            

                                
                                    // If a file was uploaded, display it below the input.
                                    $file = get_post_meta($post->ID, $id_name, true);
                                    if ( $type === 'file' ) {
                                        // display the image
                                        $file = get_post_meta($post->ID, $id_name, true);

                                        $file_type = wp_check_filetype($file);
                                        $image_types = array('jpeg', 'jpg', 'bmp', 'gif', 'png');
                                        if ( isset($file) ) {
                                            if ( in_array($file_type['ext'], $image_types) ) {
                                                echo "<img src='$file' alt='' style='max-width: 400px;' />";
                                            } else {
                                                echo "<a href='$file'>$file</a>";
                                            }
                                        }
                                    }
                                
                            echo '</p>';
                            echo '<p>';

                                
                                    // If a file was uploaded, display it below the input.
                                    $file = get_post_meta($post->ID, $id_name, true);
                                    if ( (isset($type[0])) && ($type[0] == 'croppedImage') ) {
                                        // display the image
                                        $file = get_post_meta($post->ID, $id_name, true);

                                        $file_type = wp_check_filetype($file);
                                        $image_types = array('jpeg', 'jpg', 'bmp', 'gif', 'png');
                                        if ( isset($file) ) {
                                            if ( in_array($file_type['ext'], $image_types) ) {
                                                echo "<img src='$file' alt='' id='target' /><input type='hidden' id='x' name='x' /><input type='hidden' name='y' id='y' /><input type='hidden' name='h' id='h' value=0 /><input type='hidden' id='w' name='w' value=0 /><input type='hidden' name='id_name' value='$id_name' /><script>jQuery.noConflict();
 jQuery('#target').Jcrop({aspectRatio:$type[1], boxWidth: 800, onSelect: updateCord});function updateCord(c){jQuery('#x').val(c.x);jQuery('#y').val(c.y);jQuery('#w').val(c.w);jQuery('#h').val(c.h);}</script>";
                                            } else {
                                                echo "<a href='$file'>$file</a>";
                                            }
                                        }
                                    }
                                
                            echo '</p>';
                            
                            
                            echo '<p>';

                                
                                    // If a file was uploaded, display it below the input.
                                    if ( $type === 'editor' ) {
                                        // display the image

                                            wp_editor( $post->post_content, 'content-id', array( 'textarea_name' => 'content', 'media_buttons' => false, 'tinymce' => array( 'theme_advanced_buttons1' => 'formatselect,forecolor,|,bold,italic,underline,|,bullist,numlist,blockquote,|,justifyleft,justifycenter,justifyright,justifyfull,|,link,unlink,|,spellchecker,wp_fullscreen,wp_adv' ) ) ); 
                                        
                                    }
                                
                            echo '</p>';
                            
                            echo '<p>';

                                
                                    // If a file was uploaded, display it below the input.
                                    if ( $type === 'date' ) {
                                        
                                        wp_enqueue_script( 'jquery-ui-core', 'jquery' );
                                        wp_enqueue_script( 'jquery-ui-datepicker', 'jquery-ui-core' );
                                        wp_enqueue_style( 'jquery-ui-datepicker');
                                        wp_enqueue_style('jqueryDataPickerStyle', get_template_directory_uri() . '/framework/admin/css/jquery-ui-1.8.23.custom.css');
                                        
                                        echo "<script type='text/javascript'>
                                    jQuery(document).ready(function(){
                                        jQuery( '.datepicker' ).datepicker({dateFormat: 'yymmdd'});
                                });</script>";
                                        

                                    }
                                
                            echo '</p>';
                            echo '<p>';
                            
                                if ( $type === 'slideshow' ) {
                                echo "<script type='text/javascript'>
                                        var cloneCount = 1;
                                        var subCount = 1;
                                        jQuery(document).ready(function(){
                                        jQuery( '#sortable' ).sortable();   
                                        jQuery('#cloner').click(function(e){
                                        jQuery(this).parent()
                                        alert('this.patent='+jQuery(this).parent().html());
                                        var parent = jQuery('#id').clone().attr('id', 'id'+ cloneCount++);
                                        var children = jQuery(parent).children();
                                        jQuery(children).each(
                                        function() {
                                        
                                        jQuery(this).attr('value','input_' + cloneCount + '_' + subCount++)
                                        .parent()
                                        .insertAfter(jQuery('[id^=id]:last'));
                                        return false;
                                        }
                                        );
                                        });
                                        });
                                        
                                        
                                        jQuery(document).ready(function(){
                                        
                                        var _custom_media = true,
                                        _orig_send_attachment = wp.media.editor.send.attachment;
                                        
                                         jQuery('.button').click(function(e) {
                                         
                                            var send_attachment_bkp = wp.media.editor.send.attachment;
                                            var button = jQuery(this);
                                            var id = button.attr('id').replace('_button', '');
                                            _custom_media = true;
                                            wp.media.editor.send.attachment = function(props, attachment){
                                            if ( _custom_media ) {
                                            jQuery('#'+'imageURL').val(attachment.url);
                                            } else {
                                            return _orig_send_attachment.apply( this, [props, attachment] );
                                            };
                                            }
                                            wp.media.editor.open(button);
                                            return false;
                                            });
                                            jQuery('.add_media').on('click', function(){
                                            _custom_media = false;
                                            });
                                        
                                        
                                        
                                        
                                        });</script>";
                                }
                            
                            echo '</p>';
                            
                            

                            

                        }
                    },
                    $post_type_name, // associated post type
                    'normal', // location/context. normal, side, etc.
                    'default', // priority level
                    array($form_fields) // optional passed arguments.
                ); // end add_meta_box
            });
    }


  







    /**
     * When a post saved/updated in the database, this methods updates the meta box params in the db as well.
     */
    function save_post()
    {
        add_action('save_post', function()
            {
                if ($post->post_type == 'revision') 
                {
                   error_log('Its revision!!');
                }
                error_log('Save called!!');
                // Only do the following if we physically submit the form,
                // and now when autosave occurs.
                if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

                global $post;

                if ($_POST && !wp_verify_nonce($_POST['jw_nonce'], plugin_basename(__FILE__))) {
                    return;
                }

                // Get all the form fields that were saved in the session,
                // and update their values in the db.
                if (isset($_SESSION['taxonomy_data'])) {
                    foreach ($_SESSION['taxonomy_data'] as $form_name) {
                        if (!empty($_FILES[$form_name]) ) {
                            if ( !empty($_FILES[$form_name]['tmp_name']) ) {
                                $upload = wp_upload_bits($_FILES[$form_name]['name'], null, file_get_contents($_FILES[$form_name]['tmp_name']));

                                if (isset($upload['error']) && $upload['error'] != 0) {
                                    wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
                                } else {
                                    update_post_meta($post->ID, $form_name, $upload['url']);
                                }
                            }
                       } else {
                            // Make better. Have to do this, because I can't figure
                            // out a better way to deal with checkboxes. If deselected,
                            // they won't be represented here, but I still need to
                            // update the value to false to blank in the table. Hmm...
                            if (!isset($_POST[$form_name])) $_POST[$form_name] = '';
                            if (isset($post->ID) ) {
                                update_post_meta($post->ID, $form_name, $_POST[$form_name]);
                            }
                        }
                    }
                    
                    error_log('Here check if the post variables are set');
                    if( isset($_POST['x']) && isset($_POST['y']) && isset($_POST['h']) && isset($_POST['w']) && ($_POST['h']!=0) && ($_POST['w']!=0) )
                    {
                      error_log('Its and update call and the crop cordinates has changed!!');
                      $upload_dir = wp_upload_dir();                    //Configured upload directory for media
                      $path = $upload_dir['path'];                     //path of the configured media upload directory
                      $httpURL = $upload_dir['url'];                    //httpULR of the configured media upload directory
                      $url = get_post_meta($post->ID,$_POST['id_name'],true);    //URL of the post meta data
                      $filename = str_replace(".jpg","",basename($url));         //Filename from the URL
                      $newfile = $path.'/'.$filename.'-new.jpg';
                      $newfilehttp = $httpURL.'/'.$filename.'-new.jpg';
                      $img_r = imagecreatefromjpeg($url); 
                      if(!$img_r)
                        {
                            wp_die('Image creation from uploaded image failed');
                        } 
                      $dst_r = ImageCreateTrueColor( $_POST['w'.$id_name], $_POST['h'.$id_name] );
                      imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],$_POST['w'],$_POST['h'],$_POST['w'],$_POST['h']);
                      imagejpeg($dst_r,$newfile);
                      update_post_meta($post->ID, $_POST['id_name'], $newfilehttp);
                     
                      unset($_POST['x']);
                      unset($_POST['y']);
                      unset($_POST['w']);
                      unset($_POST['h']);
                      unset($_POST['id_name']);
                        
                    }
                    $_SESSION['taxonomy_data'] = array();

                }

            });
    }
}


/*********/
/* USAGE */
/*********/

// $product = new PostType("movie");
// $product->add_taxonomy('Actor');
// $product->add_taxonomy('Director');
// $product->add_meta_box('Movie Info', array(
//  'name' => 'text',
//  'rating' => 'text',
//  'review' => 'textarea',
// 'Profile Image' => 'file'

// ));


?>
