<?php

/**
 * remove auto wrapper for content and excerpts
 */
remove_filter('the_excerpt', 'wpautop');

/**
 * add shortcode support for widgets
 */
add_filter('widget_text', 'do_shortcode');

/**
 * disable admin bar in frontend
 */
add_filter('show_admin_bar', '__return_false');

/**
 * add post formats to pages
 */
add_post_type_support('page', 'post-formats');

/**
 * @param $tag
 * @param $handle
 * @return mixed
 */
function addScriptAtts($tag, $handle)
{
    if ('jquery' === $handle) {
        //return str_replace(' src', ' defer src', $tag);
    }

    return $tag;
}
add_filter('script_loader_tag', 'addScriptAtts', 10, 2);

/**
 * add featured image support for posts and pages
 */
add_theme_support('post-thumbnails', array(
    'post',
    'page',
    'product',
));

/**
 * load translation domain
 */
function fafThemeLoadTextdomain()
{
    load_plugin_textdomain('faf-theme', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'fafThemeLoadTextdomain');

/**
 * add acf google map field support
 *
 * @param $api
 * @return mixed
 */
function fafThemeAcfMapApiKey($api)
{
    $api['key'] = GMAPS_APIKEY;

    return $api;
}
add_filter('acf/fields/google_map/api', 'fafThemeAcfMapApiKey');

/**
 * allow cms access for admin/editor only
 */
function fafThemeAccess()
{
    if (!current_user_can('edit_posts') && false === strpos($_SERVER["REQUEST_URI"], 'admin-ajax.php')) {
        wp_redirect(WP_HOME, 301);
        exit;
    }
}
add_action('admin_init', 'fafThemeAccess');

/**
 * add excerpts to posts and pages
 */
function fafThemeExcerpts()
{
    add_post_type_support('page', 'excerpt');
    add_post_type_support('post', 'excerpt');
}
add_action('init', 'fafThemeExcerpts');

/**
 * add categories to attachment and pages
 */
function fafThemeCategories()
{
    register_taxonomy_for_object_type('category', 'attachment');
    register_taxonomy_for_object_type('category', 'page');
}
add_action('init', 'fafThemeCategories');

/**
 * add tags to attachment and pages
 */
function fafThemeTags()
{
    register_taxonomy_for_object_type('post_tag', 'attachment');
    register_taxonomy_for_object_type('post_tag', 'page');
}
add_action('init', 'fafThemeTags');

/**
 * add media category filter (list view only)
 */
function fafThemeMediaCategoryFilter()
{
    $screen = get_current_screen();

    if ('upload' == $screen->id) {
        $dropdown_options = array(
            'show_option_all' => __('Alle Kategorien'),
            'hide_empty' => false,
            'hierarchical' => true,
            'orderby' => 'name',
        );
        wp_dropdown_categories($dropdown_options);
    }
}
add_action('restrict_manage_posts', 'fafThemeMediaCategoryFilter');