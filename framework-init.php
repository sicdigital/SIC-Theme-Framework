<?php 
/**
 * 
 *
 * @author Michael Chacon
 * @version $Id$
 * @copyright , 3 October, 2012
 * @package default
 **/


/**
* SicFramework
*
* The SicFramework class is used to set and store the default values of the base theme.
* Extend the framework in order to change values when creating a new theme. You can choose
* the functionality that you want to use  This is at the heart of everything,
* This is the API. This will alow me to semantically create versions this is the core thats
*
*/

class SicFramework
{
	public $name;
	public $settings;

	function __construct($name = "sic", $settings = array())	

		{
			$this->name = $name;
			$this->settings = $settings;
			$this->setDefaults();
			$this->defineConstants();
			$this->requireFiles();
			$this->requireSettings();
			$this->enableFeatures();

		}

/**
 * Set Defaults 
 *
 * 
 * Store any default vars you want use throughout the theme.
 * 
 **/
function setDefaults(){
	
	$defaults = array(
		'dev_mode' => TRUE,
		'features' => array(
						'tabs' => TRUE,
						'lightbox' =>  TRUE,
						'isotope' => FALSE,
						'fitvids' => TRUE),
		
		'design' => array(
			'background' => array( 
				'type' => 'backstretch',
				'url' => 'http://dev.framework/wp-content/themes/sic_theme/framework/img/defaults/graffiti_bg.jpg'),
			
			'layout' => 'responsive'
			),//design
		

		'footer' => array(
			'layout' => '4',
			'display_copyright' => TRUE
		),//footer

		'pages' => array(
			'title' => TRUE,
			'layout' => 'right',
			'sidebars' => array('default')
			)//page_settings
	);


	$this->settings = array_merge($defaults, $this->settings);
	
	return $this;

	}


function enableFeatures(){

	if ($this->settings['dev_mode']){
		add_action( 'template_redirect', array( $this, 'enable_less' ));
		//wp_enqueue_script( "modernizr",  get_bloginfo('template_directory') . "/framework/packages/modernizr/modernizr.js", FALSE, "2.6.2", FALSE);

	}
		

	if ($this->settings['design']['background']['type'] == 'backstretch'){
		wp_enqueue_script( "backstretch",  get_bloginfo('template_directory') . "/framework/packages/backstretch/jquery.backstretch.min.js", "jquery", "1.0", "all");
		add_action('wp_head', array( $this, 'add_backstretch' ));
	}//if
		

	if ($this->settings['features']['lightbox']){
		wp_enqueue_script( "PrettyPhoto",  get_bloginfo('template_directory') . "/framework/packages/prettyPhoto_3.1.4/js/jquery.prettyPhoto.js", array('jquery'), '3.1.4');
		wp_enqueue_style( "PrettyPhoto",  get_bloginfo('template_directory') . "/framework/packages/prettyPhoto_3.1.4/css/prettyPhoto.css", 'PrettyPhoto', '3.1.4');
		add_action('wp_head', array( $this, 'pretty_photo' ));
	}//endif


		if ($this->settings['features']['isotope']){

			wp_enqueue_script( "isotope",  get_bloginfo('template_directory') . "/framework/packages/isotope/jquery.isotope.min.js", '', "2.5.3", 'jquery');
		}

		if ($this->settings['features']['fitvids']){

			wp_enqueue_script( "fitvids",  get_bloginfo('template_directory') . "/framework/packages/fitvids/jquery.fitvids.min.js", '', "2.5.3", 'jquery');
			add_action('wp_head', array( $this, 'fitvids' ));
		
		}


if(is_admin()){
	    wp_enqueue_style( 'admin_styles', get_bloginfo('template_directory') . "/framework/admin/css/admin.css", "jquery", "1.0", "all");

}
}//enable features

function fitvids(){
	echo '<script>jQuery(document).ready(function(){jQuery("#content_block").fitVids();';
}//add_backstretch


function pretty_photo(){
	echo '<script type="text/javascript">jQuery(document).ready(function(){jQuery("a[rel^=\'lb\']").prettyPhoto();});</script>';
}//prettyphoto

function add_backstretch(){
	echo '<script>jQuery(document).ready(function(){
	jQuery.backstretch("' . $this->settings['design']['background']['url']. '", {speed: 100});})</script>';
}//add_backstretch

