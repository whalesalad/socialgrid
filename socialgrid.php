<?php
/*
Plugin Name: SocialGrid
Plugin URI: http://whalesalad.com/socialgrid
Description: SocialGrid makes it easy to include attractive links to your various social media profiles on the web.
Version: 0.1
Author: Michael Whalen
Author URI: http://whalesalad.com
*/

define('WP_DEBUG', true);

define('SG_VERSION', 0.1);
define('SG_NAME', 'SocialGrid');
define('SG_SLUG', 'socialgrid');

add_action('admin_menu', SG_SLUG.'_add_options_page');
add_action('admin_post_'.SG_SLUG.'_save', SG_SLUG.'_save_options');
add_action('init', SG_SLUG.'_settings_head');

// if ($_GET['activated'])
//     tasty_activate_theme();

function socialgrid_add_options_page(){
    add_theme_page(__(SG_NAME.' Options', SG_SLUG), __(SG_NAME.' Options', SG_SLUG), 'edit_themes', SG_SLUG.'-options', SG_SLUG.'_options_admin');
}

function socialgrid_settings_head() {
    // wp_enqueue_style('tasty-settings-stylesheet', TASTY_STATIC . '/admin.css');
    // wp_enqueue_script('tasty-admin-js', TASTY_STATIC . '/admin.js');
}

function socialgrid_options_admin() {
    echo 'Meow';
}
