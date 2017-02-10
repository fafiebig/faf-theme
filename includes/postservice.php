<?php

/**
 * shorten string to given len, add hellip
 *
 * @param $string
 * @param int $len
 * @return mixed|string
 */
function getShortString($string, $len = 20)
{
    $string = (0 === strpos($string, 'http://')) ? str_replace('http://', '', $string) : $string;
    $string = (0 === strpos($string, 'https://')) ? str_replace('https://', '', $string) : $string;

    return (strlen($string) < $len) ? $string : substr($string, 0, $len).'&hellip;';
}

/**
 * get user meta data
 *
 * @param $id
 * @return array
 */
function getUserMetaData($id)
{
    $retval = [];
    $user = get_user_by('login', $id);

    if (false !== $user) {

        $retval['ID'] = $user->ID;
        $retval['email'] = $user->data->user_email;

        foreach (get_user_meta($user->ID) AS $key => $val) {
            $retval[$key] = $val[0];
        }
    }

    return $retval;
}

/**
 * get category slugs from a post
 *
 * @param $id
 * @return array
 */
function getCategorySlugsByPostId($id)
{
    $retval = [];
    $terms = get_the_terms($id, ['category']);
    foreach ($terms AS $term) {
        $retval[] = $term->slug;
    }

    return $retval;
}

/**
 * show custom menu by slug
 *
 * @param $slug
 */
function showMenu($slug)
{
    if (function_exists('pll_current_language')) {
        $slug = pll_current_language('slug').' '.$slug;
    }
    wp_nav_menu(array(
        'menu' => $slug,
        'theme_location' => '__no_such_location',
        'fallback_cb' => false,
        'container' => 'nav',
        'container_class' => $slug.'-nav'
    ));
}

/**
 * get posts by ids, slugs, category slugs, types
 *
 * @param $param
 * @param null $orderby
 * @param null $order
 * @param null $limit
 * @return array
 */
function getPostsByParam($param, $orderby = null, $order = null, $limit = null)
{
    if (isset($param['ids'])) {
        return getPostsByIds($param['ids'], $orderby, $order, $limit);
    } elseif (isset($param['slugs'])) {
        return getPostsBySlugs($param['slugs'], $orderby, $order, $limit);
    } elseif (isset($param['categories'])) {
        return getPostsByCategorySlugs($param['categories'], $orderby, $order, $limit);
    } elseif (isset($param['types'])) {
        return getPostsByTypes($param['types'], $orderby, $order, $limit);
    }

    return [];
}

/**
 * get posts by ids
 *
 * @param $ids
 * @param null $orderby
 * @param null $order
 * @param null $limit
 * @return array
 */
function getPostsByIds($ids, $orderby = null, $order = null, $limit = null)
{
    $ids = explode(',', $ids);

    if (empty($ids)) {
        return [];
    }

    $ppp = (null !== $limit) ? $limit : -1;

    $query = new WP_Query([
        'posts_per_page' => $ppp,
        'post_type' => ['post'],
        'post_status' => 'publish',
        'post__in' => $ids,
        'orderby' => $orderby,
        'order' => $order
    ]);

    return $query->get_posts();
}

/**
 * get posts by slugs
 *
 * @param $slugs
 * @param null $orderby
 * @param null $order
 * @param null $limit
 * @return array
 */
function getPostsBySlugs($slugs, $orderby = null, $order = null, $limit = null)
{
    $slugs = explode(',', $slugs);

    if (empty($slugs)) {
        return [];
    }

    $ppp = (null !== $limit) ? $limit : -1;

    $query = new WP_Query([
        'posts_per_page' => $ppp,
        'post_type' => ['post'],
        'post_status' => 'publish',
        'post_name__in' => $slugs,
        'orderby' => $orderby,
        'order' => $order
    ]);

    return $query->get_posts();
}

/**
 * get posts by types
 *
 * @param $types
 * @param null $orderby
 * @param null $order
 * @param null $limit
 * @return array
 */
function getPostsByTypes($types, $orderby = null, $order = null, $limit = null)
{
    $types = explode(',', $types);

    if (empty($types)) {
        return [];
    }

    $ppp = (null !== $limit) ? $limit : -1;

    $query = new WP_Query([
        'posts_per_page' => $ppp,
        'post_type' => $types,
        'post_status' => 'publish',
        'orderby' => $orderby,
        'order' => $order
    ]);

    return $query->get_posts();
}

/**
 * get posts by category slugs
 *
 * @param $slugs
 * @param null $orderby
 * @param null $order
 * @param null $limit
 * @return array
 */
function getPostsByCategorySlugs($slugs, $orderby = null, $order = null, $limit = null)
{
    $slugs = explode(',', $slugs);

    if (empty($slugs)) {
        return [];
    }

    $ppp = (null !== $limit) ? $limit : -1;

    $query = new WP_Query([
        'posts_per_page' => $ppp,
        'post_type' => ['post'],
        'post_status' => 'publish',
        'orderby' => $orderby,
        'order' => $order,
        'tax_query' => [
            'relation' => 'OR',
            [
                'taxonomy' => 'category',
                'field' => 'slug',
                'terms' => $slugs,
            ]
        ]
    ]);

    return $query->get_posts();
}

/**
 * get posts by search params
 *
 * @param $param
 * @return array
 */
