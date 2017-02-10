<?php

/**
 * add new block to admin dashboard
 */
function fafThemeAddBlock()
{
    wp_add_dashboard_widget(
        'faf-block',
        'FAF Block',
        'fafThemeBlock'
    );
}
add_action('wp_dashboard_setup', 'fafThemeAddBlock');

/**
 * @param $columns
 * @return mixed
 */
function fafThemeLayoutColumns($columns)
{
    $columns['dashboard'] = 1;
    return $columns;
}
add_filter('screen_layout_columns', 'fafThemeLayoutColumns');

/**
 * @return int
 */
function fafThemeLayoutDashboard()
{
    return 1;
}
add_filter('get_user_option_screen_layout_dashboard', 'fafThemeLayoutDashboard');

/**
 * content of admin dashboard block
 */
function fafThemeBlock()
{
    echo '
<div class="wrap">
    <h1>FAF Block</h1>
</div>
';
}
