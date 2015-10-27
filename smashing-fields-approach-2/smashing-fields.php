<?php
/*
	Plugin Name: Smashing Fields Plugin: Approach 2
	Description: Setting up custom fields for our plugin with custom markup
	Author: Matthew Ray
	Version: 1.0.0
*/
class Smashing_Fields_Plugin {

    public function __construct() {
    	// Hook into the admin menu
    	add_action( 'admin_menu', array( $this, 'create_plugin_settings_page' ) );
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

    public function plugin_settings_page_content() {
        if( $_POST['updated'] === 'true' ){
            $this->handle_form();
        } ?>
    	<div class="wrap">
    		<h2>My Awesome Settings Page</h2>
    		<form method="POST">
                <input type="hidden" name="updated" value="true" />
                <?php wp_nonce_field( 'awesome_update', 'awesome_form' ); ?>
                <table class="form-table">
                	<tbody>
                        <tr>
                    		<th><label for="username">Username</label></th>
                    		<td><input name="username" id="username" type="text" value="<?php echo get_option('awesome_username'); ?>" class="regular-text" /></td>
                    	</tr>
                        <tr>
                    		<th><label for="email">Email Address</label></th>
                    		<td><input name="email" id="email" type="text" value="<?php echo get_option('awesome_email'); ?>" class="regular-text" /></td>
                    	</tr>
                	</tbody>
                </table>
                <p class="submit">
                    <input type="submit" name="submit" id="submit" class="button button-primary" value="Send my Info!">
                </p>
    		</form>
    	</div> <?php
    }

    public function handle_form() {
        if( ! isset( $_POST['awesome_form'] ) || ! wp_verify_nonce( $_POST['awesome_form'], 'awesome_update' ) ){ ?>
           <div class="error">
               <p>Sorry, your nonce was not correct. Please try again.</p>
           </div> <?php
           exit;
        } else {
            $valid_usernames = array( 'admin', 'matthew' );
            $valid_emails = array( 'email@domain.com', 'anotheremail@domain.com' );

            $username = sanitize_text_field( $_POST['username'] );
            $email = sanitize_email( $_POST['email'] );

            if( in_array( $username, $valid_usernames ) && in_array( $email, $valid_emails ) ){
                update_option( 'awesome_username', $username );
                update_option( 'awesome_email', $email );?>
                <div class="updated">
                    <p>Your fields were saved!</p>
                </div> <?php
            } else { ?>
                <div class="error">
                    <p>Your username or email were invalid.</p>
                </div> <?php
            }
        }
    }
}
new Smashing_Fields_Plugin();
