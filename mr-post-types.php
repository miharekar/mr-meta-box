<?php
/*-------------------------------------------------------------------------------------------*/
/* mr_meta_box-gallery Post Type */
/*-------------------------------------------------------------------------------------------*/
add_action('init', 'register_cpt_mr_meta_box_gallery');
function register_cpt_mr_meta_box_gallery() {
	$labels = array(
		'name' => _x('mr_meta_box Galleries', 'mr_meta_box_gallery'),
		'singular_name' => _x('mr_meta_box Gallery', 'mr_meta_box_gallery'),
		'add_new' => _x('Add New', 'mr_meta_box_gallery'),
		'add_new_item' => _x('Add New mr_meta_box Gallery', 'mr_meta_box_gallery'),
		'edit_item' => _x('Edit mr_meta_box Gallery', 'mr_meta_box_gallery'),
		'new_item' => _x('New mr_meta_box Gallery', 'mr_meta_box_gallery'),
		'view_item' => _x('View mr_meta_box Gallery', 'mr_meta_box_gallery'),
		'search_items' => _x('Search mr_meta_box Galleries', 'mr_meta_box_gallery'),
		'not_found' => _x('No mr_meta_box galleries found', 'mr_meta_box_gallery'),
		'not_found_in_trash' => _x('No mr_meta_box galleries found in Trash', 'mr_meta_box_gallery'),
		'parent_item_colon' => _x('Parent mr_meta_box Gallery:', 'mr_meta_box_gallery'),
		'menu_name' => _x('mr_meta_box Galleries', 'mr_meta_box_gallery'),
	);

	$args = array(
		'labels' => $labels,
		'description' => 'Gallery post type for mr_meta_box usage.',
		'supports' => array('title', 'editor'),
		'public' => false,
		'rewrite' => false,
	);
	register_post_type('mr_meta_box_gallery', $args);
}