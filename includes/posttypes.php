<?php

/**
 * add new post types
 *
 * @see https://developer.wordpress.org/resource/dashicons/#menu for dash icons
 */
function fafThemeCustomPostTypes()
{

//    register_post_type('document',
//        array(
//            'labels' => array(
//                'name' => __('Dokumente'),
//                'singular_name' => __('Dokument')
//            ),
//            'public' => true,
//            'has_archive' => true,
//            'rewrite' => array('slug' => 'document'),
//            'menu_icon' => 'dashicons-media-text',
//            'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'revisions'),
//            'menu_position' => 5
//        )
//    );

}
add_action('init', 'fafThemeCustomPostTypes');
