<?php
/**
* Class SicShortCode
* This class is made for expanding!
*
* 
* @author Michael Chacon & Ptah Dunbar
**/ 

abstract class SicShortcode

{
    
    private $_htmlElementIdPrefix;
   
    public $content;
    
    public $atts;
    
   
    /**
    * __construct
    *
    * Sets the properties for the object and runs init
    *
    * @return $this
    * @author Michael Chacon
    **/ 
    public function __construct($atts = array(), $content = '')
        {
            $this->setAttributes($atts)
                ->setContent($content);

            $this->init();

            return $this;
        }


   /**
    * setContent
    * Public API - Override this function to set the content of the shortcode.
    *
    * @return $content
    * @author Michael Chacon
    **/  
    public function setContent($content)
        {
            if (is_string($content)) {
                $content = do_shortcode($content);
        
                if ($content != strip_tags($content)) {
                    preg_match_all('/(.*?)\n/i', $content, $matches);
                } else {
                    preg_match_all('/(http|https):\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(.*?)\n/i', $content, $matches);
                }
        
                $content = $matches[0];
        
                foreach ($content as $key => $value) {
                    $value = trim($value);
                    if (empty($value)) unset($content[$key]);
                }
            }

            $this->content = $content;
            
            return $this;
        }

   /**
    * getDefaultAtts
    * Public API - Override te set the default attributes
    *
    * @return NULL
    * @author Michael Chacon
    **/
    public function getDefaultAtts()
        {
            return array();
        }
      
   /**
    * setAttributes
    * Merge default attributes with user inputed attributes, return to shortcode object. 
    *
    * @return $this
    * @author Michael Chacon
    **/ 
    public function setAttributes($atts = array())
        {
            //shortcode_atts merges two arrays to set shortcode attribites
            $atts = shortcode_atts($this->getDefaultAtts(), $atts);
            
            //if ID isn't set within the atts generate one
            if ( ! isset($atts['id']) ) {
                $atts['id'] = $this->generateDefaultId();
            }

            //set object attributes
            $this->atts = $atts;

            return $this;
        }
    

  /**
    * getContent
    * Helper function used to ouput the content
    *
    * @return $content
    * @author Michael Chacon
    **/  
    public function getContent()
        {
            return $this->content;
        }


  /**
    * getAttributes
    * Helper function used to return the attributes
    *
    * @return $content
    * @author Michael Chacon
    **/  
    public function getAttributes()
        {
            return $this->atts;
        }


	/**
     * generateDefaultId
 	 * Generates a random number prefixed to use as a default if no ID is provided
     *
     * @return $default_id
     * @author Michael Chacon
     **/ 
     public function generateDefaultId()
            {
                return $this->_htmlElementIdPrefix . rand(5, 15);
            }
      
  
    /**
     * do_shortcode
 	 * Outputs the content of the shortcode - can only be run from within the loop 
	 *
     * @return $output
     * @author Michael Chacon
     **/ 
     public function do_shortcode($atts = array(), $content = '')
		{
	        $this->__construct($atts, $content);

	        return $this->output();
	    }

    // implement what the shortcode should output;
    abstract public function output();


    public function init() {}

}