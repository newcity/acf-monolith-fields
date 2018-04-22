<?php

// exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


// check if class already exists
if( !class_exists('newcity_acf_field_image_with_caption') ) :


class newcity_acf_field_image_with_caption extends acf_field {
	
	
	/*
	*  __construct
	*
	*  This function will setup the field type data
	*
	*  @type	function
	*  @date	5/03/2014
	*  @since	5.0.0
	*
	*  @param	n/a
	*  @return	n/a
	*/

	var $fields;
	
	var $field_defaults = array(
		'text' => array(
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array(
				'width' => '',
				'class' => '',
				'id' => ''
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'value' => '',
			'_name' => '',
			'_prepare' => 0
		),
		'image' => array(
			'type' => 'image',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => [
				'width' => '',
				'class' => '',
				'id' => ''
			],
			'return_format' => 'id',
			'preview_size' => 'medium',
			'library' => 'all',
			'min_width' => 480,
			'min_height' => 240,
			'min_size' => '',
			'max_width' => '',
			'max_height' => '',
			'max_size' => 10,
			'mime_types' => 'jpg, jpeg, png',
		),
	);

	var $dimension_keys = [
		'min_width' => [
			'label' => 'Min. Width',
			'units' => 'px',
		],
		'min_height' => [
			'label' => 'Min. Height',
			'units' => 'px',
		],
		'min_size' => [
			'label' => 'Min. File Size',
			'units' => ' MB',
		],
		'max_width' => [
			'label' => 'Max. Width',
			'units' => 'px',
		],
		'max_height' => [
			'label' => 'Max. Width',
			'units' => 'px',
		],
		'max_size' => [
			'label' => 'Max. File Size',
			'units' => ' MB',
		],
	];

	function has_dimension_settings( $image_settings ) {
		foreach ($this->dimension_keys as $key => $values ) {
			xdebug_break();
			if ( !empty( $image_settings[$key] ) ) {
				return true;
			}
			return false;
		}
	}

	function set_image_instructions( $image_settings ) {
		if ( !$this->has_dimension_settings( $image_settings ) ) {
			if (  !empty( $image_settings['instructions'] ) ) {
				return $image_settings['instructions'];
			}
			return '';
		}

		$instructions = [];

		foreach ( $this->dimension_keys as $key => $values ) {
			if ( !empty( $image_settings[$key] ) ) {
				$instructions[] = "{$values['label']}: {$image_settings[$key]}{$values['units']}";
			}
		}

		return '<span class="description__columns">' . implode( '<br/>', $instructions ) . '</span>';

	}
	
	function __construct( $settings ) {
		
		/*
		*  name (string) Single word, no spaces. Underscores allowed
		*/
		
		$this->name = 'image_with_caption';


		/*
		*  fields (array) Array of name / label values for the text fields to be created
		*/

		$this->fields = array(
			array(
				'name' => 'imgsrc',
                'label' => 'Choose Image',
                'type' => 'image'
			),
			array(
				'name' => 'headline',
				'label' => 'Caption Headline',
			),
			array(
				'name' => 'caption',
                'label' => 'Caption',
                'type' => 'wysiwyg'
            ),
            array(
                'name' => 'citation',
                'label' => 'Image Credit'
            )
		);
		
		
		/*
		*  label (string) Multiple words, can include spaces, visible when selecting a field type
		*/
		
		$this->label = __('Image with Caption', 'acf-monolith');
		
		
		/*
		*  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
		*/
		
		$this->category = 'Monolith';
		
		
		/*
		*  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
		*/
		
		$this->defaults = array(
			'show_headline' => true,
			'show_citation' => true,
			'sub_fields'	=> array(),
			'layout'		=> 'block',
			'sub_fields'	=> array(),
		);
		
		
		/*
		*  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
		*  var message = acf._e('image_with_caption', 'error');
		*/
		
		$this->l10n = array(
			'error'	=> __('Error in Image with Caption', 'acf-monolith'),
		);
		
		
		/*
		*  settings (array) Store plugin settings (url, path, version) as a reference for later use with assets
		*/
		
		$this->settings = $settings;

		// do not delete!
    	parent::__construct();
    	
	}

	/*
	*  visibility_status()
	*
	*  Check for a field variable called `show_{sub_field name}` and return its value or true
	*
	*  @param	$sub_field (array) current sub_field being tested for visibility
	*  @param	$field (array) the parent $field of the $sub_field being tested
	*  @param	$prefix (string) Option to use a prefix other than `show_` for the visibility variable
	*  @return	boolean
	*/

	function visibility_status( $sub_field, $field, $prefix = 'show_' ) {

		if ( isset( $field[ $prefix . $sub_field['name'] ] ) ) {
			return $field[ $prefix . $sub_field['name'] ];
		}
		
		return true;
	}
	
	
	/*
	*  render_field_settings()
	*
	*  Create extra settings for your field. These are visible when editing a field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/
	
	function render_field_settings( $field ) {
		foreach( $field['sub_fields'] as &$sub_field ) {

			// add value
			if( isset($field['value'][ $sub_field['key'] ]) ) {
				
				// this is a normal value
				$sub_field['value'] = $field['value'][ $sub_field['key'] ];
				
			} elseif( isset($sub_field['default_value']) ) {
				
				// no value, but this sub field has a default value
				$sub_field['value'] = $sub_field['default_value'];
				
			}
			
			
			// update prefix to allow for nested values
			$sub_field['prefix'] = $field['name'];
			
			
			// restore required
			if( $field['required'] ) $sub_field['required'] = 0;
		
		}
		
		/*
		*  acf_render_field_setting
		*
		*  This function will create a setting for your field. Simply pass the $field parameter and an array of field settings.
		*  The array of settings does not require a `value` or `prefix`; These settings are found from the $field array.
		*
		*  More than one setting can be added by copy/paste the above code.
		*  Please note that you must also have a matching $defaults value for the field name (font_size)
		*/
		
		acf_render_field_setting( $field, array(
			'label'			=> __('Show Headline Field','acf-monolith'),
			'instructions'	=> __('Can this caption include a separate headline?','acf-monolith'),
			'type'			=> 'true_false',
			'name'			=> 'show_headline',
			'ui'			=> 1,
        ));
        
		acf_render_field_setting( $field, array(
			'label'			=> __('Show Image Credit Field','acf-monolith'),
			'instructions'	=> __('Can this caption include a credit for the image?','acf-monolith'),
			'type'			=> 'true_false',
			'name'			=> 'show_citation',
			'ui'			=> 1,
		));
		

		// layout
		acf_render_field_setting( $field, array(
			'label'			=> __('Layout','acf'),
			'instructions'	=> __('Specify the style used to render the selected fields', 'acf'),
			'type'			=> 'radio',
			'name'			=> 'layout',
			'layout'		=> 'horizontal',
			'choices'		=> array(
				'block'			=> __('Block','acf'),
				'table'			=> __('Table','acf'),
				'row'			=> __('Row','acf')
			)
		));

		// Sizes (copied from default ACF Image field)
		// min
		acf_render_field_setting( $field, array(
			'label'			=> __('Minimum','acf'),
			'instructions'	=> __('Restrict which images can be uploaded','acf'),
			'type'			=> 'text',
			'name'			=> 'min_width',
			'prepend'		=> __('Width', 'acf'),
			'append'		=> 'px',
		));
		
		acf_render_field_setting( $field, array(
			'label'			=> '',
			'type'			=> 'text',
			'name'			=> 'min_height',
			'prepend'		=> __('Height', 'acf'),
			'append'		=> 'px',
			'_append' 		=> 'min_width'
		));
		
		acf_render_field_setting( $field, array(
			'label'			=> '',
			'type'			=> 'text',
			'name'			=> 'min_size',
			'prepend'		=> __('File size', 'acf'),
			'append'		=> 'MB',
			'_append' 		=> 'min_width'
		));	
		
		
		// max
		acf_render_field_setting( $field, array(
			'label'			=> __('Maximum','acf'),
			'instructions'	=> __('Restrict which images can be uploaded','acf'),
			'type'			=> 'text',
			'name'			=> 'max_width',
			'prepend'		=> __('Width', 'acf'),
			'append'		=> 'px',
		));
		
		acf_render_field_setting( $field, array(
			'label'			=> '',
			'type'			=> 'text',
			'name'			=> 'max_height',
			'prepend'		=> __('Height', 'acf'),
			'append'		=> 'px',
			'_append' 		=> 'max_width'
		));
		
		acf_render_field_setting( $field, array(
			'label'			=> '',
			'type'			=> 'text',
			'name'			=> 'max_size',
			'prepend'		=> __('File size', 'acf'),
			'append'		=> 'MB',
			'_append' 		=> 'max_width'
		));	
		
		
		// allowed type -- commented out, since allowing anything other than the defaults (jpg, jpeg, png)
		// is highly discouraged
		// acf_render_field_setting( $field, array(
		// 	'label'			=> __('Allowed file types','acf'),
		// 	'instructions'	=> __('Comma separated list. Leave blank for all types','acf'),
		// 	'type'			=> 'text',
		// 	'name'			=> 'mime_types',
		// ));


	}
	
