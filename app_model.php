<?php
/*
*	The model file for the Facebook Open Graph Actions Plugin
*
*/


class Sauce_Model {

	private $wp_post;

	function __construct() {

		// add a standard image size for the thumbnails
		add_image_size( "sauce-post-thumbnail-medium", 300, 170, true);

	}

	// Creates a table to store locally whether they are publishing read actions or not (required by facebook). Runs on activation.
	function install() {
	
	}
	

	// Activating the plugin
	function activate() {
		// send_client_data('activate');	
	}

	// Deactivating the plugin
	function deactivate() {
// 		send_client_data('deactivate');
	}

	// Uninstalling the plugin
	function uninstall() {
		
	}

	// Set auto sharing
	function set_auto_sharing($data) {
		// Set auto sharing for the user
	}

	
	// Sees if we publish a certain post type
	function is_publishing_post_type($this_post_type) {
		if ($this_post_type == 'post') {
			$publishing = get_option('sauce_custom_'.$this_post_type, true);
		} else {
			$publishing = get_option('sauce_custom_'.$this_post_type, false);
		}		
		if ($publishing == 'on') {
			$publishing = true;
		} elseif ($publishing == 'off') {
			$publishing = false;
		}
		return $publishing;
	}

	// Get a bunch of standard data on the post for ready access elsewhere.
	// All variables should be assigned as public model variables.
	function get_post($post_id) {

		// Apply post to class variable
		$this->wp_post = get_post($post_id);

		// Get the post with the details we want, and return
		$post = (object) array(
			"id" => $post_id,
			"title" => $this->get_title(),
			"description" => $this->get_description(),
			"url" => $this->get_permalink(),
			"image" => $this->get_image()
		);

		return $post;

	}

	function get_title() {
		return apply_filters('the_title', $this->wp_post->post_title);
	}

	function get_description() {
		// if we have an excerpt that's been manually declared, use that.
    if($this->wp_post->post_excerpt){
      $desc = trim(esc_html(strip_tags(do_shortcode( apply_filters('the_excerpt', $this->wp_post->post_excerpt) ))));
    } 

    // If not, strip out a snippet of content from the_content()
    else {
      $text = strip_shortcodes( $this->wp_post->post_content );
      $text = apply_filters('the_content', $text);
      $text = str_replace(']]>', ']]&gt;', $text);
      $text = addslashes( strip_tags($text) );
      $excerpt_length = apply_filters('excerpt_length', 55);
     
      $words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
      if ( count($words) > $excerpt_length ) {
        array_pop($words);
        $text = implode(' ', $words);
        $text = $text . "...";
      } else {
        $text = implode(' ', $words);
      }
    
      $desc =  $text;
    } 
    return $desc;
	}

	function get_permalink() {
		return get_permalink($this->wp_post->ID);
	}

	// we specifically want images with a width of 300 pixels, as this is the size needed
	// for our activity feed widgets.
	function get_image() {
    $images = array();
    // get an image from the thumbnail and add to the $images array
    if( has_post_thumbnail( $this->wp_post->ID )){
      $thumb_id = get_post_thumbnail_id( $this->wp_post->ID );
      $image = wp_get_attachment_image_src( $thumb_id, "sauce-post-thumbnail-medium" );
      $images[] = (object) array(
        "url" => $image[0],
        "width" => $image[1],
        "height" => $image[2]
      );
    }
    return $images;
	}

}


?>