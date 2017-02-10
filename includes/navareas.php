<?php

/**
 * add nav areas
 */
function fafThemeNavAreas()
{
    register_nav_menus([
        'header-nav'    => 'Header Navigation',
        'main-nav'      => 'Main Navigation',
        'sidebar-nav'   => 'Sidebar Navigation',
        'footer-nav'    => 'Footer Navigation'
    ]);
}
add_action('after_setup_theme', 'fafThemeNavAreas');

/**
 * @param $name
 * @return mixed
 */
function getTimberMenu($name, $class = '')
{
    $post           = get_post();
    $menu           = new TimberMenu($name);
    $data['items']  = $menu->get_items;
    $data['class']  = $name.' '.$class;
    $data['slug']   = $post->post_name;

    return Timber::compile('twig/parts/menu.twig', $data);
}