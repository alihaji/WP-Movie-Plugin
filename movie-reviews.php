<?php

/**
 * Plugin Name: movie reviews
 * Plugin URI: http://alihaji.info
 * Description: This plugin adds great movie reviews to your website
 * Version: 1.0.0
 * Author: Ali Haji 
 * Author URI: http://alihaji.info
 * License: GPL2
 */


add_action( 'init', 'mr_post_type' );

function mr_post_type() {
	$labels = array(
		'name'               => _x( 'movie reviews', 'post type general name', 'movie-reviews' ),
		'singular_name'      => _x( 'movie reviews', 'post type singular name', 'movie-reviews' ),
		'menu_name'          => _x( 'movie reviews', 'admin menu', 'movie-reviews' ),
		'name_admin_bar'     => _x( 'movie reviews', 'add new on admin bar', 'movie-reviews' ),
		'add_new'            => _x( 'Add New', 'movie', 'movie-reviews' ),
		'add_new_item'       => __( 'Add New review', 'movie-reviews' ),
		'new_item'           => __( 'New movie review', 'movie-reviews' ),
		'edit_item'          => __( 'Edit movie review', 'movie-reviews' ),
		'view_item'          => __( 'View movie review', 'movie-reviews' ),
		'all_items'          => __( 'All movie reviews', 'movie-reviews' ),
		'search_items'       => __( 'Search movie reviews', 'movie-reviews' ),
		'parent_item_colon'  => __( 'Parent movie reviews:', 'movie-reviews' ),
		'not_found'          => __( 'No movie reviews found.', 'movie-reviews' ),
		'not_found_in_trash' => __( 'No movie reviews found in Trash.', 'movie-reviews' )
	);

	$args = array(
		'labels'             => $labels,
        'description'        => __( 'movie reviwes for wordpress websites.', 'movie-reviews' ),
		'public'             => true,
		'rewrite'            => array( 'slug' => 'movie_review' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments', 'custom_field' ),
		'menu_icon'          =>	'dashicons-video-alt2'	
	);

	register_post_type( 'movie_review', $args );
}


add_filter( 'the_content', 'prepend_movie_data');
function prepend_movie_data( $content) {
	if( is_singular('movie_review')){
		
		// getting data from custom field area
		$author = get_post_meta( get_the_id(), 'author', true);
		$score = get_post_meta( get_the_id(), 'score', true);
		// creating html here and then prepend it to content 
		$html = '
		<div class="movie-meta">
			<strong>Author: </strong> '.$author.' <br>
			<strong>Score: </strong> '.$score.' <br>
		</div>
		';

	return $content . $html;
	}
	
	return $content;
}


add_action('pre_get_posts', 'add_movie_review_to_query');
function add_movie_review( $query ){
	// adding this hook only to movie-review not all post . 
	// be careful when working with pre_get_post
	// always use conditional statemnt when using pre_get_post
	if( !is_admin()){
	$query->set( 'post_type', array('post,movie_review') );
}
}

	// we adding a post type to the post title which works across all themes
add_filter( 'the_title', 'prepend_post_type', 10, 2);
function prepend_post_type( $title, $id ){
	if( is_home()) {
	$post_type = get_post_type( $id );
	$types = array(
		'post' => 'blog',
		'movie_review' => 'Review',
	);
	
	return '<small>' . $type[$post_type] . ':</small><br>' . $title ;
	}
	return $title;
}




