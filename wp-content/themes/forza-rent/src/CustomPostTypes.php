<?php

/**
 * use BoldizArt\WpTheme\Base;
 */

namespace BoldizArt\WpTheme;

class CustomPostTypes
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        // Add actions
        if (function_exists('add_action')) {

            // Register custom post types
            \add_action('init', [$this, 'registerCustomPostTypes']);

            // Register custom taxonomies
            \add_action('init', [$this, 'registerCustomTaxonomies']);
        }
    }

    /**
     * Create a new post type
     */
    function registerCustomPostTypes()
    {
        $labels = [
            'name'               => __('Cars', 'forzarent'),
            'singular_name'      => __('Car', 'forzarent'),
            'menu_name'          => __('Cars', 'forzarent'),
            'all_items'          => __('All Cars', 'forzarent'),
            'add_new_item'       => __('Add New Car', 'forzarent'),
            'edit_item'          => __('Edit Car', 'forzarent'),
            'view_item'          => __('View Car', 'forzarent'),
            'search_items'       => __('Search Cars', 'forzarent'),
            'not_found'          => __('No Cars found', 'forzarent'),
            'not_found_in_trash' => __('No Cars found in trash', 'forzarent'),
        ];

        $args = [
            'labels'              => $labels,
            'public'              => true,
            'show_in_rest'        => true,
            'show_in_menu'        => true,
            'supports'            => ['title', 'editor', 'thumbnail', 'custom-fields', 'revisions'],
            'has_archive'         => true,
            'rewrite'             => ['slug' => 'cars'],
            'menu_icon'           => 'dashicons-car',
            'exclude_from_search' => false,
        ];

        register_post_type('cars', $args);
    }


    /**
     * Register custom taxonomy
     */
    function registerCustomTaxonomies()
    {
        // Car Type
        $car_type_lables = [
            'name'          => __('Car Type', 'forzarent'),
            'singular_name' => __('Car Type', 'forzarent'),
            'search_items'  => __('Search Car Types', 'forzarent'),
            'all_items'     => __('All Car Types', 'forzarent'),
            'edit_item'     => __('Edit Car Type', 'forzarent'),
            'add_new_item'  => __('Add Car Type', 'forzarent'),
        ];

        $car_type_args = [
            'labels'       => $car_type_lables,
            'hierarchical' => true,
            'public'       => true,
            'show_ui'      => true,
            'show_in_rest' => true,
            'rewrite'      => ['slug' => 'car-type']
        ];

        // Car Model
        $car_category_lables = [
            'name'          => __('Car Category', 'forzarent'),
            'singular_name' => __('Car Category', 'forzarent'),
            'search_items'  => __('Search Car Categorys', 'forzarent'),
            'all_items'     => __('All Car Categorys', 'forzarent'),
            'edit_item'     => __('Edit Car Category', 'forzarent'),
            'add_new_item'  => __('Add Car Category', 'forzarent'),
        ];

        $car_category_args = [
            'labels'       => $car_category_lables,
            'hierarchical' => true,
            'public'       => true,
            'show_ui'      => true,
            'show_in_rest' => true,
            'rewrite'      => ['slug' => 'car-category']
        ];



        // Fuel type
        $gearbox_labels = [
            'name'          => __('Gearbox Type', 'forzarent'),
            'singular_name' => __('Gearbox Type', 'forzarent'),
            'search_items'  => __('Search Gearbox Types', 'forzarent'),
            'all_items'     => __('All Gearbox Types', 'forzarent'),
            'edit_item'     => __('Edit Gearbox Type', 'forzarent'),
            'add_new_item'  => __('Add New gearbox Type', 'forzarent'),
        ];

        $gearbox_args = [
            'labels'       => $gearbox_labels,
            'hierarchical' => true,
            'public'       => true,
            'show_ui'      => true,
            'show_in_rest' => true,
            'rewrite'      => ['slug' => 'gearbox-type'],
        ];

        // Drive type
        $drive_labels = [
            'name'          => __('Drive Type', 'forzarent'),
            'singular_name' => __('Drive Type', 'forzarent'),
            'search_items'  => __('Search Drive Types', 'forzarent'),
            'all_items'     => __('All Drive Types', 'forzarent'),
            'edit_item'     => __('Edit Drive Type', 'forzarent'),
            'add_new_item'  => __('Add New drive Type', 'forzarent'),
        ];

        $drive_args = [
            'labels'       => $drive_labels,
            'hierarchical' => true,
            'public'       => true,
            'show_ui'      => true,
            'show_in_rest' => true,
            'rewrite'      => ['slug' => 'drive-type'],
        ];

        // taxonomy: car-category, vezana za CPT: cars
        register_taxonomy('car-type', ['cars'],  $car_type_args);
        register_taxonomy('car-category', ['cars'], $car_category_args);
        register_taxonomy('gearbox-type', ['cars'],  $gearbox_args);
        register_taxonomy('drive-type', ['cars'],  $drive_args);
    }
}
