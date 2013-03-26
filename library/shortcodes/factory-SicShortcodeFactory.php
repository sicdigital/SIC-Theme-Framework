<?php
/**
 * SicShortcodeFactory Class
 *
 * The purpose of the sic shortcode factory is to register each of the shortcodes extended
 * from the abstract shortcode class. The abstract shortcode class is the blueprint
 * for creating shortcode labels and adding a function to control the output. 
 *
 * @package SicFramework
 * @author Michael Chacon
 **/

class SicShortcodeFactory
{
	/**
	 * This is what the variable does. The var line contains the type stored in this variable.
	 * @var string
	 */
	protected $_shortcodeRegistry;
	
	/**
	 * This is what the variable does. The var line contains the type stored in this variable.
	 * @var string
	 */
	 protected $_factory;


    /**
     * _construct(array $shortcodeDefinitions = array())
     *
     * Takes in all the defined shortcodes loops through them and registers each.
     * @return NULL
     * @author Michael Chacon
     **/ 
	
     public function __construct(array $shortcodeDefinitions = array())
	    {
			//loop through shortode definitions and run each through add shortcode method.
			foreach ($shortcodeDefinitions as $callback => $name) {
				$this->addShortcode($name, $callback);
			}
			//sets the registryShortcodes method to run on init hook
	        add_action('init', array($this, 'registryShortcodes'));
		
			// TODO: hack. remove eventually.
			//remove_filter('the_content', 'wpautop');
	    }//_construct
        
   


    /**
     * registryShortcodes()
     *
     * 
     * @return NULL
     * @author Michael Chacon
     **/ 
     public function registryShortcodes()
         {
    		//loops through shortcoderegistry 
            foreach ($this->shortcodeRegistry as $callback => $name) {

                if ( is_int($callback) ) {
                 
                    $this->_factory[$name] = null;

                    add_shortcode($name, array($this, 'defaultShortcodeOutput'));

                } elseif ( is_callable($callback) ) {

                    $this->_factory[$name] = $callback;

                    add_shortcode($name, $callback);

                } elseif ( class_exists($callback) ) {

                    $this->_factory[$name] = new $callback;

                    add_shortcode($name, array($this->_factory[$name], 'do_shortcode'));
                }
            }
         }//registryShortcodes


   


    /**
     * defaultShortcodeOutput()
     *
     * @return NULL
     * @author Michael Chacon
     **/    
   
        public function defaultShortcodeOutput($atts = array(), $content = '')
        {
            return $content;
        }//defaultShortcodeOutpub
    	
    	
   

    /**
     * addShortcode
     *
     * Public API
     *
     * @return NULL
     * @author Michael Chacon
     **/    
       
        public function addShortcode($name, $callback = null)
        {
            if ( $callback ) {
                $this->shortcodeRegistry[$callback] = $name;
            } else {
                $this->shortcodeRegistry[] = $name;
            }

            return $this;
        }//addShortcode
    
    }//class SicShortCodeFactory    



/*
[bar color="yellow" /]

[images id="boo" speed="900" animate="fade"]
http://lorempixel.com/400/200/
http://lorempixel.com/400/200/
http://lorempixel.com/400/200/
[/images]

[images id="boo" speed="900" animate="fade"]
[baz]<img src="http://lorempixel.com/400/200/" />[/baz]
[baz]<img src="http://lorempixel.com/400/200/" /><p>this is a caption</p>[/baz]
[/images]

[text]chea![/text] 
*/
