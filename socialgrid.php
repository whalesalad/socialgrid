<?php
/*
Plugin Name: SocialGrid
Plugin URI: http://whalesalad.com/socialgrid
Description: SocialGrid makes it easy to include attractive links to your various social media profiles on the web.
Version: 2.4
Author: Michael Whalen
Author URI: http://whalesalad.com
*/

// Define global SocialGrid constants
define('SG_VERSION', 2.4);
define('SG_NAME', 'SocialGrid');
define('SG_SLUG', 'socialgrid');

// Define path to SocialGrid libs and direct url to SocialGrid static assets
define('SG_CLASS_LIB', dirname(__FILE__).'/classes/');
define('SG_STATIC', WP_PLUGIN_URL.'/'.basename(dirname(__FILE__)).'/static');

// IF WE'RE DEALIN WITH PHP5
if (is_php5()) {
    // Load various classes...
    foreach (glob(SG_CLASS_LIB.'*.class.php') as $filename) {
        require_once $filename;
    }

    // Jump on various WP Admin hooks
    add_action('admin_menu', SG_SLUG.'_add_options_page');

    if (isset($_GET['page']) and $_GET['page'] == 'socialgrid-options') {
        add_action('admin_post_'.SG_SLUG.'_save', SG_SLUG.'_save_options');
        add_action('init', SG_SLUG.'_settings_init');
        add_action('admin_head', SG_SLUG.'_settings_head');
    }
    add_action('init', SG_SLUG.'_frontend_init');

    // Create the various AJAX actions, see functions at end of file.
    add_action('wp_ajax_add_socialgrid_service', 'socialgrid_add_service_rpc');
    add_action('wp_ajax_update_socialgrid_service', 'socialgrid_update_service_rpc');
    add_action('wp_ajax_remove_socialgrid_service', 'socialgrid_remove_service_rpc');
    add_action('wp_ajax_rearrange_socialgrid_services', 'socialgrid_rearrange_rpc');
    add_action('wp_ajax_toggle_socialgrid_icon_size', 'socialgrid_toggle_icon_size_rpc');
    add_action('wp_ajax_master_socialgrid_reset', 'socialgrid_master_reset_rpc');
    add_action('wp_ajax_save_socialgrid_settings', 'socialgrid_savesettings_rpc');
    
    $sg_settings = new SocialGridSettings();
    $sg_admin = new SGAdmin($sg_settings);
    
} else {
    add_action('admin_menu', SG_SLUG.'_add_options_page');
    add_action('init', SG_SLUG.'_settings_init');
}

function socialgrid_add_options_page() {
    add_theme_page(__(SG_NAME.' Options', SG_SLUG), __(SG_NAME.' Options', SG_SLUG), 'edit_themes', SG_SLUG.'-options', SG_SLUG.'_options_admin');
}

function socialgrid_settings_init() {
    global $sg_settings;
    if (is_admin() and isset($_GET['page']) and $_GET['page'] == 'socialgrid-options') {
        wp_enqueue_style(SG_SLUG.'-admin-stylesheet',  SG_STATIC.'/css/'.SG_SLUG.'-admin.css');
        wp_enqueue_script(SG_SLUG.'-admin-js', SG_STATIC.'/js/'.SG_SLUG.'-admin.js');
        wp_enqueue_script(SG_SLUG.'-admin-jquery-ui', SG_STATIC.'/js/jquery-ui-1.7.2.custom.min.js');
    }
}

function socialgrid_frontend_init() {
    global $sg_settings;
    if (!$sg_settings->disable_tooltips) {
        wp_enqueue_script('jquery');
        wp_enqueue_script(SG_SLUG.'-js', SG_STATIC.'/js/'.SG_SLUG.'.js');
    }
    wp_enqueue_style(SG_SLUG.'-stylesheet',  SG_STATIC.'/css/'.SG_SLUG.'.css');
}

function socialgrid_settings_head() { 
    global $sg_admin; ?>
    <script type="text/javascript">
        SG_DEFAULTS = <?= json_encode($sg_admin->default_services); ?>;
        SG_SERVICES = <?= json_encode($sg_admin->inline_service_list()); ?>;
        SG_MINI_ICONS = <?= json_encode($sg_admin->settings->enable_mini_icons); ?>;
        SG_DISABLE_TOOLTIPS = <?= json_encode($sg_admin->settings->disable_tooltips); ?>;
        jQuery(document).ready(function() {
            SocialGridAdmin.init();
        });
    </script>
<?php }

