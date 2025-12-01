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
        $labels = [
            'name'          => __('Car Categories', 'forzarent'),
            'singular_name' => __('Car Category', 'forzarent'),
            'search_items'  => __('Search Categories', 'forzarent'),
            'all_items'     => __('All Categories', 'forzarent'),
            'edit_item'     => __('Edit Category', 'forzarent'),
            'add_new_item'  => __('Add New Category', 'forzarent'),
            'menu_name'     => __('Car Categories', 'forzarent'),
        ];

        $args = [
            'labels'       => $labels,
            'hierarchical' => true,
            'public'       => true,
            'show_ui'      => true,
            'show_in_rest' => true,
            'rewrite'      => ['slug' => 'car-category']
        ];

        // taxonomy: car-category, vezana za CPT: cars
        register_taxonomy('car-category', ['cars'], $args);
        register_taxonomy_for_object_type('projects', 'portfolio');
    }
}