	function set_field_values( $field ) {
		$sub_fields = array();

		// $sub_field_defaults = array(
		// 	'type' => 'text',
		// 	'instructions' => '',
		// 	'required' => 0,
		// 	'conditional_logic' => 0,
		// 	'wrapper' => array(
		// 		'width' => '',
		// 		'class' => '',
		// 		'id' => ''
		// 	),
		// 	'default_value' => '',
		// 	'placeholder' => '',
		// 	'prepend' => '',
		// 	'append' => '',
		// 	'maxlength' => '',
		// 	'value' => '',
		// 	'_name' => '',
		// 	'_prepare' => 0
		// );

		$sub_field_settings = $this->fields;


		foreach ( $sub_field_settings as &$sub_field ) {

			$sub_field['visible'] = $this->visibility_status( $sub_field, $field );

			if ( $sub_field['visible'] ) {
				$field_ids = array(
					'key' => $sub_field['name'],
					'id' => '',
					'_name' => $field['key'] . '_' . $sub_field['name'],
				);

				$field_type = array_key_exists( 'type', $sub_field ) ? $sub_field['type'] : 'text';
				$field_type = array_key_exists( $field_type, $this->field_defaults ) ? $field_type : 'text';

				foreach ( $this->dimension_keys as $key => $value ) {
					if ( !empty($field[$key]) ) {
						$sub_field[$key] = $field[$key];
					}
				}

				$merged_field = array_merge( $this->field_defaults[$field_type], $sub_field, $field_ids );
				if ( $field_type == 'image' ) {

					$merged_field['instructions'] = $this->set_image_instructions( $merged_field );
				}
				$sub_fields[] = $merged_field;
			}
		}

		$field['sub_fields'] = $sub_fields;
		return $field;
	}

