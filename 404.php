<?php

/**
 * 404 error page.
 */

$data = Timber::get_context();
Timber::render('twig/404.twig', $data);