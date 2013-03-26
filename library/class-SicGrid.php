<?php

class GridShortcode
{
    public function __construct()
    {
        add_action('init', array($this, 'add_shortcode'));
    }

    public function add_shortcode()
    {
        add_shortcode('grid', array($this, 'do_shortcode'));
        add_shortcode('column', array($this, 'do_shortcode_column_element'));
        // remove_filter('the_content', 'wpautop');
    }
    
    public function do_shortcode($atts, $content = null)
    {
        $grid = new Grid($content, $atts);

        return $grid->output();
    }

    public function do_shortcode_column_element($atts, $content = null)
    {
        return $content;
    }
}



/**
 * Grid class
 *
 * @package SIC-Framework
 * @author Michael Chacon
 *
 * The purpose of this class is to output all the html necessary for a grid. 
 * This is great for a gallery, portfolio, or as a way to lay out a grid of images/text.
 * pass an array with content and another with args.
 *
 *
 */

class Grid
{
    private $id;
    public $settings = array();
    public $content = array();
    
    public function getId()
    {
        return $this->id;
    }
    
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }
    
    public function setContent($content)
    {
		// if the content is a string run it as a shortcode.
        if (is_string($content)) {
            $content = do_shortcode($content);
            
			//removes spaces from the beginning and/or end of the content
            if ($content != strip_tags($content)) {

                preg_match_all(' / ( . * ?) \ n / i', $content, $matches);
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

    public function setOptions(array $settings)
    {
        $this->settings = $settings;
        
        return $this;
    }

    public function __construct($content, $args = array())
    {
        if ( ! $content ) {
            return;
        }

        $atts = shortcode_atts(
			array(
			'id' => 'grid_' . rand(5, 15),
			'columns' => '3',
            'masonry' => FALSE,
            'isotope' => FALSE,
            'filters' => 'all'

		), $args);
        
        $this->setId($atts['id'])
            ->setOptions($atts)
            ->setContent($content);

        return $this;
    }

    
    public function output()
    {
        $content = $this->getContent();
        $id = $this->getId();
		$columns = $this->settings[columns];
        $output = '';
        if (is_array($content)) {

			$i = 1;
			$row = $columns + 1;
			$count = count($content);
	
			//wrap it in a tag
			echo '<div id="' . $id . '" class="grid columns_' . $columns . '">';
			//each new array item lock and load
            if($this->settings['masonry'] == TRUE){
                echo '<script>jQuery(window).load(function(){jQuery("#' . $id . '").masonry({itemSelector : ".column"});});</script>';

                 foreach($content as $key => $value){
                       
                                ?>
                                    <div class="column<?php if ($i % $columns == 0){echo ' last'; }?>">     
                                        <div class="item">
                                            <?php echo $value;?>
                                    </div><!--item-->
                                    </div><!--column-->
                                        
                            <?php   
                    
                            $i++;
                            }//foreach
            }
            if($this->settings['isotope'] == TRUE){
                echo '<script>jQuery(window).load(function(){jQuery("#' . $id . '").isotope({itemSelector : ".column"});});

                    jQuery("#filters a").click(function(){
                      var selector = jQuery(this).attr("data-filter");
                      jQuery("#' . $id . '").isotope({ filter: selector });
                      return false;});

                </script>';

                 foreach($content as $key => $value){
                                ?>
                                <?php echo '<div class="column">'?>        
                                   <?php echo $value;?>
                                        <?php echo '</div>'?>
                            <?php   
                    
                            $i++;
                            }//foreach
            }
else{
                foreach($content as $key => $value){
					
				//for the first pass add the row or
				if( $i == 1 || $i % $columns == 1 ){
					echo '<div id="" class="row cf">';
						
				}
	
				?>
					<div class="column <?php if ($i % $columns == 0){echo 'last'; }?>">		
						
							<?php echo $value;?>
				

					</div><!--column-->
						
			<?php	
	
			$i++;
	
			if($i % $columns == 1 || $i == $count + 1){
					echo '</div><!--row-->';
						
				}		
			}//foreach

}//else

			echo '</div>';
			

        return $output;
    }
	}
}
$GridShortcode = new GridShortcode;