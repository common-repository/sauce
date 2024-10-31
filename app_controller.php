<?php
/*
* The controller file for the Facebook Open Graph Actions Plugin
*
*/



class Sauce_Controller {

  public $model;

  
  // Kick things off with a bang
  function __construct() {
    
    // Add the model in as a variable within the controller
    $this->model = new Sauce_Model;

    // Assign variables
    $this->client_id = (string) get_option("sauce_client_id");
    $this->app_id = (string) get_option("sauce_app_id");
    
    // Adds the doctype to the html
    add_filter('language_attributes', array($this, 'add_doctype'));

    // Enqueue scripts and css
    add_action( 'wp_head', array($this, 'add_snippet'));
    
    // Create admin menu
    add_action( 'admin_menu', array($this, 'admin_create_menu'));
    
    // Register admin settings
    add_action( 'admin_init', array($this, 'admin_register_settings') );

    // Admin stylesheetz
    add_action('admin_print_styles', array($this, 'add_admin_stylesheets'));
    

  }

    
  /** Header stuff **/

  // Adds the doctype to the html
  function add_doctype( $output ) {
    return $output . ' xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml"';
  }

  function get_client_id() {
    $id = get_option("sauce_client_id");
    if ($id) {
      return (string) $id;
    } else {
      return "";
    }
  }

  // Setup auto read
  function is_readable() {

    // Get post id
    $post_id = get_the_ID();
    if ($post_id == 0) {
      return false;
    }

    // Do checks
    if ((!$post_id) 
      || get_post_status($post_id) != 'publish'
      || !is_singular()
    ) return false;

    // See which post types we're publishing
    $custom_posts = get_post_types(array(
      'public' => true
    )); 
    $types_publishing = array();
    foreach ($custom_posts as $type) {
      if ($type == 'post') {
        if (get_option('sauce_custom_'.$type, 'on') == 'on') {
          $types_publishing[] = $type;
        } 
      } else {
        if (get_option('sauce_custom_'.$type, 'off' ) == 'on') {
          $types_publishing[] = $type;
        }
      } 
    }

    // Add code if we're publishing to this type
    if (in_array(get_post_type($post_id), $types_publishing)) { 
      return true; 
    } else {
      return false;
    }

  }

  function is_sdk_disabled() {
    return $this->convert_wp_option_to_boolean(get_option('sauce_sdk_disable'));
  }

  function is_ui_disabled() {
    return $this->convert_wp_option_to_boolean(get_option('sauce_ui_disable'));
  }

  // Send back server details to the client side
  function add_snippet() { 

    // Get the client details
    $options = (object) array(
      "id" => $this->client_id,
      "appId" => $this->app_id,
      "autoPublish" => $this->is_readable()
    );

    // Get details of the object (article) being viewed
    if ($this->is_readable()) {
      $details = $this->model->get_post(get_the_ID());
    }

    // Include the view if we've got the stuff we need
    if ($options->id && $options->appId) {
      include(SAUCE_PLUGIN_PATH.'/views/frontend/snippet.php');
    }

  }

  // Convert option to js bool
  function convert_wp_option_to_boolean($option) {
    if ($option == 'on') {
      return true;
    } else {
      return false;
    }
  }

  
  /** Admin stuff **/
  
  // Create settings page
  function admin_create_menu() {
    add_menu_page("", "Sauce", 'administrator', 'sauce', array($this, 'admin_settings'), SAUCE_PLUGIN_URL.'images/favicon.png'); 
  }

  function get_errors() {
    $errors = array();

    // 1. Check the PHP version
    if (phpversion() < 5.2) {
      $errors[] = 'Your PHP version is '.phpversion().'. This plugin requires at least version 5.2.0 to run. Please update your PHP version.';
    }

    // 2. Get a sample post to check the permalink structure
    $posts_array = get_posts(array( 
      "posts_per_page" => 1
    )); 
    if (isset($posts_array[0])) {
      $post = $posts_array[0];  
      $permalink = get_permalink($post->ID);
      if ($post->guid == $permalink) {
        $errors[] = "Your permalink structure is not supported by Facebook. Switch to a 'pretty' URLs by changing your <a href='". get_bloginfo('url') . "/wp-admin/options-permalink.php'>permalink settings</a>.";
      }
    }


    return $errors;
  }

  // The admin settings page - all about editing options
  function admin_settings() {
    $custom_posts = get_post_types(array(
      'public' => true
    )); 
    global $current_user;
    get_currentuserinfo();
    $errors = $this->get_errors();
    include(SAUCE_PLUGIN_PATH.'/views/admin/settings.php');

    // track the pageview / POST etc
    $this->track();
  }

  function track() {

    if (isset($_GET["settings-updated"])) {

      // identify the user
      $client_id = $this->get_client_id();

      // get the user name
      $user_id = get_current_user_id();
      $user = get_userdata($user_id);

      $tracking = array(
        "client_id" => $client_id,
        "name" => $user->data->user_nicename,
        "url" => get_bloginfo("url")
      );

      // note we're running the tracking in javascript, not PHP, because 
      // the PHP library results in a degrated user experience.
      include(SAUCE_PLUGIN_PATH.'/views/admin/track.php');

    } 

   
  }
  
  // Register admin settings
  function admin_register_settings() {

    // Sauce client id
    register_setting( 'sauce-settings-group', 'sauce_client_id' );

    // Fb app id
    register_setting( 'sauce-settings-group', 'sauce_app_id' );

    // Register custom post types options
    $custom_posts = get_post_types(array(
      'public' => true
    )); 
    foreach ($custom_posts as $type) {
      register_setting( 'sauce-settings-group', 'sauce_custom_'.$type );
    }

  }
  
  // Add custom stylesheet for the plugin
  function add_admin_stylesheets() {
    wp_register_style( 'sauce-admin-style', plugins_url('css/admin.css', __FILE__) );
    wp_enqueue_style( 'sauce-admin-style' );
  } 
  
  

  
}

?>