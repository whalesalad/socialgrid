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

add_action('wp_ajax_add_socialgrid_service', 'socialgrid_add_service_rpc');
add_action('wp_ajax_remove_socialgrid_service', 'socialgrid_remove_service_rpc');
add_action('wp_ajax_rearrange_socialgrid_services', 'socialgrid_rearrange_rpc');

// if ($_GET['activated'])
//     tasty_activate_theme();

function socialgrid_add_options_page() {
    add_theme_page(__(SG_NAME.' Options', SG_SLUG), __(SG_NAME.' Options', SG_SLUG), 'edit_themes', SG_SLUG.'-options', SG_SLUG.'_options_admin');
}

function socialgrid_settings_init() {
    if (is_admin()) {
        wp_enqueue_style(SG_SLUG.'-admin-stylesheet',  SG_STATIC.'/css/'.SG_SLUG.'-admin.css');
        wp_enqueue_script(SG_SLUG.'-admin-js', SG_STATIC.'/js/'.SG_SLUG.'-admin.js');
        wp_enqueue_script(SG_SLUG.'-admin-jquery-ui', SG_STATIC.'/js/jquery-ui-1.7.2.custom.min.js');
    } else {
        wp_enqueue_style(SG_SLUG.'-stylesheet',  SG_STATIC.'/css/'.SG_SLUG.'.css');
        wp_enqueue_script(SG_SLUG.'-js', SG_STATIC.'/js/'.SG_SLUG.'.js');
    }
}

$sg_settings = new SocialGridSettings();
$sg_admin = new SGAdmin($sg_settings);

function socialgrid_settings_head() { 
    global $sg_admin; ?>
    <script type="text/javascript">
        SG_DEFAULTS = <?php echo json_encode($sg_admin->default_services); ?>;
        SG_SERVICES = <?php echo json_encode($sg_admin->inline_service_list()); ?>;
        jQuery(document).ready(function() {
            SocialGridAdmin.init();
        })
    </script>
<?php }

// Admin Page
function socialgrid_options_admin() { 
    // SocialGrid class. Instantiated in sidebar, renders grid of Social Media buttons.
    global $sg_admin, $sg_settings;
    
    ?>
    <h2><?php _e(SG_NAME.' Options', SG_SLUG); ?></h2>
    <p>SocialGrid is a widget that you can place in your sidebar that features any of the social network or profile sites you choose.</p>
    <p><strong>Use the interface below to add and rearrange the services however you'd like. <br/>Socialgrid saves your settings automatically as you interact with it.</strong></p>
    
    <div id="socialgrid-admin">
        <div id="socialgrid-header">
            <h3>SOCIALGRID</h3>
            <a href="#" class="socialgrid-button socialgrid-settings-button"><span>Settings</span></a>
        </div>
        
        <div id="socialgrid-content">
            <div class="socialgrid-pane" id="socialgrid-home-screen">
                <ul class="socialgrid-items">
                    <?php $sg_admin->render_buttons() ?>
                    <?php if ($sg_admin->show_add_button()): ?>
                    <li class="socialgrid-item-add">+</li>
                    <?php endif ?>
                </ul>
                <div id="socialgrid-drop-delete"></div>
            </div>
        </div>
        
    </div>
        
<?php } 

// Will end up being an RPC call to add a service
function socialgrid_add_service_rpc() {
    global $sg_settings, $sg_admin;

    // Get the posted vars
    $service = $_POST['service'];
    $username = $_POST['username'];
    
    // Create an index
    $index = count($sg_settings->services);
    
    // Create the new setting
    $sg_settings->services[$service] = new SocialGridService($sg_admin, $service, $username, $index);
    
    // Save the settings, cross fingers
    $sg_settings->save();
}

function socialgrid_rearrange_services_rpc() {
    global $sg_settings;
    
    $order = $_POST['service_order'];
    
    // Update each of the items with their new order
    
}

function socialgrid_rearrange_rpc() {
    global $sg_settings;
    
    $services = explode("|", $_POST['services']);
    
    $i = 0;
    foreach ($services as $service) {
        $sg_settings->services[$service]->index = $i;
        $i++;
    }
    
    $sg_settings->save();
}

function socialgrid_remove_service_rpc() {
    global $sg_settings;
    
    $service = $_POST['service'];
    
    unset($sg_settings->services[$service]);
    
    $sg_settings->save();
}

function reset_services() {
    global $sg_settings;
    
    $services['vimeo'] = new SocialGridService('vimeo', 'whalesalad', 1);
    $services['twitter'] = new SocialGridService('twitter', 'whalesalad', 0);
    $services['myspace'] = new SocialGridService('myspace', 'whalesalad', 2);
    
    $sg_settings->services = $services;
    $sg_settings->save();
    // $sg_settings->reset();
}

class SocialGridWidget extends WP_Widget {
    function SocialGridWidget() {
        parent::WP_Widget(false, $name = 'SocialGrid');
    }

    function widget() {
        global $sg_settings;
        $sg = new SG($sg_settings);
        $sg->display();
    }

    /** @see WP_Widget::form */
    function form() {
        echo('<p><strong>You do not edit SocialGrid\'s settings here.</strong> <a href="'.admin_url('themes.php?page=socialgrid-options').'">Click here</a> to edit your SocialGrid widget settings.');
    }
}

add_action('widgets_init', create_function('', 'return register_widget("SocialGridWidget");'));

?>