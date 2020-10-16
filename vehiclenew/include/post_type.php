<?php 
/*
 * Post Type register template
*/
// Register Custom Post Type
function vehicle_post_type() {

	$labels = array(
		'name'                  => _x( 'Vehicles', 'Post Type General Name', 'ancineha' ),
		'singular_name'         => _x( 'Vehicle', 'Post Type Singular Name', 'ancineha' ),
		'menu_name'             => __( 'Vehicle', 'ancineha' ),
		'name_admin_bar'        => __( 'Vehicle', 'ancineha' ),
		'archives'              => __( 'Item Archives', 'ancineha' ),
		'attributes'            => __( 'Item Attributes', 'ancineha' ),
		'parent_item_colon'     => __( 'Parent Item:', 'ancineha' ),
		'all_items'             => __( 'All Items', 'ancineha' ),
		'add_new_item'          => __( 'Add New Item', 'ancineha' ),
		'add_new'               => __( 'Add New', 'ancineha' ),
		'new_item'              => __( 'New Item', 'ancineha' ),
		'edit_item'             => __( 'Edit Item', 'ancineha' ),
		'update_item'           => __( 'Update Item', 'ancineha' ),
		'view_item'             => __( 'View Item', 'ancineha' ),
		'view_items'            => __( 'View Items', 'ancineha' ),
		'search_items'          => __( 'Search Item', 'ancineha' ),
		'not_found'             => __( 'Not found', 'ancineha' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'ancineha' ),
		'featured_image'        => __( 'Featured Image', 'ancineha' ),
		'set_featured_image'    => __( 'Set featured image', 'ancineha' ),
		'remove_featured_image' => __( 'Remove featured image', 'ancineha' ),
		'use_featured_image'    => __( 'Use as featured image', 'ancineha' ),
		'insert_into_item'      => __( 'Insert into item', 'ancineha' ),
		'uploaded_to_this_item' => __( 'Uploaded to this item', 'ancineha' ),
		'items_list'            => __( 'Items list', 'ancineha' ),
		'items_list_navigation' => __( 'Items list navigation', 'ancineha' ),
		'filter_items_list'     => __( 'Filter items list', 'ancineha' ),
	);
	$args = array(
		'label'                 => __( 'Vehicle', 'ancineha' ),
		'description'           => __( 'Vehicle', 'ancineha' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
		'taxonomies'            => array( 'vichle_type' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
	);
	register_post_type( 'vehicle', $args );

}
add_action( 'init', 'vehicle_post_type', 0 );

// Register Custom Taxonomy
function vehicle_type() {

	$labels = array(
		'name'                       => _x( 'Vehicle Types', 'Taxonomy General Name', 'ancineha' ),
		'singular_name'              => _x( 'Vehicle Type', 'Taxonomy Singular Name', 'ancineha' ),
		'menu_name'                  => __( 'Vehicle Type', 'ancineha' ),
		'all_items'                  => __( 'All Items', 'ancineha' ),
		'parent_item'                => __( 'Parent Item', 'ancineha' ),
		'parent_item_colon'          => __( 'Parent Item:', 'ancineha' ),
		'new_item_name'              => __( 'New Item Name', 'ancineha' ),
		'add_new_item'               => __( 'Add New Item', 'ancineha' ),
		'edit_item'                  => __( 'Edit Item', 'ancineha' ),
		'update_item'                => __( 'Update Item', 'ancineha' ),
		'view_item'                  => __( 'View Item', 'ancineha' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'ancineha' ),
		'add_or_remove_items'        => __( 'Add or remove items', 'ancineha' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'ancineha' ),
		'popular_items'              => __( 'Popular Items', 'ancineha' ),
		'search_items'               => __( 'Search Items', 'ancineha' ),
		'not_found'                  => __( 'Not Found', 'ancineha' ),
		'no_terms'                   => __( 'No items', 'ancineha' ),
		'items_list'                 => __( 'Items list', 'ancineha' ),
		'items_list_navigation'      => __( 'Items list navigation', 'ancineha' ),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);
	register_taxonomy( 'vehicle_type', array( 'vehicle' ), $args );

}
add_action( 'init', 'vehicle_type', 0 );