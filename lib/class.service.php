<?php
/**
 * @package WordPress
 * @subpackage SocialGrid
 */

$SG_SERVICES = array(
    'delicious' => array(
        'name' => 'Delicious',
        'text' => 'My Delicious Bookmarks',
        'url' => 'delicious.com/${username}'),
        
    'deviantart' => array(
        'name' => 'deviantART',
        'text' => 'My deviantART Profile',
        'url' => '${username}.delicious.com/'),
        
    'digg' => array(
        'name' => 'Digg',
        'text' => 'My deviantART Profile',
        'url' => 'digg.com/users/${username}'),
        
    'facebook' => array(
        'name' => 'Facebook',
        'text' => 'My Facebook Profile',
        'url' => 'facebook.com/${username}'),
        
    'flickr' => array(
        'name' => 'Flickr',
        'text' => 'My Flickr Photos',
        'url' => 'flickr.com/photos/${username}'),
        
    'lastfm' => array(
        'name' => 'Last.fm',
        'text' => 'My Last.fm Scrobbles',
        'url' => 'last.fm/user/${username}'),
        
    'linkedin' => array(
        'name' => 'LinkedIn',
        'text' => 'My LinkedIn Profile',
        'url' => 'linkedin.com/in/${username}'),
        
    'myspace' => array(
        'name' => 'MySpace',
        'text' => 'My MySpace Profile',
        'url' => 'myspace.com/${username}'),
        
    'rss' => array(
        'name' => 'RSS',
        'text' => 'My RSS Feed',
        'url' => '${rss_url}'),
        
    'stumbleupon' => array(
        'name' => 'StumbleUpon',
        'text' => 'My StumbleUpon Profile',
        'url' => 'www.stumbleupon.com/stumbler/${username}'),
    
    'tumblr' => array(
        'name' => 'Tumblr',
        'text' => 'My Tumblr',
        'url' => '${username}.tumblr.com'),
    
    'twitpic' => array(
        'name' => 'TwitPic',
        'text' => 'My TwitPic Photos',
        'url' => 'twitpic.com/photos/${username}'),
    
    'twitter' => array(
        'name' => 'Twitter',
        'text' => 'My Tweets',
        'url' => 'twitter.com/${username}'),
    
    'vimeo' => array(
        'name' => 'Vimeo',
        'text' => 'My Vimeo Videos',
        'url' => 'vimeo.com/${username}'),
    
    'youtube' => array(
        'name' => 'YouTube',
        'text' => 'My YouTube Videos',
        'url' => 'youtube.com/user/${username}'),
);

class SocialGridService {
    function __construct() {
        $this->service = 'Meow';
    }
}
?>