	function set_sub_fields( $field ) {
		
		return $field;
	}
	
	
	/*
	*  render_field()
	*
	*  Create the HTML interface for your field
	*
	*  @param	$field (array) the $field being rendered
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/
	
	function render_field( $field ) {
		$field = $this->set_sub_fields( $field );
		$field = $this->set_field_values( $field );
		
		/*
		*  Review the data of $field.
		*  This will show what data is available
		*/
		
		// echo '<pre>';
		// 	print_r( $field );
		// echo '</pre>';
		
		do_action('acf/render_field/type=group', $field);
	}
	
		
	/*
	*  input_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
	*  Use this action to add CSS + JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	
	function input_admin_enqueue_scripts() {
		
		// vars
		$url = $this->settings['url'];
		$version = $this->settings['version'];
		
		
		// register & include JS
		// wp_register_script('acf-monolith', "{$url}assets/js/input.js", array('acf-input'), $version);
		// wp_enqueue_script('acf-monolith');
		
		
		// register & include CSS
		wp_register_style('acf-image-with-caption', "{$url}assets/css/image-with-caption.css", array('acf-input'), $version);
		wp_enqueue_style('acf-image-with-caption');
		
	}
	
	
	
	/*
	*  input_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is created.
	*  Use this action to add CSS and JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_head)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*
		
	function input_admin_head() {
	
		
		
	}
	
	*/
	
	
	/*
   	*  input_form_data()
   	*
   	*  This function is called once on the 'input' page between the head and footer
   	*  There are 2 situations where ACF did not load during the 'acf/input_admin_enqueue_scripts' and 
   	*  'acf/input_admin_head' actions because ACF did not know it was going to be used. These situations are
   	*  seen on comments / user edit forms on the front end. This function will always be called, and includes
   	*  $args that related to the current screen such as $args['post_id']
   	*
   	*  @type	function
   	*  @date	6/03/2014
   	*  @since	5.0.0
   	*
   	*  @param	$args (array)
   	*  @return	n/a
   	*/
   	
   	/*
   	
   	function input_form_data( $args ) {
	   	
		
	
   	}
   	
   	*/
	
	
	/*
	*  input_admin_footer()
	*
	*  This action is called in the admin_footer action on the edit screen where your field is created.
	*  Use this action to add CSS and JavaScript to assist your render_field() action.
	*
	*  @type	action (admin_footer)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*
		
	function input_admin_footer() {
	
		
		
	}
	
	*/
	
	
	/*
	*  field_group_admin_enqueue_scripts()
	*
	*  This action is called in the admin_enqueue_scripts action on the edit screen where your field is edited.
	*  Use this action to add CSS + JavaScript to assist your render_field_options() action.
	*
	*  @type	action (admin_enqueue_scripts)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*
	
	function field_group_admin_enqueue_scripts() {
		
	}
	
	*/

	
	/*
	*  field_group_admin_head()
	*
	*  This action is called in the admin_head action on the edit screen where your field is edited.
	*  Use this action to add CSS and JavaScript to assist your render_field_options() action.
	*
	*  @type	action (admin_head)
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	n/a
	*  @return	n/a
	*/

	/*
	
	function field_group_admin_head() {
	
	}
	
	*/


	/*
	*  load_value()
	*
	*  This filter is applied to the $value after it is loaded from the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value found in the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*  @return	$value
	*/
	
	
	function load_value( $value, $post_id, $field ) {
		return $value;
		
	}
	
	
	
	/*
	*  update_value()
	*
	*  This filter is applied to the $value before it is saved in the db
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value found in the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*  @return	$value
	*/
	
	
	function update_value( $value, $post_id, $field ) {

		$sub_field_settings = $this->fields;

		foreach ($sub_field_settings as $sub_field) {
			$sub_field['visible'] = $this->visibility_status( $sub_field, $field );

			if ( ! $sub_field['visible'] && array_key_exists( $sub_field['name'], $value ) ) {
				$value[ $sub_field['name'] ] = false;
			}
		}

		return $value;
		
	}
	
	
	
	/*
	*  format_value()
	*
	*  This filter is appied to the $value after it is loaded from the db and before it is returned to the template
	*
	*  @type	filter
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$value (mixed) the value which was loaded from the database
	*  @param	$post_id (mixed) the $post_id from which the value was loaded
	*  @param	$field (array) the field array holding all the field options
	*
	*  @return	$value (mixed) the modified value
	*/
		
	/*
	
	function format_value( $value, $post_id, $field ) {
		
		// bail early if no value
		if( empty($value) ) {
		
			return $value;
			
		}
		
		
		// apply setting
		if( $field['font_size'] > 12 ) { 
			
			// format the value
			// $value = 'something';
		
		}
		
		
		// return
		return $value;
	}
	
	*/
	
	
	/*
	*  validate_value()
	*
	*  This filter is used to perform validation on the value prior to saving.
	*  All values are validated regardless of the field's required setting. This allows you to validate and return
	*  messages to the user if the value is not correct
	*
	*  @type	filter
	*  @date	11/02/2014
	*  @since	5.0.0
	*
	*  @param	$valid (boolean) validation status based on the value and the field's required setting
	*  @param	$value (mixed) the $_POST value
	*  @param	$field (array) the field array holding all the field options
	*  @param	$input (string) the corresponding input name for $_POST value
	*  @return	$valid
	*/
	
	/*
	
	function validate_value( $valid, $value, $field, $input ){
		
		// Basic usage
		if( $value < $field['custom_minimum_setting'] )
		{
			$valid = false;
		}
		
		
		// Advanced usage
		if( $value < $field['custom_minimum_setting'] )
		{
			$valid = __('The value is too little!','acf-monolith'),
		}
		
		
		// return
		return $valid;
		
	}
	
	*/
	
	
	/*
	*  delete_value()
	*
	*  This action is fired after a value has been deleted from the db.
	*  Please note that saving a blank value is treated as an update, not a delete
	*
	*  @type	action
	*  @date	6/03/2014
	*  @since	5.0.0
	*
	*  @param	$post_id (mixed) the $post_id from which the value was deleted
	*  @param	$key (string) the $meta_key which the value was deleted
	*  @return	n/a
	*/
	
	/*
	
	function delete_value( $post_id, $key ) {
		
		
		
	}
	
	*/
	
	
	/*
	*  load_field()
	*
	*  This filter is applied to the $field after it is loaded from the database
	*
	*  @type	filter
	*  @date	23/01/2013
	*  @since	3.6.0	
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	$field
	*/
	
	
	function load_field( $field ) {

		foreach( $field['sub_fields'] as $sub_field ) {
			if ( array_key_exists( $sub_field['key'], $field['value'] ) ) {
				$field['value'][ $sub_field['key'] ] = $sub_field['value'];
			}
		}
				
		// return
		return $field;
		
	}	
	

	
	
	/*
	*  update_field()
	*
	*  This filter is applied to the $field before it is saved to the database
	*
	*  @type	filter
	*  @date	23/01/2013
	*  @since	3.6.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	$field
	*/
	
	
	function update_field( $field ) {

		// foreach( $field['sub_fields'] as $sub_field ) {
		// 	if ( array_key_exists( $sub_field['key'], $field['value'] ) ) {
		// 		$field['value'][ $sub_field['key'] ] = $sub_field['value'];
		// 	}
		// }
		
		return $field;
	}	
	
	
	/*
	*  delete_field()
	*
	*  This action is fired after a field is deleted from the database
	*
	*  @type	action
	*  @date	11/02/2014
	*  @since	5.0.0
	*
	*  @param	$field (array) the field array holding all the field options
	*  @return	n/a
	*/
	
	/*
	
	function delete_field( $field ) {
		
		
		
	}	
	
	*/
	
	
}


// initialize
new newcity_acf_field_image_with_caption( $this->settings );


// class_exists check
endif;

?>