<?php

/**
 * @param $param
 * @return string
 */
function fafThemeShortSuperSearch($param)
{
    $posts      = array();
    $categories = getCategoriesByParam($param);
    $tags       = getTagsByParam($param);

    if (!empty($_GET['pg']) || $param['result']) {
        $args = $_GET;
        $args['pg'] = ($_GET['pg']) ? $_GET['pg'] : 1;
        $args['posts_per_page'] = (int)get_option('posts_per_page');
        $args['post_type'] = ($param['types']) ? explode(',', $param['types']) : ['post'];

        $posts = getPostsBySearch($args);
        $param['total'] = $posts['total'];
        $param['pg'] = $args['pg'];

//        if ($param['total'] >= 1) {
//
//            foreach ($posts['posts'] AS $post) {
//
//                // featured image
//                $imgId = get_post_thumbnail_id($post);
//                $imgSrc = wp_get_attachment_image_url($imgId, 'large');
//                $imgSrcset = wp_get_attachment_image_srcset($imgId, 'large');
//                $imgSizes = wp_get_attachment_image_sizes($imgId, 'large');
//
//                $html .= '<li class="result">';
//                $html .= '<a class="result-link" href="'.get_permalink($post).'" title="'.__('Zum Beitrag').'">';
//                if (has_post_thumbnail($post)) {
//                    $html .= '<span class="result-image">';
//                    $html .= '<img src="'.esc_url($imgSrc).'" srcset="'.esc_attr($imgSrcset).'" sizes="'.esc_attr($imgSizes).'" class="teaser-image" alt="'.apply_filters('the_title', $post->post_title).'"/>';
//                    $html .= '</span>';
//                } else {
//                    $html .= '<span class="result-image x-empty"></span>';
//                }
//                $html .= '<div class="text-wrapper">';
//                $html .= '<h3 class="result-title">'.apply_filters('the_title', $post->post_title).'</h3>';
//                $html .= '<span class="result-excerpt">'.apply_filters('the_excerpt', $post->post_excerpt).'</span>';
//                $html .= '</div>';
//                $html .= '</a>';
//                $html .= '</li>';
//            }
//        }
    }

    $data = array(
        'param'             => $param,
        'pagination'        => getPagination($param),
        'categories'        => $categories,
        'tags'              => $tags,
        'posts'             => $posts,
        'search'            => $_GET['search'],
        'current_category'  => $_GET['category'],
        'current_tag'       => $_GET['tag'],
        'current_from'      => $_GET['from'],
        'current_to'        => $_GET['to'],
    );

    return Timber::compile('twig/short/supersearch.twig', $data);
}
add_shortcode('super_search', 'fafThemeShortSuperSearch');