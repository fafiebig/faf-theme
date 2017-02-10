<?php

/**
 * get meta version
 */
function metaVersion()
{
    echo VERSION;
}

/**
 * get meta title
 */
function metaTitle()
{
    $post = get_post();
    echo $post->post_title.' | '.get_bloginfo('name').' | '.get_bloginfo('description');
}

/**
 * get meta description from post excerpt
 */
function metaDescription()
{
    $post = get_post();
    $excerpt = $post->post_excerpt;
    if (!empty($excerpt)) {
        echo strip_tags($excerpt);
    } else {
        echo strip_tags(bloginfo('description'));
    }
}

/**
 * get meta keywords from post tags
 */
function metaKeywords()
{
    $post = get_post();
    $tags = wp_get_post_tags($post->ID);
    $keywords = array();
    foreach ($tags AS $tag) {
        $keywords[] = $tag->name;
    }

    echo strip_tags(implode(', ', $keywords));
}

/**
 * get meta tags
 */
function metaTags()
{
    $post = get_post();
    $tags = wp_get_post_tags($post->ID);

    $keywords = array();

    foreach ($tags AS $tag) {
        $keywords[] = '#'.preg_replace('/\s+/', '', strtolower($tag->name));
    }

    echo strip_tags(implode(' ', $keywords));
}

/**
 * get permalink url
 */
function metaPermalink()
{
    $post = get_post();

    echo get_permalink($post->ID);
}

/**
 * get featured image url
 */
function metaFeaturedImage()
{
    $post = get_post();
    $url = null;
    if (has_post_thumbnail($post->ID)) {
        $url = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large')[0];
    }

    if (null === $url) {
        $attachments = get_children(array(
            'post_parent' => $post->ID,
            'post_status' => 'inherit',
            'post_type' => 'attachment',
            'post_mime_type' => 'image',
            'order' => 'ASC',
            'orderby' => 'menu_order'
        ));

        $attachment = end($attachments);

        $url = wp_get_attachment_image_src($attachment->ID, 'large')[0];
    }

    if (null === $url) {
        $gallery    = get_post_gallery($post, false);
        $ids        = explode(',', $gallery['ids']);
        $id         = end($ids);

        $url = wp_get_attachment_image_src($id, 'large')[0];
    }

    echo $url;
}
