<?php
/*
	Plugin Name: Smashing Fields Plugin: Approach 3
	Description: Setting up custom fields for our plugin using ACF
	Author: Matthew Ray
	Version: 1.0.0
*/
class Smashing_Fields_Plugin {

    public function __construct() {
        // Hook into the admin menu
        add_action( 'admin_menu', array( $this, 'create_plugin_settings_page' ) );
        add_action( 'admin_init', array( $this, 'add_acf_variables' ) );

        add_filter( 'acf/settings/path', array( $this, 'update_acf_settings_path' ) );
        add_filter( 'acf/settings/dir', array( $this, 'update_acf_settings_dir' ) );

        include_once( plugin_dir_path( __FILE__ ) . 'vendor/advanced-custom-fields/acf.php' );

        $this->setup_options();
    }

    public function update_acf_settings_path( $path ) {
        $path = plugin_dir_path( __FILE__ ) . 'vendor/advanced-custom-fields/';
        return $path;
    }

    public function update_acf_settings_dir( $dir ) {
        $dir = plugin_dir_url( __FILE__ ) . 'vendor/advanced-custom-fields/';
        return $dir;
    }

    public function create_plugin_settings_page() {
    	// Add the menu item and page
    	$page_title = 'My Awesome Settings Page';
    	$menu_title = 'Awesome Plugin';
    	$capability = 'manage_options';
    	$slug = 'smashing_fields';
    	$callback = array( $this, 'plugin_settings_page_content' );
    	$icon = 'dashicons-admin-plugins';
    	$position = 100;

    	add_menu_page( $page_title, $menu_title, $capability, $slug, $callback, $icon, $position );
    }

    public function plugin_settings_page_content() {
        do_action('acf/input/admin_head');
        do_action('acf/input/admin_enqueue_scripts');

        $options = array(
        	'id' => 'acf-form',
        	'post_id' => 'options',
        	'new_post' => false,
        	'field_groups' => array( 'acf_awesome-options' ),
        	'return' => admin_url('admin.php?page=smashing_fields'),
        	'submit_value' => 'Update',
        );
        acf_form( $options );
    }

    public function add_acf_variables() {
        acf_form_head();
    }

    public function setup_options() {

    	if( function_exists( 'register_field_group' ) ) {
    		register_field_group(array (
    			'id' => 'acf_awesome-options',
    			'title' => 'Awesome Options',
    			'fields' => array (
    				array (
    					'key' => 'field_562dc35316a0f',
    					'label' => 'Awesome Name',
    					'name' => 'awesome_name',
    					'type' => 'text',
    					'default_value' => '',
    					'placeholder' => '',
    					'prepend' => '',
    					'append' => '',
    					'formatting' => 'html',
    					'maxlength' => '',
    				),
    				array (
    					'key' => 'field_562dc9affedd6',
    					'label' => 'Awesome Date',
    					'name' => 'awesome_date',
    					'type' => 'date_picker',
    					'date_format' => 'yymmdd',
    					'display_format' => 'dd/mm/yy',
    					'first_day' => 1,
    				),
    				array (
    					'key' => 'field_562dc9bffedd7',
    					'label' => 'Awesome WYSIWYG',
    					'name' => 'awesome_wysiwyg',
    					'type' => 'wysiwyg',
    					'default_value' => '',
    					'toolbar' => 'full',
    					'media_upload' => 'yes',
    				),
    			),
    			'location' => array (
    				array (
    					array (
    						'param' => 'options_page',
    						'operator' => '==',
    						'value' => 'smashing_fields',
    					),
    				),
    			),
    			'menu_order' => 0,
    			'position' => 'normal',
    			'style' => 'default',
    			'label_placement' => 'top',
    			'instruction_placement' => 'label',
    			'hide_on_screen' => '',
    			'active' => 1,
    			'description' => '',
    		));
    	}
    }

}
new Smashing_Fields_Plugin();
