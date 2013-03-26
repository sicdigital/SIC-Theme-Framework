<?php

/**
 * SicImages
 *
 * Creates the [image][/image] shortcode 
 *
 * @package SicFramework
 * @author Michael Chacon
 **/

class SicImages extends SicShortcode
{
    private $_htmlElementIdPrefix = 'images_';

    public function output()
    {
        $content = $this->getContent();

        $output = '';

        if ( is_array($content) ) {
            foreach ($content as $image) {
                $output .= '<img src="'. esc_url(trim($image)) .'" />' . "\n";
            }
        }
        
        return $output;
    }
}


/**
 * SicText
 *
 *
 * @package SicFramework
 * @author Michael Chacon
 **/

class SicText extends SicShortcode
{
    private $_htmlElementIdPrefix = 'text_';
    
    public function setContent($content)
    {
        $this->content = $content;
    }

    public function output()
    {
        $content = $this->getContent();

        return '[text]' . $content . '[text]';
    }
}


/**
 * SicBar
 * 
 *
 * @package SicFramework
 * @author Michael Chacon
 **/
class SicBar extends SicShortcode
{
    private $_htmlElementIdPrefix = 'bar_';

    public function init()
    {
        add_action('wp_footer', array($this, 'outputJsOptions'));
    }

    public function output()
    {
        $content = $this->getContent();
        
        $atts = $this->getAttributes();
        
        if ($atts['color']) {

            return '<hr style="height:10px;background-color:'. $atts['color'] .'"]';

        }

        return '[bar]';
    }

    public function getDefaultAtts()
    {
        $this->defaultAtts = array(
            'width' => '100%',
            'height' => '100px',
            'color' => null,
        );

        return $this->defaultAtts;
    }


    public function outputJsOptions()
    {
        // echo '<!-- output js settings, yarrr! -->';

        // echo var_export($this->getAttributes());
    }
}//sicbar




/*
 *
 * SicVideo
 * 
 *
 * @package SicFramework
 * @author Michael Chacon
 **/
class SicVideo extends SicShortcode
{
    private $_htmlElementIdPrefix = 'video_';

    public function init()
        {
            add_action('wp_footer', array($this, 'outputJsOptions'));
        }


    public function setContent($content){
        $youtubeRegex = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i';
            
        //if only a string came through use it as the video
        if (is_string($content)) {
            $isYoutubeVideo= preg_match($youtubeRegex, $content );
            $urlPathInfo = pathinfo($content);

            if($urlPathInfo['extension']=='ogv'){
                $videoType = 'ogg';
             }

            else {
                $videoType = $urlPathInfo['extension'];  
            }

        $output .= '<video id="' . $atts['id'] . '" class="video-js vjs-default-skin" controls  preload="auto" width="100%" height="500" poster="http://video-js.zencoder.com/oceans-clip.png" data-setup=\'{"techOrder":["html5","youtube"],"ytcontrols":false}\'>';            
                               
                                if ($isYoutubeVideo == 1){$output .= '<source src="' . $content . '" type="video/youtube" />';}
                                else{ $output .= '<source src="' . $content . '" type="video/mp4"/>';}

        $output .=  '</video><!--video_player-->';

        }//if is_strong 
        
    $this->content = $output;
    return $this;
    }//setContent



    function output()
    {
        $content = $this->getContent();
        $atts = $this->getAttributes();

        return $content;
    }


     function getDefaultAtts()
    {
        $this->defaultAtts = array(
            'mp4' => 'http://video-js.zencoder.com/oceans-clip.mp4',
            'ogg' => 'http://video-js.zencoder.com/oceans-clip.mp4',
            'mov' => 'http://video-js.zencoder.com/oceans-clip.mp4'
        );

        return $this->defaultAtts;
    }



     function outputJsOptions() {
            wp_enqueue_style( "videojs_styls",  get_bloginfo('template_directory') . "/framework/packages/video-js_Youtube/video-js.css", '', "3.2.0", false);
            wp_enqueue_script( "videojs",  get_bloginfo('template_directory') . "/framework/packages/video-js_Youtube/video.js", '', "3.2.0", false);

    }


}//sicbar




$SicShortcodeFactory = new SicShortcodeFactory(
    array(
    // shortcode with custom output.
    'SicImages' => 'images',
    'SicText' => 'text',
    'SicVideo' => 'video',

));

$SicShortcodeFactory->addShortcode('bar', 'SicBar');


//$sicText = new SicText(array(), 'this is madness');
//echo $sicText->output();

