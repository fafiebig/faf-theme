<?php

/**
 * index.php
 */

$data = Timber::get_context();
Timber::render('twig/404.twig', $data);
