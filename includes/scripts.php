<?php

/**
 * add scripts/styles to load in frontend
 */
function fafThemeStylesScripts()
{
    // Deregister the jquery version bundled with WordPress.
    wp_deregister_script('jquery');

    // load jquery
    //wp_enqueue_script('jquery', get_template_directory_uri() . '/assets/js/jquery.min.js', array(), '2.2.0', true);

    // load bootstrap js
    //wp_enqueue_script('bootstrap.js', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array('jquery'), '3.3.7', true);

    // load bootstrap css
    //wp_enqueue_style('bootstrap.css', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css', array(), '3.3.7', 'all');

    // load theme styles
    //wp_enqueue_style('faf.css', get_template_directory_uri() . '/assets/css/style.css', array('bootstrap.css'), '1.0.0', 'all');

}
add_action('wp_enqueue_scripts', 'fafThemeStylesScripts');

/**
 * add scripts/styles to load in backend
 */
function fafThemeAdminStyles()
{
    wp_register_style('custom_wp_admin_css', get_template_directory_uri().'/assets/css/admin-styles.css', array(), '1.0.0');
    wp_enqueue_style('custom_wp_admin_css');
}
add_action('admin_enqueue_scripts', 'fafThemeAdminStyles');
