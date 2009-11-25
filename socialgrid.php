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

// Define global SocialGrid constants
define('SG_VERSION', 0.1);
define('SG_NAME', 'SocialGrid');
define('SG_SLUG', 'socialgrid');

// Define path to SocialGrid libs and direct url to SocialGrid static assets
define('SG_LIB', dirname(__FILE__).'/lib');
define('SG_STATIC', WP_PLUGIN_URL.'/'.basename(dirname(__FILE__)).'/static');

// Load various classes...
require_once(SG_LIB.'/class.service.php');
require_once(SG_LIB.'/class.settings.php');
require_once(SG_LIB.'/class.socialgrid.php');

// Jump on various WP Admin hooks
add_action('admin_menu', SG_SLUG.'_add_options_page');
add_action('admin_post_'.SG_SLUG.'_save', SG_SLUG.'_save_options');
add_action('init', SG_SLUG.'_settings_init');
add_action('admin_head', SG_SLUG.'_settings_head');

// if ($_GET['activated'])
//     tasty_activate_theme();

function socialgrid_add_options_page() {
    add_theme_page(__(SG_NAME.' Options', SG_SLUG), __(SG_NAME.' Options', SG_SLUG), 'edit_themes', SG_SLUG.'-options', SG_SLUG.'_options_admin');
}

function socialgrid_settings_init() {
    wp_enqueue_style(SG_SLUG.'-admin-stylesheet',  SG_STATIC.'/css/'.SG_SLUG.'-admin.css');
    wp_enqueue_script(SG_SLUG.'-admin-js', SG_STATIC.'/js/'.SG_SLUG.'-admin.js');
    wp_enqueue_script(SG_SLUG.'-admin-jquery-ui', SG_STATIC.'/js/jquery-ui-1.7.2.custom.min.js');
}

$sg_settings = new SocialGridSettings();
$sg_admin = new SGAdmin();

function socialgrid_settings_head() { 
    global $sg_admin; ?>
    <script type="text/javascript">
        SG_DEFAULTS = <?php echo json_encode($sg_admin->default_services); ?>;
        SG_SERVICES = <?php echo json_encode($sg_admin->inline_service_list()); ?>;
    </script>
<?php }

// Admin Page
function socialgrid_options_admin() { 
    // SocialGrid class. Instantiated in sidebar, renders grid of Social Media buttons.
    global $sg_admin;
    
    ?>
    <h2><?php _e(SG_NAME.' Options', SG_SLUG); ?></h2>
    <p>SocialGrid is a widget that you can place in your sidebar that features any of the social network or profile sites you choose. Simply use the 
    
    <div id="socialgrid-admin">
        <div id="socialgrid-header">
            <h3>SOCIALGRID</h3>
            <a href="#" class="socialgrid-button socialgrid-settings-button"><span>Settings</span></a>
        </div>
        
        <div id="socialgrid-content">
            <ul class="socialgrid-items" id="socialgrid-home-screen">
                <?php $sg_admin->render_buttons() ?>
                <?php if ($sg_admin->show_add_button()): ?>
                <li class="socialgrid-item-add">+</li>
                <?php endif ?>
            </ul>
        </div>
    </div>
        
<?php } 

// Will end up being an RPC call to add a service
function add_socialgrid_service ($request = 'meow') {
    global $sg_settings;
    
    $services['vimeo'] = new SocialGridService('vimeo', 'whalesalad', 1);
    $services['twitter'] = new SocialGridService('twitter', 'whalesalad', 0);
    $services['myspace'] = new SocialGridService('myspace', 'whalesalad', 2);
    
    $sg_settings->services = $services;
    $sg_settings->save();
    // $sg_settings->reset();
}

// add_socialgrid_service();

?>