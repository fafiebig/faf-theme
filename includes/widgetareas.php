<?php

/**
 * add widget areas
 */
function fafThemeWidgetAreas()
{
    register_sidebar(array(
        'name' => 'Header Widget',
        'id' => 'header-widget',
        'before_widget' => '<div class="header-widget">',
        'after_widget' => '</div>',
        'before_title' => '',
        'after_title' => '',
    ));

    register_sidebar(array(
        'name' => 'Main Widget',
        'id' => 'main-widget',
        'before_widget' => '<div class="main-widget">',
        'after_widget' => '</div>',
        'before_title' => '',
        'after_title' => '',
    ));

    register_sidebar(array(
        'name' => 'Sidebar Widget',
        'id' => 'sidebar-widget',
        'before_widget' => '<div class="sidebar-widget">',
        'after_widget' => '</div>',
        'before_title' => '',
        'after_title' => '',
    ));

    register_sidebar(array(
        'name' => 'Footer Widget',
        'id' => 'footer-widget',
        'before_widget' => '<div class="footer-widget">',
        'after_widget' => '</div>',
        'before_title' => '',
        'after_title' => '',
    ));
}
add_action('widgets_init', 'fafThemeWidgetAreas');

/**
 * @param $name
 * @return mixed
 */
function getTimberWidget($name)
{
    return Timber::get_widgets($name);
}
