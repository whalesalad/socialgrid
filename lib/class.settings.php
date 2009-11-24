<?php
/**
 * @package WordPress
 * @subpackage SocialGrid
 */

class SocialGridSettings {
    function __construct() {
        $this->get_settings();
    }
    
    function get_settings() {
        $saved_settings = maybe_unserialize(get_option('socialgrid_settings'));

        if (!empty($saved_settings) && is_object($saved_settings)) {
            foreach ($saved_settings as $setting => $value)
                $this->$setting = $value;
        } else {
            $this->reset();
        }
    }
    
    function save_settings() {
        $default_settings = $this->defaults();
        
        foreach ($default_settings as $setting => $value) {
            if (!isset($this->$setting))
                $this->$setting = $default_settings->$setting;
        }
        
        update_option('socialgrid_settings', $this);
    }
    
    function reset() {
        update_option('socialgrid_settings', NULL);
    }
}

?>