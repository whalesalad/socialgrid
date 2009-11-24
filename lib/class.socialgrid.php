<?php
/**
 * @package WordPress
 * @subpackage SocialGrid
 */
global $SG_SERVICES;

class SG {
    function __construct() {
        $this->settings = new SocialGridSettings();
        $this->default_services = $SG_SERVICES;
    }
    
    function create_button($class, $button) {
        if (!isset($button["url"]))
            return;
            
        echo '<li class="button '.$class.'"><a href="'.$button["url"].'">'.$button["text"].'</a></li>';
    }
    
    function render_buttons($buttons) {
        echo '<ul class="socialButtons">';
        foreach ($buttons as $key => $value) {
            $this->create_button($key, $value);
        }
        echo '</ul>';
    }
}
?>