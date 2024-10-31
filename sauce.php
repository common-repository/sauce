<?php
/*
Plugin Name: Sauce for Wordpress
Plugin URI: http://sauceapp.io/
Description: Bring the party to you. Sauce integrates with you Wordpress blog, allowing users to easily share what they read on your site.
Version: 0.0.7
Author: Chris Houghton
Author URI: http://sauceapp.io/
License: GPL
*/

// Set constants
define( 'SAUCE_PUGIN_NAME', 'Sauce');
define( 'SAUCE_PLUGIN_URL', plugin_dir_url( __FILE__ ));
define( 'SAUCE_CURRENT_VERSION', '0.0.7' );
define( 'SAUCE_PLUGIN_PATH', plugin_dir_path(__FILE__ ));


// Include the model 
include_once ('app_model.php');

// Include the controllers
include_once ('app_controller.php');


// Create the controller - this is what runs everything else
$fbOgAction = new Sauce_Controller;

// Activation hooks (can't go in the model construct for some reason - I think they run before the model is created)
register_activation_hook( __FILE__, array('Sauce_Model', 'install'));
register_activation_hook( __FILE__, array('Sauce_Model', 'activate'));
register_deactivation_hook( __FILE__, array('Sauce_Model', 'deactivate'));
register_uninstall_hook( __FILE__, array('Sauce_Model', 'uninstall'));	




?>