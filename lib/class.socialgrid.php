<?php
/**
 * @package WordPress
 * @subpackage SocialGrid
 */

class SG {
    function __construct() {
        $this->settings = new SocialGridSettings();
        $this->services = $this->settings->services;
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

class SGAdmin extends SG {
    function create_grid_item($service) {
        // name, description, url
        // <li class="socialgrid-item add">+</li>
        $item = '<li class="socialgrid-item '.$service->slug.'">'.$service->name.'</li>';
        return $item;
    }
    
    function render_buttons() {
        foreach ($this->services as $service) {
            $items[] = $this->create_grid_item($service);
        }
        echo join($items, "\n");
    }
    
    function show_add_button() {
        if (count($this->services) < 15) {
            return true;
        } else {
            return false;
        }
    }
}
?>