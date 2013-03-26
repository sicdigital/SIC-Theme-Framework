<?php
/**
 * Contains methods for customizing the theme customization screen.
 * 
 * @link http://codex.wordpress.org/Theme_Customization_API
 * @since MyTheme 1.0
 */
 
  class customSection{
	 
     public $custom_section_name;
 
 	 public $custom_section_args;
	 	 
 	 public $custom_section_fields;
      
	
	
		/**
		* __construct sets everything in motion. 
		*
		* @return $this
		* @author Michael Chacon
		**/
	  
		  function __construct( $name, $args = array())
      
			{
				$this->custom_section_fields = array();
			
				$this->setSectionArgs($args)
		
				->setSectionID($name)
		
				->setSectionDefaultArgs()
		
				->customize_register(array(&$this, "addSection"));
			}



		/**
		* setSectionArgs - sets the section argument variable to whatever the user passed in
		*
		* @return $this
		* @author Michael Chacon
		**/
		
			function setSectionArgs($args){

				$this->custom_section_args = (array)$args;
			
			return $this;
		}
		
		
		/**
		* setSectionDefaultArgs - sets any values the user left out of the section args with defaults
		*
		* @return $this
		* @author Michael Chacon
		**/
		
			function setSectionDefaultArgs(){
				 
				 $defaults = array( 
					   'title' => __( 'MyTheme Options', $name), //Visible title of section
				       'priority' => 35, //Determines what order this appears in
				       'default' => TRUE,
					   'capability' => 'edit_theme_options', //Capability needed to tweak
				       'description' => __('Allows you to customize some example settings for MyTheme.', $name ), //Descriptive tooltip
				    );
			     
				 $args = array_merge($this->custom_section_args, $defaults);	

			 return $this;
			}
 		
		
		/**
 		* setSectionID - this sets the unique ID for each section
 		*
 		* @return $this
 		* @author Michael Chacon
 		**/
		
			function setSectionID($name){
			
				$this->custom_section_name = $name;
				$this->custom_section_name = strtolower($this->custom_section_name);
		
				return $this;
			}
	
	 
		
		/**
 		* addSection - this sets the unique ID for each section
 		*
 		* @return $this
 		* @author Michael Chacon
 		**/
	 
	 	 function addSection($wp_customize){
	         $wp_customize->add_section( $this->custom_section_name, $this->custom_section_args );
			 $this->addFields($wp_customize);
	 	 }
	 
	 
	 
	 
 		/**
  		* registerField
  		*
  		* @return $this
  		* @author Michael Chacon
  		**/
	 
		 function registerField($name, $options = array()){
			$field =  array($name => $options);
			 $this->custom_section_fields  = array_merge($this->custom_section_fields, $field);
		 	
		 }
	
	
 		/** 
  		* addFields
  		*
  		* @return $this
  		* @author Michael Chacon
  		**/
	 
		function addFields($wp_customize){
			 
		foreach($this->custom_section_fields as $name => $options){
			  
			//add_settings
			$wp_customize->add_setting( $this->custom_section_name . '[' .  $name . ']' , //Give it a SERIALIZED name (so all theme settings can live under one db record)

				array(
					'default' => $options['default'],
					'type' => 'theme_mod', //Is this an 'option' or a 'theme_mod'?
					'capability' => 'edit_theme_options', //Optional. Special permissions for accessing this setting.
					'transport' => 'refresh', //What triggers a refresh of the setting? 'refresh' or 'postMessage' (instant)?
				));
            
				$this->setControl($wp_customize);
			}//foreach
			 
		}//addFields

		
	
 		/** 
  		* setControl
  		*
  		* @return $this
  		* @author Michael Chacon
  		**/
	 
		function setControl($wp_customize){
			
			foreach($this->custom_section_fields as $name => $options){
				
				switch ($options['type']) {
					case 'colorpicker':
					
			        $wp_customize->add_control( new WP_Customize_Color_Control( //Instantiate the color control class
			           $wp_customize, //Pass the $wp_customize object (required)
			           $this->custom_section_name . '[' . $name . ']', //Set a unique ID for the control
			           array(
			              'label' => __( $name, 'mytheme' ), //Admin-visible name of the control
			              'section' => $this->custom_section_name, //ID of the section this control should render in (can be one of yours, or a WordPress default section)
			              'settings' => $this->custom_section_name . '[' . $name . ']', //Which setting to load and manipulate (serialized is okay)
			              'priority' => 10, //Determines the order this control appears in for the specified section
			           ) 
			        ) );
						break;
						
						case 'upload':
				        $wp_customize->add_control( new WP_Customize_Upload_Control( //Instantiate the color control class
				           $wp_customize, //Pass the $wp_customize object (required)
				           $this->custom_section_name . '[' . $name . ']', //Set a unique ID for the control
				           array(
				              'label' => __(  $name , 'mytheme' ), //Admin-visible name of the control
				              'section' => $this->custom_section_name, //ID of the section this control should render in (can be one of yours, or a WordPress default section)
				              'settings' => $this->custom_section_name . '[' . $name . ']', //Which setting to load and manipulate (serialized is okay)
				              'priority' => 10, //Determines the order this control appears in for the specified section
				           ) 
				        ) );
							
							break;
							
							case 'image':
					        $wp_customize->add_control( new WP_Customize_Image_Control( //Instantiate the color control class
					           $wp_customize, //Pass the $wp_customize object (required)
					           $this->custom_section_name . '[' . $name . ']', //Set a unique ID for the control
					           array(
					              'label' => __($name, 'mytheme' ), //Admin-visible name of the control
					              'section' => $this->custom_section_name, //ID of the section this control should render in (can be one of yours, or a WordPress default section)
					              'settings' => $this->custom_section_name . '[' . $name . ']', //Which setting to load and manipulate (serialized is okay)
					              'priority' => 10, //Determines the order this control appears in for the specified section
					           ) 
					        ) );
							
								break;
								
								
								case 'background_image':
						        $wp_customize->add_control( new WP_Customize_Background_Image_Control( //Instantiate the color control class
						           $wp_customize, //Pass the $wp_customize object (required)
						           $this->custom_section_name . '[' . $name . ']', //Set a unique ID for the control
						           array(
						              'label' => __( $name, 'mytheme' ), //Admin-visible name of the control
						              'section' => $this->custom_section_name, //ID of the section this control should render in (can be one of yours, or a WordPress default section)
						              'settings' => $this->custom_section_name . '[' . $name . ']', //Which setting to load and manipulate (serialized is okay)
						              'priority' => 10, //Determines the order this control appears in for the specified section
						           ) 
						        ) );
							
									break;
								
								
									case 'header_image':
							        $wp_customize->add_control( new WP_Customize_Header_Image_Control( //Instantiate the color control class
							           $wp_customize, //Pass the $wp_customize object (required)
							           $this->custom_section_name . '[' . $name . ']', //Set a unique ID for the control
							           array(
							              'label' => __( $name, 'mytheme' ), //Admin-visible name of the control
							              'section' => $this->custom_section_name, //ID of the section this control should render in (can be one of yours, or a WordPress default section)
							              'settings' => $this->custom_section_name . '[' . $name . ']', //Which setting to load and manipulate (serialized is okay)
							              'priority' => 10, //Determines the order this control appears in for the specified section
							           ) 
							        ) );
							
										break;
								
					
					default:
					$wp_customize->add_control( $this->custom_section_name . '[' . $name . ']' ,
						array(
							'label'   =>   $name,
							'type' => $options['type'],
							'section' =>   $this->custom_section_name,
							'choices' => $options['choices']
						));
						break;
				}
				
		
				}//foreach
		}//setControl




		function getFieldType($wp_customize){
			foreach($this->custom_section_fields as $name => $options){
				
	
					
				}//foreach
			return $type;
		}




 	 /**
       * Helper method, that attaches a passed function to the 'init' WP action
       * @param function $cb Passed callback function.
       */
     
 		 function customize_register($cb)
 	     {
 	         add_action("customize_register", $cb);
		
 	     }
	 
	 

	 }//end class
	
	 function mytheme_customize_css()
	 {
	     
		 $sic_mods = get_theme_mods();
		 ?>
		 
	          <style type="text/css">
	              h1 { color:<?php echo $sic_mods['example']['colorpicker'] ?>; }
	          </style>
	     <?php
	 }
	 add_action( 'wp_head', 'mytheme_customize_css');
