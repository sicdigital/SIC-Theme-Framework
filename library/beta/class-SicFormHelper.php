<?php

class SicFormHelper{
	
public $field_registry;

public $script_registry;

public function	__construct(array $registeredFields = array()){

	foreach($registeredFields as $type => $callback){

			$this->registerField($type, $callback, $styles, $scripts);	

		}

}//construct


   public function registerField($type, $callback = null, $scripts = array(), $styles = array())
        {
            if ( $callback ) {

                $this->field_registry[$type]['callback'] = $callback;

            } 

			if($styles){
			
				$this->field_registry[$type]['styles'] = $styles;
				
			}


			if($scripts){
	                $this->field_registry[$type]['scripts'] = $scripts;

			}
            
            else {
                $this->field_registry[] = $type;
            }

            return $this;
    
       }//registerField
    


	
	function getField($type, $atts){

		 call_user_func_array($this->field_registry[$type]['callback'], array($atts));

			if ($this->field_registry[$type]['scripts']) {
				$scripts = $this->field_registry[$type]['scripts'];
				$this->enqueueScripts($scripts);
			}

			if ($this->field_registry[$type]['styles']) {
				$styles = $this->field_registry[$type]['styles'];
				$this->enqueueStyles($styles);
			}

	}//getField


	function enqueueScripts($scripts){
		 foreach ($scripts as $scripts => $dependancies) {
		 	$this->scriptQueue($scripts, $dependancies);
		 	$this->initScript();
		 }


	}

	function scriptQueue($scripts, $dependancies){
		wp_enqueue_script($scripts, $dependencies);
	}



	function initScript(){

					add_action('admin_enqueue_scripts', array($this, 'scriptQueue'));
	}



	function enqueueStyles($styles){
		foreach($styles as $key => $style ) {

			$this->styleQueue($style);
			$this->initStyle();

		}//foreach
	}//enqueueStyles

	
	function styleQueue($style){
		
		wp_enqueue_style($style);

	}//styleQueue



	function initStyle(){

					add_action('admin_enqueue_scripts', array($this, 'styleQueue'));
	}//initStyle



}//class

/**************************************************
*
*
* Register Fields
*
*
*****************************************************/

GLOBAL $forms;
$forms = new sicFormHelper();


/************************************
Text Field
**************************************/

$forms->registerField('text', 'sicText');
function sicText($atts){
	echo '<input type="text" value="' . $atts['value'] . '" name="' . $atts['id'] . '" id="' . $atts['id'] . '"/>';}


/************************************
Text Area
**************************************/

$forms->registerField('textarea', 'sicTextArea');
function sicTextArea($atts){
	echo '<textarea rows="4" cols="50" value="" name="' . $atts['id'] .  '" id="' . $atts['id'] . '">' . $atts['value']  . '</textarea>';
}


/************************************
Radio
**************************************/

$forms->registerField('radio', 'sicRadio');
function sicRadio($atts){
	foreach ($atts['options'] as $key => $value) {
		//var_dump($atts);
		//echo '<input type="radio" name="' . $atts['id'] . '" value="' . $value . '"/>' . $key . '<br  />';
	echo '<input type="radio" name="' . $atts['id'] . '" value="' . $key . '" />'. $key . '<br  />';
		if($atts['std'] == $key){echo 'checked="checked"';}

	}
	echo '</input>';
	
}

/************************************
Select
**************************************/

$forms->registerField('select', 'sicSelect');
function sicSelect($atts){
	echo '<select name="' . $atts['name'] . '"">';

	foreach ($atts['options'] as $key => $value) {
		echo '<option value="' . $value . '">' . $key . '</option>';
	}
	echo '</select>';
	
}

/************************************
File
**************************************/

$forms->registerField('file', 'sicFile', array('jquery-ui-datepicker'=>'jquery') , array('jqueryDataPickerStyle'));
function sicFile($atts){

	echo '<input type="file" name="' . $atts['id'] . '" id="' . $atts['id'] . '"/>';

}

/************************************
Date
**************************************/

$forms->registerField('date', 'sicDate', array('jquery-ui-datepicker'=>'jquery'), array('jqueryDataPickerStyle', 'jquery-ui-datepicker'));
function sicDate($atts){
echo '<input type="text" value="' . $atts['value'] . '"  class="datepicker" name="' . $atts[id] . '" id="' . $atts[id] . '"/>';
echo '<script type="text/javascript">jQuery(document).ready(function(){jQuery( "#' . $atts[id]  . '" ).datepicker({dateFormat: "yymmdd"});});</script>';		
				
}
wp_register_style( "jqueryDataPickerStyle", get_bloginfo("stylesheet_directory") . "/framework/admin/css/jquery-ui-1.8.23.custom.css");


/************************************
Editor
**************************************/

$forms->registerField('editor', 'sicEditor');
function sicEditor($atts){
wp_editor( $post->post_content, 'content-id', array( 'textarea_name' => 'content', 'media_buttons' => false, 'tinymce' => array( 'theme_advanced_buttons1' => 'formatselect,forecolor,|,bold,italic,underline,|,bullist,numlist,blockquote,|,justifyleft,justifycenter,justifyright,justifyfull,|,link,unlink,|,spellchecker,wp_fullscreen,wp_adv' ) ) ); 
	
}
wp_register_style( "jqueryDataPickerStyle", get_bloginfo("stylesheet_directory") . "/framework/admin/css/jquery-ui-1.8.23.custom.css");


// $forms->getField('radio', array('id' => 'phone_number', 'std' => 'This is a text field', 'options' => array('1' => '2' ,'2' => '3','3' => '')));
//$forms->getField('select', array('id' => 'phone_number', 'std' => 'This is a text field', 'options' => array('1' => '2' ,'2' => '3','3' => '22')));
// $forms->getField('text', array('id' => 'phone_number', 'std' => 'This is a text field', 'options' => array('1' => '2' ,'2' => '3','3' => '')));
// $forms->getField('textarea', array('id' => 'phone_number', 'std' => 'This is a text field', 'options' => array('1' => '2' ,'2' => '3','3' => '')));
// $forms->getField('file', array('id' => 'phone_number', 'std' => 'This is a text field', 'options' => array('1' => '2' ,'2' => '3','3' => '')));
// $forms->getField('date', array('id' => 'phone_number', 'std' => 'This is a text field', 'options' => array('1' => '2' ,'2' => '3','3' => '')));;

