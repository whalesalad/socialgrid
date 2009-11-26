<?php
/**
 * @package WordPress
 * @subpackage SocialGrid
 */

class SocialGridSettings {
    function __construct() {
        $this->fetch();
    }
    
    function fetch() {
        $saved_settings = maybe_unserialize(get_option('socialgrid_settings'));

        if (!empty($saved_settings) && is_object($saved_settings)) {
            foreach ($saved_settings as $setting => $value)
                $this->$setting = $value;
        }
    }
    
    function save() {
        update_option('socialgrid_settings', $this);
    }
    
    function reset() {
        update_option('socialgrid_settings', NULL);
    }
}

?>