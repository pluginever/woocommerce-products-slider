<?php
namespace Pluginever\PLVR_WPS;

class Scripts{

	/**
	 * Constructor for the class
	 *
	 * Sets up all the appropriate hooks and actions
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'wp_enqueue_scripts', array( $this, 'load_assets') );
    }

   	/**
	 * Add all the assets required by the plugin
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	function load_assets(){
		$suffix = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? '' : '.min';
		wp_register_style('plvr-wc-products-slider', PLVR_WPS_ASSETS.'/css/plvr-wc-products-slider{$suffix}.css', [], date('i'));
		wp_register_script('plvr-wc-products-slider', PLVR_WPS_ASSETS.'/js/plvr-wc-products-slider{$suffix}.js', ['jquery'], date('i'), true);
		wp_localize_script('plvr-wc-products-slider', 'jsobject', ['ajaxurl' => admin_url( 'admin-ajax.php' )]);
		wp_enqueue_style('plvr-wc-products-slider');
		wp_enqueue_script('plvr-wc-products-slider');
	}

    

}