function enable_less(){
	wp_enqueue_script( "less_css",  get_bloginfo('template_directory') . "/framework/packages/less/less.min.js", FALSE , '1.3.3', FALSE);
}//enable_less





/**
 * DEFINE DIRECTORY CONSTANTS
 *
 * 
 * All of these define a variable to use in place of specific directories throughout the theme.
 * 
 **/
function defineConstants(){

 // Sets the directory everything is in 
 define('SIC_Framework', TEMPLATEPATH . '/framework');

 // Sets the packges directory, this is the place where all included packages are stored (aka stuff I didn't build, timthumb, cufon, etc)
 define('SIC_Packages', SIC_Framework . '/packages');
 
 // Sets the library directory, this is where all classes and files specific to the WP theme are stored (admin options, widgets, etc)
 define('SIC_Library', SIC_Framework . '/library');

 define('SIC_ShortCodes', SIC_Library . '/shortcodes');

 define('SIC_Widgets', SIC_Library . '/widgets');

 // Sets the CSS directory, the different FRONT-END styles are stored here
 define('SIC_CSS', SIC_Framework . '/css');
 
 // Sets the JS directory, the different FRONT-END JS is stored here, not related to packages.
 define('SIC_JS', SIC_Framework . '/js');
 
 // Sets the Admin directory, this is where everything used for the WP Admin side is stored. (custom post types, )
 define('SIC_Admin', SIC_Framework . '/admin');
 
 // Sets the directory for tim thumb to use throught the theme, need to find a better way to use timthumb
 define('TimThumb', get_bloginfo("stylesheet_directory") . '/framework/packages/timthumb/timthumb.php');
 function timthumb(){ echo TimThumb;}
 
 // Sets the directory everything is in 
 define('SIC_Settings', TEMPLATEPATH . '/settings');
 

define( 'RWMB_URL' , get_bloginfo('template_directory') . '/framework/admin/meta-box-framework/');
}




/**
* Requre Files
*
* 
* These are named pretty specifically, you should get the point
*
**/
function requireFiles(){

 //	require_once( SIC_Library . '/class-SicMetaBoxes.php');

	require_once( SIC_ShortCodes . '/abstract-SicShortCode.php');
	require_once( SIC_ShortCodes . '/factory-SicShortcodeFactory.php');
	require_once( SIC_ShortCodes . '/register-shortcodes.php');
	require_once( SIC_ShortCodes . '/class-SicTwitterFeed.php');
	require_once( SIC_ShortCodes . '/class-SicSlider.php');


	require_once( SIC_Library . '/class-SicGrid.php');
	require_once( SIC_Library . '/class-SicPagination.php');
	require_once( SIC_Library . '/class-SicPostTypes.php');
	require_once( SIC_Library . '/class-SicTemplateMethods.php');
	require_once( SIC_Library . '/class-SidebarGenerator.php');
	require_once( SIC_Widgets . '/class-widgets.php');
	require_once( SIC_Library . '/function-breadcrumbs.php');
	require_once( SIC_Library . '/function-comments.php');
	require_once( SIC_Library . '/function-footer.php');

	require_once( SIC_Library . '/beta/class-themeAPI.php');
	
	require_once( SIC_Admin . '/meta-box-framework/meta-box.php');
	 require_once( SIC_Admin . '/options-framework/theme-options.php');


//	require_once( SIC_Library . '/class-SicFormHelper.php');

}//require files


/*
* Require Settings
*
* The settings files interface with the framework so that each individual theme can have it's own settings
* in a logical place. 
*
*/
function requireSettings(){


}



}//class







add_filter('admin_enqueue_scripts', 'admin_styles'); 

function admin_styles(){
    wp_enqueue_script('admin_funcs', get_bloginfo('template_directory').'/framework/admin/functions.js', array('jquery'));
}	

 