function getPostsBySearch($param)
{
    $args = [];
    $args['post_status'] = 'publish';
    $args['post_type'] = $param['post_type'];
    $args['posts_per_page'] = $param['posts_per_page'];
    $args['offset'] = abs(($param['pg'] * $param['posts_per_page']) - $param['posts_per_page']);
    $args['orderby'] = 'date';
    $args['order'] = 'DESC';

    if (!empty($param['category'])) {
        $args['tax_query'] = [
            'relation' => 'OR',
            [
                'taxonomy' => 'category',
                'field' => 'slug',
                'terms' => [$param['category']],
            ]
        ];
    }

    if (!empty($param['tag'])) {
        $args['tag'] = $param['tag'];
    }

    if (!empty($param['search'])) {
        $args['s'] = $param['search'];
    }

    if (!empty($param['from']) && !empty($param['to'])) {
        $args['date_query'] = [
            'after' => $param['from'],
            'before' => $param['to']
        ];
    }

    $query = new WP_Query($args);

    return [
        'total' => $query->max_num_pages,
        'posts' => $query->get_posts()
    ];
}

/**
 * get pagination
 *
 * @param $param
 * @return string
 */
function getPagination($param)
{
    $get = $_GET;
    $links = 3;
    $total = $param['total'];
    $page = $param['pg'];
    $last = $total;
    $start = (($page - $links) > 0) ? $page - $links : 1;
    $end = (($page + $links) < $last) ? $page + $links : $last;
    $html = '<ul class="b-pagination">';
    $class = ($page == 1) ? "m-disabled" : "";

    $get['pg'] = (($page - 1) > 0) ? ($page - 1) : 1;
    $html .= '<li class="prev"><a href="?'.http_build_query($get).'" class="prev-link '.$class.'" title="'.__('Zur vorherigen Seite').'"><<</a></li>';

    if ($start > 1) {
        $get['pg'] = 1;
        $html .= '<li class="page-item"><a href="?'.http_build_query($get).'" class="page-link" title="'.__('Zur Seite 1').'">1</a></li>';
        $html .= '<li class="page-item m-disabled">...</li>';
    }

    for ($i = $start; $i <= $end; $i++) {
        $get['pg'] = $i;
        $class = ($page == $i) ? "m-active" : "";
        $html .= '<li class="page-item"><a href="?'.http_build_query($get).'" class="page-link '.$class.'" title="'.__('Zur Seite ').$i.'">'.$i.'</a></li>';
    }

    if ($end < $last) {
        $get['pg'] = $last;
        $html .= '<li class="page-item m-disabled">...</li>';
        $html .= '<li class="page-item"><a href="?'.http_build_query($get).'" class="page-link" title="'.__('Zur Seite ').$last.'">'.$last.'</a></li>';
    }


    $get['pg'] = (($page + 1) < $last) ? ($page + 1) : $last;
    $class = ($page == $end) ? "m-disabled" : "";
    $html .= '<li class="next"><a href="?'.http_build_query($get).'" class="next-link '.$class.'" title="'.__('Zur nächsten Seite').'">>></a></li>';

    $html .= '</ul>';

    return $html;
}

/**
 * get tags by slugs
 *
 * @param $param
 * @return array
 */
function getTagsByParam($param)
{
    if (isset($param['tags'])) {
        return getTagsBySlugs($param['tags']);
    }

    return [];
}

/**
 * get tags by slugs
 *
 * @param $slugs
 * @return array
 */
function getTagsBySlugs($slugs)
{
    $retval = [];
    $slugs = explode(',', $slugs);

    foreach ($slugs as $slug) {
        if ($tag = get_term_by('slug', $slug, 'post_tag')) {
            $retval[] = $tag;
        }
    }

    return $retval;
}

/**
 * get categories by slugs, taxonomies, parent slugs
 *
 * @param $param
 * @return array
 */
function getCategoriesByParam($param)
{
    if (isset($param['categories'])) {
        return getCategoriesBySlugs($param['categories']);

    } elseif (isset($param['taxonomies'])) {
        return getCategoriesByTaxonomies($param['taxonomies']);

    } elseif (isset($param['parents'])) {
        return getCategoriesByParents($param['parents']);
    }

    return [];
}

/**
 * get categories by slugs
 *
 * @param $slugs
 * @return array
 */
function getCategoriesBySlugs($slugs)
{
    $retval = [];
    $slugs = explode(',', $slugs);

    foreach ($slugs as $slug) {
        if ($category = get_category_by_slug($slug)) {
            $retval[] = $category;
        }
    }

    return $retval;
}

/**
 * get categories by taxonomies
 *
 * @param $taxonomies
 * @return array
 */
function getCategoriesByTaxonomies($taxonomies)
{
    $taxonomies = explode(',', $taxonomies);
    $args = [
        'hide_empty' => 0,
        //'orderby' => 'name',
        'hierarchical' => true,
        'taxonomy' => $taxonomies
    ];

    return get_categories($args);
}

/**
 * get categories by parent slugs
 *
 * @param $parents
 * @return array
 */
function getCategoriesByParents($parents)
{
    $retval = [];
    $parents = explode(',', $parents);

    foreach ($parents as $parent) {

        $category = get_category_by_slug($parent);

        $args = [
            'type' => 'post',
            'child_of' => $category->term_id,
            'hide_empty' => false,
            'hierarchical' => 1,
            'taxonomy' => 'category',
        ];

        $children = get_categories($args);

        $retval = array_merge($retval, $children);
    }

    return $retval;
}
