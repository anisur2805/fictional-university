<?php

function fictional_university_assets() {
	wp_enqueue_style( 'fictional-university-google-font', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i" rel="stylesheet' );
	wp_enqueue_style( 'fictional-university-font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
	wp_enqueue_script( 'fictional-university-index', get_theme_file_uri( '/build/index.js' ), array( 'jquery' ), microtime(), true );
	wp_enqueue_style( 'fictional-university-style', get_stylesheet_uri() );
	wp_enqueue_style( 'fictional-university-index', get_theme_file_uri( '/build/index.css' ), null, microtime() );
	wp_enqueue_style( 'fictional-university-style-index', get_theme_file_uri( '/build/style-index.css' ), null, microtime() );
}

add_action( 'wp_enqueue_scripts', 'fictional_university_assets' );

function fictional_university_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_image_size( 'fictional-university-featured-image', 2000, 1200, true );
}
add_action( 'after_setup_theme', 'fictional_university_setup' );