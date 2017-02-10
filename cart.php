<?php

/**
 * cart.php
 */

$data = Timber::get_context();
$data['posts'] = Timber::get_posts();
$data['cart'] = WC()->cart;

Timber::render('twig/woo/cart.twig', $data);