/*Example Use*/
	$exampleSection = new customSection('example', 
	array(
	'title' => __( 'Contact Information', 'mytheme' ), //Visible title of section
	'priority' => 35, //Determines what order this appears in
	'capability' => 'edit_theme_options', //Capability needed to tweak
	'description' => __('Allows you to customize some example settings for MyTheme.', 'mytheme'), //Descriptive tooltip
	));

	$exampleSection->registerField('Upload', 
	array(
	'type'=>'upload',
	'default'=>'Good2!',
	'choices' => array('12' => '1','21' => '21','1' => '221'))
	);


	$exampleSection->registerField('colorpicker', 
	array(
	'type'=>'colorpicker',
	'default'=>'Good!')
	);
	$exampleSection->registerField('Image', 
	array(
	'type'=>'image',
	'default'=>'Good!')
	);

	$exampleSection->registerField('backgroundimage', 
	array(
	'type'=>'background_image',
	'default'=>'Good!')
	);
	$exampleSection->registerField('headerimage', 
	array(
	'type'=>'header_image',
	'default'=>'Good!')

	);
	$exampleSection->registerField('radio', 
	array(
	'type'=>'radio',
	'default'=>'Good!',
	'choices' => array('12' => '1','21' => '21','1' => '221'))

	);

	$exampleSection->registerField('select', 
	array(
	'type'=>'select',
	'default'=>'Good!',
	'choices' => array('12' => '1','21' => '21','1' => '221'))

	);

	$exampleSection->registerField('checkbox', 
	array(
	'type'=>'checkbox',
	'default'=>'Good!',
	'choices' => array('12' => '1','21' => '21','1' => '221'))

	);

	$exampleSection->registerField('dropdown-pages', 
	array(
	'type'=>'dropdown-pages',
	'default'=>'Good!',
	'choices' => array('12' => '1','21' => '21','1' => '221'))

	);			 
 
