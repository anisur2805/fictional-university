<?php

function fictional_university_assets() {
	wp_enqueue_style( 'fictional-university-style', get_stylesheet_uri() );
}

add_action( 'wp_enqueue_scripts', 'fictional_university_assets' );
