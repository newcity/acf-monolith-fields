<?php

/*
Plugin Name: Advanced Custom Fields: Monolith Field Types
Plugin URI: https://github.com/newcity
Description: Reusable ACF field groups corresponding to common Monlith patterns
Version: 1.0.0
Author: Jesse Janowiak, NewCity
Author URI: https://insidenewcity.com
License: NONE
*/

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('newcity_acf_monolith') ) :

class newcity_acf_monolith {
	
	// vars
	var $settings;
	
	
	/*
	*  __construct
	*
	*  This function will setup the class functionality
	*
	*  @type	function
	*  @date	17/02/2016
	*  @since	1.0.0
	*
	*  @param	void
	*  @return	void
	*/
	
	function __construct() {
		
		// settings
		// - these will be passed into the field class.
		$this->settings = array(
			'version'	=> '1.0.0',
			'url'		=> plugin_dir_url( __FILE__ ),
			'path'		=> plugin_dir_path( __FILE__ )
		);
		
		
		// include field
		add_action('acf/include_field_types', 	array($this, 'include_field')); // v5
		// add_action('acf/register_fields', 		array($this, 'include_field')); // v4
	}
	
	
	/*
	*  include_field
	*
	*  This function will include the field type class
	*
	*  @type	function
	*  @date	17/02/2016
	*  @since	1.0.0
	*
	*  @param	$version (int) major ACF version. Defaults to 5
	*  @return	void
	*/
	
	function include_field( $version = 5 ) {
		
		// load textdomain
		load_plugin_textdomain( 'acf-monolith', false, plugin_basename( dirname( __FILE__ ) ) . '/lang' ); 
		
		
		// include
		include_once('fields/class-newcity-acf-field-headline_group-v' . $version . '.php');
		include_once('fields/class-newcity-acf-field-image-with-caption-v' . $version . '.php');
	}
	
}


// initialize
new newcity_acf_monolith();


// class_exists check
endif;
	
?>