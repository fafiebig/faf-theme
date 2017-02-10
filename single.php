<?php

/**
 * single.php
 */

$data = Timber::get_context();
$data['posts'] = Timber::get_posts();
Timber::render('twig/page.twig', $data);
