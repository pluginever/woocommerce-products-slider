<?php

namespace Pluginever\WPS;
/**
 * Class CPT
 *
 * @since 1.0.0
 * @package Pluginever\WPS
 */
class CPT {
    protected $slug;

    /**
     * CPT constructor.
     *
     */
    public function __construct() {
        $this->slug = 'plvr_wc_products_slider';
        add_action( 'init', array( $this, 'register_shortcode_post' ), 0 );
        add_filter( 'manage_plvr_wc_products_slider_posts_columns', array( $this, 'set_shortocode_column' ) );
        add_filter( 'manage_plvr_wc_products_slider_posts_custom_column', array( $this, 'shortocode_column_data' ), 10, 2 );
    }


    public function register_shortcode_post() {
        $labels = array(
            'name'               => _x( 'WooCommerce Products Slider Shortcode', 'post type general name', 'plvrwps' ),
            'singular_name'      => _x( 'WooCommerce Products Slider', 'post type singular name', 'plvrwps' ),
            'menu_name'          => _x( 'Woo Cat Slider', 'admin menu', 'plvrwps' ),
            'name_admin_bar'     => _x( 'WooCommerce Products Slider', 'add new on admin bar', 'plvrwps' ),
            'add_new'            => _x( 'Add New', 'book', 'plvrwps' ),
            'add_new_item'       => __( 'Add New Slider', 'plvrwps' ),
            'new_item'           => __( 'New Slider', 'plvrwps' ),
            'edit_item'          => __( 'Edit Slider', 'plvrwps' ),
            'view_item'          => __( 'View Slider', 'plvrwps' ),
            'all_items'          => __( 'All Sliders', 'plvrwps' ),
            'search_items'       => __( 'Search Slider', 'plvrwps' ),
            'parent_item_colon'  => __( 'Parent Slider:', 'plvrwps' ),
            'not_found'          => __( 'No Slider found.', 'plvrwps' ),
            'not_found_in_trash' => __( 'No Slider found in Trash.', 'plvrwps' )
        );

        $args = array(
            'labels'                => $labels,
            'description'           => __( 'Description.', 'plvrwps' ),
            'public'                => false,
            'publicly_queryable'    => false,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'query_var'             => true,
            'can_export'            => true,
            'capability_type'       => 'post',
            'has_archive'           => false,
            'hierarchical'          => false,
            'menu_position'         => null,
            'menu_icon'             => 'dashicons-images-alt',
            'supports'              => array( 'title' ),
            'show_in_rest'          => true,
            'rest_controller_class' => 'WP_REST_Posts_Controller',
        );

        register_post_type( $this->slug, $args );
    }


    public function set_shortocode_column( $columns ) {
        unset( $columns['date'] );
        $columns['shortcode'] = __( 'Shortcode', 'plvrwps' );
        $columns['date']      = __( 'Date', 'plvrwps' );

        return $columns;
    }

    public function shortocode_column_data( $column, $post_id ) {
        switch ( $column ) {

            case 'shortcode' :
                echo "<code>[plvr_wps id='{$post_id}']</code>";
                break;

        }

    }

}
