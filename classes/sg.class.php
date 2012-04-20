<?php
/**
 * @package WordPress
 * @subpackage SocialGrid
 */

class SG {
    public $default_services = array(
        "cargo" => array(
            "name" => "Cargo Collective",
            "text" => "Cargo Collective",
            "url" => "cargocollective.com/%s"),

        "dopplr" => array(
            "name" => "Dopplr",
            "text" => "Dopplr Trips",
            "url" => "www.dopplr.com/traveller/%s"),
        
        "dribbble" => array(
            "name" => "Dribbble",
            "text" => "Dribbble Shots",
            "url" => "dribbble.com/%s"),
        
        "picasa" => array(
            "name" => "Picasa Web",
            "text" => "Picasa Web Albums",
            "url" => "picasaweb.google.com/%s"),
            
        "google" => array(
            "name" => "Google+",
            "text" => "Google+ Profile",
            "url" => "plus.google.com/%s"),
        
        "ember" => array(
            "name" => "Ember",
            "text" => "Ember Images",
            "url" => "emberapp.com/%s"),
            
        "qik" => array(
            "name" => "Qik",
            "text" => "Qik Videos",
            "url" => "qik.com/%s"),
        
        "readernaut" => array(
            "name" => "Readernaut",
            "text" => "Books on Readernaut",
            "url" => "readernaut.com/%s"),
        
        "reddit" => array(
            "name" => "Reddit",
            "text" => "Reddit Profile",
            "url" => "www.reddit.com/user/%s"),
        
        "delicious" => array(
            "name" => "Delicious",
            "text" => "Delicious Bookmarks",
            "url" => "delicious.com/%s"),

        "deviantart" => array(
            "name" => "deviantART",
            "text" => "deviantART Profile",
            "url" => "%s.deviantart.com"),

        "digg" => array(
            "name" => "Digg",
            "text" => "Stories I Digg",
            "url" => "digg.com/users/%s"),

        "facebook" => array(
            "name" => "Facebook",
            "text" => "Facebook Profile",
            "url" => "facebook.com/%s"),

        "flickr" => array(
            "name" => "Flickr",
            "text" => "Flickr Photos",
            "url" => "flickr.com/photos/%s"),

        "forrst" => array(
            "name" => "Forrst",
            "text" => "Forrst Profile",
            "url" => "http://forrst.com/people/%s"),

        "lastfm" => array(
            "name" => "Last.fm",
            "text" => "Last.fm Scrobbles",
            "url" => "last.fm/user/%s"),

        "linkedin" => array(
            "name" => "LinkedIn",
            "text" => "LinkedIn Profile",
            "url" => "www.linkedin.com/in/%s"),

        "myspace" => array(
            "name" => "MySpace",
            "text" => "MySpace Profile",
            "url" => "myspace.com/%s"),

        "posterous" => array(
            "name" => "Posterous",
            "text" => "Posterous",
            "url" => "%s.posterous.com"),

        "rss" => array(
            "name" => "RSS",
            "text" => "RSS Feed",
            "url" => "%s"),

        "stumbleupon" => array(
            "name" => "StumbleUpon",
            "text" => "StumbleUpon Profile",
            "url" => "www.stumbleupon.com/stumbler/%s"),

        "tumblr" => array(
            "name" => "Tumblr",
            "text" => "Tumblr",
            "url" => "%s.tumblr.com"),
        
        "technorati" => array(
            "name" => "Technorati",
            "text" => "Technorati Profile",
            "url" => "technorati.com/blogs/%s"),

        "twitter" => array(
            "name" => "Twitter",
            "text" => "Tweets",
            "url" => "twitter.com/%s"),

        "vimeo" => array(
            "name" => "Vimeo",
            "text" => "Vimeo Videos",
            "url" => "vimeo.com/%s"),
        
        "viddler" => array(
            "name" => "Viddler",
            "text" => "Viddler Videos",
            "url" => "viddler.com/explore/%s"),

        "virb" => array(
            "name" => "Virb",
            "text" => "Virb Profile",
            "url" => "virb.com/%s"),

        "youtube" => array(
            "name" => "YouTube",
            "text" => "YouTube Videos",
            "url" => "youtube.com/user/%s"),
    );
    
    function __construct($settings) {
        $this->settings = $settings;
        $this->services = $this->settings->services;
    }
    
    function get_default_desc($service) {
        return $this->default_services[$service->slug]['text'];
    }

    function create_button($service) {
        $prefix = ($service->slug == 'rss') ? '' : 'http://';
        return '<li class="button '.$service->slug.'"><a href="'.$prefix.$service->url.'" target="_blank" title="'.$this->get_default_desc($service).'">'.$this->get_default_desc($service).'</a></li>';
    }
    
    function display() {
        $services = $this->services;

        if ($services) {
            $size = ($this->settings->enable_mini_icons) ? "mini" : "standard";
            
            echo '<li><ul id="socialGrid" class="'.$size.'">';
            
            $grid_items = array();
            
            // Create a temporary array of the items
            foreach ($services as $service) {
                $grid_items[$service->index] = $this->create_button($service);
            }

            // Sort the items by the index
            ksort($grid_items);

            // Spit 'em out
            echo join($grid_items, "\n");
            
            echo '</ul></li>';
        } else {
            return false;
        }
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
                $services[$service] = ($service == 'rss') ? true : $value->username;
            }
        }
        
        return $services;
    }
    
    function create_grid_item($service) {
        return '<li class="socialgrid-item '.$service->slug.'">'.$this->get_default_desc($service).'</li>';
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