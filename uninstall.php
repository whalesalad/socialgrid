<?php
/**
 * @package WordPress
 * @subpackage SocialGrid
 */

if(!defined('ABSPATH') && !defined('WP_UNINSTALL_PLUGIN'))
    exit();

// Removes the socialgrid_settings option from the wp_options table
delete_option('socialgrid_settings');

?>