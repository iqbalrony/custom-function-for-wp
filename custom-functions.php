<?php
/**
 * Post list
 */
if (!function_exists('prefix_get_all_posts')) {
	function prefix_get_all_posts($posttype = 'post') {
		$args = array(
			'post_type' => $posttype,
			'post_status' => 'publish',
			'posts_per_page' => -1
		);

		$post_list = array();
		if ($data = get_posts($args)) {
			foreach ($data as $key) {
				$post_list[$key->ID] = $key->post_title;
			}
		}
		return $post_list;
	}
}

/**
 * Taxonomy list
 */
if (!function_exists('prefix_taxonomy_list')) {
	function prefix_taxonomy_list($taxonomy = 'category') {

		$taxonomy_exist = taxonomy_exists($taxonomy);
		if (!$taxonomy_exist) {
			return;
		}
		$terms = get_terms(array(
			'taxonomy' => $taxonomy,
			'hide_empty' => false
		));

		$get_terms = array();

		if (!empty($terms)) {
			foreach ($terms as $term) :
				$get_terms[$term->slug] = $term->name;
			endforeach;
		}

		return $get_terms;
	}
}

/**
 * Contact form 7 list
 */
if (!function_exists('prefix_contact_form7')) {
	function prefix_contact_form7() {
		if (!post_type_exists('wpcf7_contact_form')) {
			return array();
		}
		$args = array(
			'posts_per_page' => -1,
			'post_type' => 'wpcf7_contact_form',
		);
		$posts_array = get_posts($args);
		$posts_title = array();
		//$posts_title = array('' => '-- Select form --', );
		foreach ($posts_array as $post) {
			$posts_title[$post->ID] = $post->post_title;
		}
		return $posts_title;
	}
}