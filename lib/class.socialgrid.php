<?php
/**
 * @package WordPress
 * @subpackage SocialGrid
 */

class SG {
    public $default_services = array(
        "delicious" => array(
            "name" => "Delicious",
            "text" => "My Delicious Bookmarks",
            "url" => "delicious.com/%s"),

        "deviantart" => array(
            "name" => "deviantART",
            "text" => "My deviantART Profile",
            "url" => "%s.deviantart.com"),

        "digg" => array(
            "name" => "Digg",
            "text" => "My deviantART Profile",
            "url" => "digg.com/users/%s"),

        "facebook" => array(
            "name" => "Facebook",
            "text" => "My Facebook Profile",
            "url" => "facebook.com/%s"),

        "flickr" => array(
            "name" => "Flickr",
            "text" => "My Flickr Photos",
            "url" => "flickr.com/photos/%s"),

        "lastfm" => array(
            "name" => "Last.fm",
            "text" => "My Last.fm Scrobbles",
            "url" => "last.fm/user/%s"),

        "linkedin" => array(
            "name" => "LinkedIn",
            "text" => "My LinkedIn Profile",
            "url" => "linkedin.com/in/%s"),

        "myspace" => array(
            "name" => "MySpace",
            "text" => "My MySpace Profile",
            "url" => "myspace.com/%s"),

        "rss" => array(
            "name" => "RSS",
            "text" => "My RSS Feed",
            "url" => "%s"),

        "stumbleupon" => array(
            "name" => "StumbleUpon",
            "text" => "My StumbleUpon Profile",
            "url" => "www.stumbleupon.com/stumbler/%s"),

        "tumblr" => array(
            "name" => "Tumblr",
            "text" => "My Tumblr",
            "url" => "%s.tumblr.com"),

        "twitpic" => array(
            "name" => "TwitPic",
            "text" => "My TwitPic Photos",
            "url" => "twitpic.com/photos/%s"),

        "twitter" => array(
            "name" => "Twitter",
            "text" => "My Tweets",
            "url" => "twitter.com/%s"),

        "vimeo" => array(
            "name" => "Vimeo",
            "text" => "My Vimeo Videos",
            "url" => "vimeo.com/%s"),

        "youtube" => array(
            "name" => "YouTube",
            "text" => "My YouTube Videos",
            "url" => "youtube.com/user/%s"),
    );
    
    function __construct($settings) {
        $this->settings = $settings;
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
    function inline_service_list() {
        $services = array();

        foreach ($this->default_services as $default => $value) {
            $services[$default] = false;
        }
        
        if ($this->services) {
            foreach ($this->services as $service => $value) {
                $services[$service] = true;
            }
        }
        
        return $services;
    }
    
    function create_grid_item($service) {
        return '<li class="socialgrid-item '.$service->slug.'">'.$service->name.'</li>';
    }
    
    function render_buttons() {
        if ($this->services) {
            // Create a temporary array of the items
            foreach ($this->services as $service) {
                $items[$service->index] = $this->create_grid_item($service);
            }

            // Sort the items by the index
            ksort($items); 

            // Spit 'em out
            echo join($items, "\n");
        } else {
            return false;
        }
    }
    
    function show_add_button() {
        return (count($this->services) < 15) ? true : false;
    }
}
?>