<?php
/*
	Plugin Name: Smashing Fields Plugin: Approach 1
	Description: Setting up custom fields for our plugin.
	Author: Matthew Ray
	Version: 1.0.0
*/
class Smashing_Fields_Plugin {

    public function __construct() {
    	// Hook into the admin menu
    	add_action( 'admin_menu', array( $this, 'create_plugin_settings_page' ) );

        // Add Settings and Fields
    	add_action( 'admin_init', array( $this, 'setup_sections' ) );
    	add_action( 'admin_init', array( $this, 'setup_fields' ) );
    }

    public function create_plugin_settings_page() {
    	// Add the menu item and page
    	$page_title = 'My Awesome Settings Page';
    	$menu_title = 'Awesome Plugin';
    	$capability = 'manage_options';
    	$slug = 'smashing_fields';
    	$callback = array( $this, 'plugin_settings_page_content' );
    	$icon = 'dashicons-admin-plugins';
    	$position = 10;

    	add_menu_page( $page_title, $menu_title, $capability, $slug, $callback, $icon, $position );
    }

    public function plugin_settings_page_content() { ?>
    	<div class="wrap">
    		<h2>My Awesome Settings Page</h2>
    		<form method="POST" action="options.php">
                <?php
                    settings_fields( 'smashing_fields' );
                    do_settings_sections( 'smashing_fields' );
                    submit_button();
                ?>
    		</form>
    	</div> <?php
    }

    public function setup_sections() {
        add_settings_section( 'our_first_section', 'My First Section Title', array( $this, 'section_callback' ), 'smashing_fields' );
        add_settings_section( 'our_second_section', 'My Second Section Title', array( $this, 'section_callback' ), 'smashing_fields' );
        add_settings_section( 'our_third_section', 'My Third Section Title', array( $this, 'section_callback' ), 'smashing_fields' );
    }

    public function section_callback( $arguments ) {
    	switch( $arguments['id'] ){
    		case 'our_first_section':
    			echo 'This is the first description here!';
    			break;
    		case 'our_second_section':
    			echo 'This one is number two';
    			break;
    		case 'our_third_section':
    			echo 'Third time is the charm!';
    			break;
    	}
    }

    public function setup_fields() {
        $fields = array(
        	array(
        		'uid' => 'our_first_field',
        		'label' => 'Awesome Date',
        		'section' => 'our_first_section',
        		'type' => 'text',
        		'options' => false,
        		'placeholder' => 'DD/MM/YYYY',
        		'helper' => 'Does this help?',
        		'supplimental' => 'I am underneath!',
        		'default' => '01/01/2015'
        	),
        	array(
        		'uid' => 'our_second_field',
        		'label' => 'Awesome Date',
        		'section' => 'our_first_section',
        		'type' => 'textarea',
        		'options' => false,
        		'placeholder' => 'DD/MM/YYYY',
        		'helper' => 'Does this help?',
        		'supplimental' => 'I am underneath!',
        		'default' => '01/01/2015'
        	),
        	array(
        		'uid' => 'our_third_field',
        		'label' => 'Awesome Select',
        		'section' => 'our_first_section',
        		'type' => 'select',
        		'options' => array(
        			'yes' => 'Yeppers',
        			'no' => 'No way dude!',
        			'maybe' => 'Meh, whatever.'
        		),
        		'placeholder' => 'Text goes here',
        		'helper' => 'Does this help?',
        		'supplimental' => 'I am underneath!',
        		'default' => 'maybe'
        	)
        );
    	foreach( $fields as $field ){

        	add_settings_field( $field['uid'], $field['label'], array( $this, 'field_callback' ), 'smashing_fields', $field['section'], $field );
            register_setting( 'smashing_fields', $field['uid'] );
    	}
    }

    public function field_callback( $arguments ) {

        $value = get_option( $arguments['uid'] );
        if( ! $value ) {
            $value = $arguments['default'];
        }

        switch( $arguments['type'] ){
            case 'text':
                printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value );
                break;
            case 'textarea':
                printf( '<textarea name="%1$s" id="%1$s" placeholder="%2$s" rows="5" cols="50">%3$s</textarea>', $arguments['uid'], $arguments['placeholder'], $value );
                break;
            case 'select':
                if( ! empty ( $arguments['options'] ) && is_array( $arguments['options'] ) ){
                    $options_markup = '';
                    foreach( $arguments['options'] as $key => $label ){
                        $options_markup .= sprintf( '<option value="%s" %s>%s</option>', $key, selected( $value, $key, false ), $label );
                    }
                    printf( '<select name="%1$s" id="%1$s">%2$s</select>', $arguments['uid'], $options_markup );
                }
                break;
        }

        if( $helper = $arguments['helper'] ){
            printf( '<span class="helper"> %s</span>', $helper );
        }

        if( $supplimental = $arguments['supplimental'] ){
            printf( '<p class="description">%s</p>', $supplimental );
        }

    }

}
new Smashing_Fields_Plugin();