// Admin Page
function socialgrid_options_admin() { 
    global $sg_admin, $sg_settings; ?>
    
    <?php if (is_php5()): // IF PHP5, DO AS NORMAL ?>
    <?php if (isset($_GET['reloaded'])): ?>
    <div id="updated" class="updated fade">
        <p><?php echo __('SocialGrid has been reset successfully!', 'socialgrid'); ?></p>
    </div>
    <?php endif; ?>
    
    <div id="socialgrid-admin">
        <div id="socialgrid-header">
            <h3>SOCIALGRID</h3>
            <a id="socialgrid-settings-button" class="socialgrid-button"><span>Settings</span></a>
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

    <h2><?php _e(SG_NAME.' Options', SG_SLUG); ?></h2>
    
    <p>SocialGrid is a widget that you can place in your sidebar that features any of the social network or profile sites you choose.</p>
    <p><strong>Use the interface below to add and rearrange the services however you'd like. <br/>Socialgrid saves your settings automatically as you interact with it.</strong></p>
    
    <p>You configure the <strong>settings</strong> for SocialGrid here on this page. To add SocialGrid to your sidebar<br/>
        visit the <a href="<?php echo admin_url('widgets.php') ?>">Widget</a> settings area. There you can add SocialGrid to your sidebar and position it wherever you'd like.</p>
    
    <br/>
        
    <p>If you like what you see here, <a href="http://whalesalad.com/tasty" target="_blank">check out Tasty</a>, a WordPress theme also created by Michael Whalen.</p>
    <p><small>The icons used in SocialGrid were created by <a target="_blank" href="http://www.komodomedia.com/blog/2009/06/social-network-icon-pack/">Rogie King of Komodo Media</a>.</small></p>
    
    <?php else: // IF PHP4, OR SOMETHING LESS THAN 5. PHP3? LULZ. 
    
    $pretty_php_version = explode('.', phpversion());
    $pretty_php_version = $pretty_php_version[0].'.'.$pretty_php_version[1];
    
    ?>

    <h2>Sorry, SocialGrid will not work on this server.</h2>

    <div id="updated" class="updated fade">
    <p><strong>Currently your web server is running PHP <?php echo $pretty_php_version.' ('.phpversion().')'; ?> and it needs to be running at least version 5.2.</strong></p>
    </div>

    <p>SocialGrid was built for PHP5, which is a newer but stable and mature version of PHP. <br/> PHP5 has been around for a couple of years now, so there is no reason why your web server should be running PHP4.</p>
    
    <p><strong>There's good news, however,</strong> most webhosts let you choose the version of PHP that you would like to run on your site. <br/>Try looking around your hosting control panel or search google for something like "how to enable php5 on XYZ host".</p>
    
    <p><em>Sorry for the inconvenience,</em><br/>&nbsp;&nbsp;&nbsp;&nbsp;<cite>&mdash; Michael</cite></p>
    
    <p><small>P.S. If you have any questions, comments, or concerns please feel free to <a href="http://whalesalad.com/contact" target="_blank">contact me via my contact form</a>.</small></p>
    
    <?php endif; ?>
<?php } 


// Add service AJAX call
function socialgrid_add_service_rpc() {
    global $sg_settings, $sg_admin;
    
    // Get the posted vars
    $service = $_POST['service'];
    $username = $_POST['username'];
    
    // Create an index
    $index = count($sg_settings->services);
    
    // Create the new setting
    if ($service == 'rss') {
        $new_service = new SocialGridRSSService($sg_admin, $index);
    } else if ($service == 'technorati') {
        $blog_url = parse_url(get_bloginfo('url'));
        $new_service = new SocialGridService($sg_admin, $service, $blog_url['host'], $index);
    } else {
        $new_service = new SocialGridService($sg_admin, $service, $username, $index);
    }
    $sg_settings->services[$service] = $new_service;
    
    // Save the settings, cross fingers
    $sg_settings->save();
}


// Update service AJAX call
function socialgrid_update_service_rpc() {
    global $sg_settings, $sg_admin;

    // Get the posted vars
    $service = $_POST['service'];
    $username = $_POST['username'];
    
    // Create the new setting
    $sg_settings->services[$service]->set_username($username);
    
    // Save the settings, cross fingers
    $sg_settings->save();
}


// Rearrange service AJAX call
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


// Remove service AJAX call
function socialgrid_remove_service_rpc() {
    global $sg_settings;
    
    $service = $_POST['service'];
    
    unset($sg_settings->services[$service]);

    $i = 0;
    foreach ($sg_settings->services as $service => $value) {
        $sg_settings->services[$service]->index = $i;
        $i++;
    }
    
    $sg_settings->save();
}

function socialgrid_savesettings_rpc() {
    global $sg_settings;
    
    $icon_size = $_POST['icon_size'];
    if ($icon_size == 'mini') {
        $sg_settings->enable_mini_icons = true;
    } else {
        $sg_settings->enable_mini_icons = false;
    }
    
    $disable_tooltips = $_POST['tooltips'];
    if ($disable_tooltips == 'disabled') {
        $sg_settings->disable_tooltips = true;
    } else {
        $sg_settings->disable_tooltips = false;
    }
    
    $sg_settings->save();
}

function socialgrid_master_reset_rpc() {
    global $sg_settings;
    
    $sg_settings->reset();
}

// Create the widget
class SocialGridWidget extends WP_Widget {
    function SocialGridWidget() {
        parent::WP_Widget(false, $name = 'SocialGrid');
    }

    function widget() {
        global $sg_settings;
        $sg = new SG($sg_settings);
        $sg->display();
    }

    function form() {
        echo('<p><strong>You do not edit SocialGrid\'s settings here.</strong> <a href="'.admin_url('themes.php?page=socialgrid-options').'">Click here</a> to edit your SocialGrid widget settings.');
    }
}

if (is_php5()) {
    // Hook the widget into WP
    add_action('widgets_init', create_function('', 'return register_widget("SocialGridWidget");'));
}

////////////////////
// CHECK FOR PHP5 //
////////////////////
function is_php5() {
    $phpversion = explode('.', PHP_VERSION);
    return ($phpversion[0] < 5) ? false : true;
    // return false; // debug
}

?>
