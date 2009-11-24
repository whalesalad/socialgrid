<?php
/**
 * @package WordPress
 * @subpackage SocialGrid
 */

$default_services = array(
    "delicious" => array(
        "name" => "Delicious",
        "text" => "My Delicious Bookmarks",
        "url" => "delicious.com/%s"),

    "deviantart" => array(
        "name" => "deviantART",
        "text" => "My deviantART Profile",
        "url" => "%s.delicious.com/"),

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
        "url" => "{$rss_url}"),

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

function get_default_service($service) {
    global $default_services;
    return $default_services[$service];
}

class SocialGridService {
    public $name = null;
    public $description = null;
    public $url = null;
    
    function __construct($type, $username) {
        $skeleton = get_default_service($type);
        
        $this->slug = $type;
        $this->name = $skeleton['name'];
        $this->description = $skeleton['text'];
        $this->url = $this->construct_url($skeleton['url'], $username);
    }
    
    private function construct_url($format, $username) {
        return sprintf($format, $username);
    }
}


?>