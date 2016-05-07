<?php
/*
 * Plugin Name: WP Custom Favicon
 * Plugin URI: https://github.com/GaganTiwari/wp-custom-favicon.git
 * Description: This Plugin permits you to add and upload custom favicon very easily for your site.
 * Version: 1.0.0
 * Author: Gagan Tiwari
 * Author URI: https://github.com/GaganTiwari/
 * Author Email: gagantiwari61@gmail.com
 */

// Exit if accessed directly

if ( ! defined( 'ABSPATH' ) ) { 
    exit;
}

if ( ! class_exists( 'WP_CUST_FAV' ) ) {
   
          class WP_CUST_FAV {

            /* ---- constructor starts  -------- */
		
		function __construct() {

                        // Adding Plugin admin Menu
			add_action( 'admin_menu', array( &$this, 'wp_cfav_admin_menu' ) );
                        // Load our custom assets.
        	        add_action( 'admin_enqueue_scripts', array( &$this, 'wp_cfav_assets' ) );
                        // Plugin admin settings register.
			add_action( 'admin_init', array( &$this, 'wp_cfav_settings' ) );
                        //  action for adding Favicon to website frontend.
			add_action( 'wp_head', array( &$this, 'wp_cfav_favicon_frontend' ) );
                        // action for adding Favicon to website backend.
			add_action( 'admin_head', array( &$this, 'wp_cfav_favicon_backend' ) );
			add_action( 'login_head', array( &$this, 'wp_cfav_favicon_backend' ) );

		} // here constructor ends


		//admin menu.
		function wp_cfav_admin_menu()
		{
                    add_options_page(__('WP Custom Favicon', 'wp_cfav'), __('WP Custom Favicon', 'wp_cfav'), 'manage_options', 'wp_cfav',  array( &$this, 'wp_cfav_menu_contents'));
                }	

		/**
		 * Defines constants for the plugin.
		 */
		function constants() {
			define( 'WP_CFAV_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
		}

		/*--------------------------------------------*
		 * Load Necessary JavaScript Files
		 *--------------------------------------------*/

		function wp_cfav_assets() {
		    if (isset($_GET['page']) && $_GET['page'] == 'wp_cfav') {

                        // CSS used by Thickbox
    			wp_enqueue_style( 'thickbox' ); 
                        // Script for Thickbox
   		        wp_enqueue_script( 'thickbox' );
                        // Script for Media upload
    			wp_enqueue_script( 'media-upload' );

                        //enque admin js for plugin.
		        wp_register_script('wp_cfav_admin', plugins_url( '/js/wp_cfav_admin.js' , __FILE__ ), array( 'thickbox', 'media-upload' ));
		        wp_enqueue_script('wp_cfav_admin');
                        
                        //enque css for plugin admin.
                        wp_register_style('wp_cfav_admin_css', plugins_url( '/css/main.css' , __FILE__ ));
		        wp_enqueue_style('wp_cfav_admin_css');
                        
		    }
		} //wp_cfav_assets

		/* Admin settings for upload and settings page */

		public function wp_cfav_settings() {

			// Settings
			register_setting( 'wp_cfav_settings', 'wp_cfav_settings', array(&$this, 'wp_cfav_settings_validate') );

			// Custom Favicon
			add_settings_section( 'favicon', __( '<i>Custom Favicon for Frontend and Backend</i>', 'wp_cfav' ), array( &$this, 'section_favicon' ), 'wp_cfav_settings' );

			add_settings_field( 'favicon_frontend_url', __( 'Favicon for Website', 'wp_cfav' ), array( &$this, 'section_favicon_frontend_url' ), 'wp_cfav_settings', 'favicon' );

			add_settings_field( 'favicon_backend_url', __( 'Favicon for Admin', 'wp_cfav' ), array( &$this, 'section_favicon_backend_url' ), 'wp_cfav_settings', 'favicon' );



		}	//wp_cfav_settings


		//wp_cfav_menu_contents start here
		public function wp_cfav_menu_contents() {
		?>
			<div class="wrap">
				
				<div id="icon-options-general" class="icon32"><br></div>
				<h2><?php _e('WP Custom Favicon Settings', 'wp_cfav'); ?></h2>

				<form method="post" action="options.php">
                                    <div class="wp_cfav_left">
					<?php settings_fields('wp_cfav_settings'); ?>
					<?php do_settings_sections('wp_cfav_settings'); ?>
                                    </div>
                                    <div class="wp_cfav_right">
                                        <table class="widefat">
                                            <tr><td class="version_top"><b>v1.0.0</b></td></tr>
						<tr><td class="save_button"><input name="Submit" type="submit" class="button-primary" value="<?php _e('Save Changes', 'wp_cfav'); ?>" />
                                                    </td></tr>
                                        </table>
                                        <table class="widefat developer_tab">
                                            <tr><td class="top"><b><i>Developer</i></b></td></tr>
                                            <tr><td class="version_top"><b><i>Gagan Tiwari</i></b><br/>
                                                    <a href="mailto:gagantiwari61@gmail.com">gagantiwari61@gmail.com</a><br/>
                                                    Github url: <a href="https://github.com/GaganTiwari">https://github.com/GaganTiwari</a>
                                                </td></tr>
                                        </table>
                                    </div>
				</form>
                                
			</div>

		<?php
		}	//wp_cfav_menu_contents

		function section_favicon() 	{


		}

		function section_favicon_frontend_url() {
		    $options = get_option( 'wp_cfav_settings' );
		    ?>
		    <span class='upload'>
		        <input type='text' id='wp_cfav_settings[favicon_frontend_url]' class='regular-text text-upload' name='wp_cfav_settings[favicon_frontend_url]' value='<?php echo esc_url( $options["favicon_frontend_url"] ); ?>'/>
		        <input type='button' class='button button-upload' value='Upload an image'/></br>
		        <img style='max-width: 300px; display: block;' src='<?php echo esc_url( $options["favicon_frontend_url"] ); ?>' class='preview-upload' />
		    </span>
		    <?php
		}

		function section_favicon_backend_url() {
		    $options = get_option( 'wp_cfav_settings' );
		    ?>
		    <span class='upload'>
		        <input type='text' id='wp_cfav_settings[favicon_backend_url]' class='regular-text text-upload' name='wp_cfav_settings[favicon_backend_url]' value='<?php echo esc_url( $options["favicon_backend_url"] ); ?>'/>
		        <input type='button' class='button button-upload' value='Upload an image'/></br>
		        <img style='max-width: 300px; display: block;' src='<?php echo esc_url( $options["favicon_backend_url"] ); ?>' class='preview-upload' />
		    </span>
		    <?php
		}

		/*--------------------------------------------*
		 * Settings Validation
		 *--------------------------------------------*/

		function wp_cfav_settings_validate($input) {

			return $input;
		}


		// Add Favicon to website frontend
		function wp_cfav_favicon_frontend() {
			$options =  get_option('wp_cfav_settings');

			if( $options['favicon_frontend_url'] != "" ) {
		        echo '<link rel="shortcut icon" href="'.  esc_url( $options["favicon_frontend_url"] )  .'"/>'."\n";
		    }
		}

		// Add Favicon to website backend
		function wp_cfav_favicon_backend() {
			$options =  get_option('wp_cfav_settings');

			if( $options['favicon_backend_url'] != "" ) {
		        echo '<link rel="shortcut icon" href="'.  esc_url( $options["favicon_backend_url"] )  .'"/>'."\n";
		    }
		}


	} // Main class ends here.


	//call of plugin
	$wp_cfav = new WP_CUST_FAV(__FILE__);

}



?>
