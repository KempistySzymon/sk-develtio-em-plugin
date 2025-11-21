<?php

// Rejestracja taksonomii
function em_register_taxonomy() {
	 $labels = array(
		 'name'              => _x( 'Cities', 'taxonomy general name' ),
		 'singular_name'     => _x( 'City', 'taxonomy singular name' ),
		 'search_items'      => __( 'Search Cities' ),
		 'all_items'         => __( 'All Cities' ),
		 'parent_item'       => __( 'Parent City' ),
		 'parent_item_colon' => __( 'Parent City:' ),
		 'edit_item'         => __( 'Edit City' ),
		 'update_item'       => __( 'Update City' ),
		 'add_new_item'      => __( 'Add New City' ),
		 'new_item_name'     => __( 'New City Name' ),
		 'menu_name'         => __( 'City' ),
	 );
	 $args   = array(
		 'hierarchical'      => true,
		 'labels'            => $labels,
		 'show_ui'           => true,
		 'show_admin_column' => true,
		 'query_var'         => true,
		 'rewrite'           => [ 'slug' => 'city' ],
	 );
	 register_taxonomy( 'city', [ 'event' ], $args );
}



// Rejestracja CPT
function em_register_post_type() {
	register_post_type('event',
		array(
			'labels'      => array(
				'name'          => __( 'Events', 'event-manager' ),
				'singular_name' => __( 'Event', 'event-manager' ),
			),
			'public'      => true,
			'has_archive' => true,
			'rewrite'     => array( 'slug' => 'events' ),
            'taxonomies' => array('city') // Powiązanie CPT z taksonomią City
		)
	);
} 
