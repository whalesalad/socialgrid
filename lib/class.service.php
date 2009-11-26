<?php
/**
 * @package WordPress
 * @subpackage SocialGrid
 */

global $sg_settings;

class SocialGridService {
    public $name = null;
    public $description = null;
    public $url = null;
    
    function __construct($sg_admin, $service, $username, $index) {
        $skeleton = $sg_admin->default_services[$service];
        
        $this->slug = $service;
        $this->name = $skeleton['name'];
        $this->description = $skeleton['text'];
        $this->username = $username;
        $this->url = $this->construct_url($skeleton['url'], $username);
        $this->index = $index;
    }
    
    private function construct_url($format, $username) {
        return sprintf($format, $username);
    }
}


?>