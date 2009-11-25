<?php
/**
 * @package WordPress
 * @subpackage SocialGrid
 */

global $sg_settings, $sg_admin;

function get_default_service($service) {
    return $sg_admin->default_services[$service];
}

class SocialGridService {
    public $name = null;
    public $description = null;
    public $url = null;
    
    function __construct($type, $username, $index) {
        $skeleton = get_default_service($type);
        
        $this->slug = $type;
        $this->name = $skeleton['name'];
        $this->description = $skeleton['text'];
        $this->url = $this->construct_url($skeleton['url'], $username);
        $this->index = $index;
    }
    
    private function construct_url($format, $username) {
        return sprintf($format, $username);
    }
}


?>