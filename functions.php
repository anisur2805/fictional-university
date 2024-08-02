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

	register_nav_menus(
		array(
			'primary_menu' => __( 'Primary Menu', 'fictional-university' ),
			'footer_one'   => __( 'Footer Menu One', 'fictional-university' ),
			'footer_two'   => __( 'Footer Menu Two', 'fictional-university' ),
		)
	);
}

add_action( 'after_setup_theme', 'fictional_university_setup' );

/**
 * Register a custom post type called "event".
 *
 * @see get_post_type_labels() for label keys.
 */
function fictional_university_custom_post_type() {
    $labels = array(
        'name'                  => _x( 'Events', 'Post type general name', 'fictional-university' ),
        'singular_name'         => _x( 'Event', 'Post type singular name', 'fictional-university' ),
        'menu_name'             => _x( 'Events', 'Admin Menu text', 'fictional-university' ),
        'name_admin_bar'        => _x( 'Event', 'Add New on Toolbar', 'fictional-university' ),
        'add_new'               => __( 'Add New', 'fictional-university' ),
        'add_new_item'          => __( 'Add New Event', 'fictional-university' ),
        'new_item'              => __( 'New Event', 'fictional-university' ),
        'edit_item'             => __( 'Edit Event', 'fictional-university' ),
        'view_item'             => __( 'View Event', 'fictional-university' ),
        'all_items'             => __( 'All Events', 'fictional-university' ),
        'search_items'          => __( 'Search Events', 'fictional-university' ),
        'parent_item_colon'     => __( 'Parent Events:', 'fictional-university' ),
        'not_found'             => __( 'No books found.', 'fictional-university' ),
        'not_found_in_trash'    => __( 'No books found in Trash.', 'fictional-university' ),
        'featured_image'        => _x( 'Event Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'fictional-university' ),
        'set_featured_image'    => _x( 'Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'fictional-university' ),
        'remove_featured_image' => _x( 'Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'fictional-university' ),
        'use_featured_image'    => _x( 'Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'fictional-university' ),
        'archives'              => _x( 'Event archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'fictional-university' ),
        'insert_into_item'      => _x( 'Insert into event', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'fictional-university' ),
        'uploaded_to_this_item' => _x( 'Uploaded to this event', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'fictional-university' ),
        'filter_items_list'     => _x( 'Filter books list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'fictional-university' ),
        'items_list_navigation' => _x( 'Events list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'fictional-university' ),
        'items_list'            => _x( 'Events list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'fictional-university' ),
    );
 
    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'events' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_icon'          => 'dashicons-calendar',
        'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
    );
 
    register_post_type( 'event', $args );
}
 
add_action( 'init', 'fictional_university_custom_post_type' );

/**
 * Fictional University Excerpt
 *
 * @return void
 */
function fictional_university_excerpt() {
    if ( has_excerpt() ) {
        echo get_the_excerpt();
    } else {
        echo wp_trim_words( get_the_content(), 130 );
    }
}

function fictional_university_event_archive_set_query( $query ) {

    if ( ! is_admin() && is_post_type_archive( 'event' ) && $query->is_main_query() ) {
        $query->set( 'meta_key', 'event_date' );
        $query->set( 'orderby', 'meta_value_num' );
        $query->set( 'order', 'ASC' );
        $query->set( 'meta_query', array(
            array(
                'key'     => 'event_date',
                'compare' => '>=',
                'value'   => date( 'Ymd' ),
                'type'    => 'numeric'
            )
        ) );
    }
}

add_action( 'pre_get_posts', 'fictional_university_event_archive_set_query' );