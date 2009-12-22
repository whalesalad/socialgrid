<?php
/**
 * @package WordPress
 * @subpackage SocialGrid
 */

class SG {
    public $default_services = array(
        "brightkite" => array(
            "name" => "Brightkite",
            "text" => "My Brightkite Profile",
            "url" => "brightkite.com/people/%s"),

        "dopplr" => array(
            "name" => "Dopplr",
            "text" => "My Dopplr Trips",
            "url" => "www.dopplr.com/traveller/%s"),
        
        "friendfeed" => array(
            "name" => "Friendfeed",
            "text" => "My Friendfeed Profile",
            "url" => "friendfeed.com/%s"),
        
        "picasa" => array(
            "name" => "Picasa Web",
            "text" => "My Picasa Web Albums",
            "url" => "picasaweb.google.com/%s"),
            
        "google" => array(
            "name" => "Google Profile",
            "text" => "My Google Profile",
            "url" => "www.google.com/profiles/%s"),
        
        "ember" => array(
            "name" => "Ember",
            "text" => "My Ember Images",
            "url" => "emberapp.com/%s"),
            
        "qik" => array(
            "name" => "Qik",
            "text" => "My Qik Videos",
            "url" => "qik.com/%s"),
        
        "readernaut" => array(
            "name" => "Readernaut",
            "text" => "My Books on Readernaut",
            "url" => "readernaut.com/%s"),
        
        "reddit" => array(
            "name" => "Reddit",
            "text" => "My Reddit Profile",
            "url" => "www.reddit.com/user/%s"),
        
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
            "text" => "Stories I Digg",
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
            "url" => "www.linkedin.com/in/%s"),

        "myspace" => array(
            "name" => "MySpace",
            "text" => "My MySpace Profile",
            "url" => "myspace.com/%s"),

        "posterous" => array(
            "name" => "Posterous",
            "text" => "My Posterous",
            "url" => "%s.posterous.com"),

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

        "twitter" => array(
            "name" => "Twitter",
            "text" => "My Tweets",
            "url" => "twitter.com/%s"),

        "vimeo" => array(
            "name" => "Vimeo",
            "text" => "My Vimeo Videos",
            "url" => "vimeo.com/%s"),
        
        "viddler" => array(
            "name" => "Viddler",
            "text" => "My Viddler Videos",
            "url" => "viddler.com/explore/%s"),

        "virb" => array(
            "name" => "Virb",
            "text" => "My Virb Profile",
            "url" => "virb.com/%s"),

        "youtube" => array(
            "name" => "YouTube",
            "text" => "My YouTube Videos",
            "url" => "youtube.com/user/%s"),
    );
    
    function __construct($settings) {
        $this->settings = $settings;
        $this->services = $this->settings->services;
    }
    
    function create_button($service) {
        $prefix = ($service->slug == 'rss') ? '' : 'http://';
        return '<li class="button '.$service->slug.'"><a href="'.$prefix.$service->url.'" target="_blank" title="'.$service->description.'">'.$service->description.'</a></li>';
    }
    
    function display() {
        $services = $this->services;

        if ($services) {
            $size = ($this->settings->enable_small_icons) ? "mini" : "standard";
            
            echo '<ul id="socialGrid" class="'.$size.'">';
            
            $grid_items = array();
            
            // Create a temporary array of the items
            foreach ($services as $service) {
                $grid_items[$service->index] = $this->create_button($service);
            }

            // Sort the items by the index
            ksort($grid_items);

            // Spit 'em out
            echo join($grid_items, "\n");
            
            echo '</ul>';
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