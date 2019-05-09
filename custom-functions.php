<?php
/**
 * Necessary function, which can be used in making of theme
 * for client or ThemeForest or helper plugin for page builder.
 */

/**
 * Get all Post list
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
 * Get all Taxonomy list
 */
if (!function_exists('prefix_get_taxonomy_list')) {
	function prefix_get_taxonomy_list($taxonomy = 'category') {

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
 * Get all Contact form 7 list
 */
if (!function_exists('prefix_get_contact_form7')) {
	function prefix_get_contact_form7() {
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

/**
 * Function for Allow HTML Tag
 */
if (!function_exists('prefix_allowed_html')) {
	function prefix_allowed_html($string) {
		$allowed_html = array(
			'div' => array(
				'id' => array(),
				'class' => array()
			),
			'p' => array(
				'id' => array(),
				'class' => array()
			),
			'span' => array(
				'class' => array()
			),
			'img' => array(
				'src' => array(),
				'alt' => array(),
				'class' => array()
			),
			'a' => array(
				'href' => array(),
				'title' => array(),
				'class' => array()
			),
			'i' => array(
				'class' => array()
			),
			'br' => array(),
			'em' => array(),
			'strong' => array(),
		);
		return wp_kses($string, $allowed_html);
	}
}

/**
 * Function for convert Hex To Rgb Color
 */
if (!function_exists('prefixHexToRgb')) {
	function prefixHexToRgb($hex, $alpha = '', $type = 'string') {
		if (!empty($hex) && strpos($hex, '#') != 0) {
			return;
		}
		$hex = str_replace('#', '', $hex);
		$length = strlen($hex);
		$rgb['r'] = hexdec($length == 6 ? substr($hex, 0, 2) : ($length == 3 ? str_repeat(substr($hex, 0, 1), 2) : 0));
		$rgb['g'] = hexdec($length == 6 ? substr($hex, 2, 2) : ($length == 3 ? str_repeat(substr($hex, 1, 1), 2) : 0));
		$rgb['b'] = hexdec($length == 6 ? substr($hex, 4, 2) : ($length == 3 ? str_repeat(substr($hex, 2, 1), 2) : 0));
		if (!empty($alpha)) {
			$rgb['a'] = $alpha;
		}
		if ($type == 'string') {
			if (!empty($alpha)) {
				$rgb = 'rgba(' . implode(', ', $rgb) . ')';
			} else {
				$rgb = 'rgb(' . implode(', ', $rgb) . ')';
			}
		}
		return $rgb;
	}
}

/**
 * Function for custom excerpt
 */
if (!function_exists('prefix_excerpt')) {
	function prefix_excerpt($sl_length = '', $sl_sign = '') {

		if (empty($sl_length)) {
			$length = apply_filters('prefix_excerpt_length', 23);
		} else {
			$length = $sl_length;
		}

		if (empty($sl_sign)) {
			$more = apply_filters('prefix_excerpt_more', '');
		} else {
			$more = $sl_sign;
		}

		printf('<p>%1$s</p>',
			wp_trim_words(get_the_content(), $length, $more)
		);
	}
}

/**
 * Register Google fonts.
 */
if (!function_exists('prefix_fonts_url')) {
	function prefix_fonts_url() {
		$fonts_url = '';
		$fonts = array();
		$subsets = 'latin,latin-ext';

		/* translators: If there are characters in your language that are not supported by Merriweather, translate this to 'off'. Do not translate into your own language. */
		if ('off' !== _x('on', 'Muli: on or off', 'text_domain')) {
			$fonts[] = 'Muli:400,600,700';
		}

		if ($fonts) {
			$fonts_url = add_query_arg(array(
				'family' => urlencode(implode('|', $fonts)),
				'subset' => urlencode($subsets),
			), 'https://fonts.googleapis.com/css');
		}

		return $fonts_url;
	}
}

/**
 * Function for get cmb2 Meta value
 */
if (!function_exists('prefix_page_option')) {
	function prefix_page_option($uniq_id) {
		if (defined('CMB2_LOADED') && !is_home() && !is_archive() && !is_search() && !is_404()) {
			return get_post_meta(get_the_ID(), $uniq_id, true);
		} else {
			return '';
		}
	}
}

/**
 * Function for Page Layout Option
 */
if (!function_exists('prefix_page_layout')) {
	function prefix_page_layout() {
		$prefix_customizer = get_theme_mod('prefix_page_layout', 'right');
		$page_layout_option = prefix_page_option('_prefix_page_layout_option');
		$prefix_page = !empty($page_layout_option) ? $page_layout_option : 'default';

		$layout = $prefix_page;
		if (empty($layout) || $layout == 'default') {
			$layout = $prefix_customizer;
		}
		if (!is_active_sidebar('sidebar-1')) {
			$layout = 'without';
		}
		return $layout;
	}
}

/**
 * Function for default blog paginations
 */
if (!function_exists('prefix_blog_paginations')) {

	function prefix_blog_paginations() {
		$args = array(
			'prev_text' => '<i class="ti-angle-left"></i>',
			'next_text' => '<i class="ti-angle-right"></i>',
			'type' => 'list'
		);
		echo paginate_links($args);
	}
}

/**
 * Function for default blog paginations2
 */
if (!function_exists('prefix_blog_paginations2')) {

	function prefix_blog_paginations2() {

		global $wp_query;
		$big = 999999999; // need an unlikely integer
		$html = paginate_links(array(
			'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
			'format' => '/page/%#%',
			'current' => max(1, get_query_var('paged')),
			'total' => $wp_query->max_num_pages,
			'end_size' => 2,
			'prev_text' => '<i class="ti-angle-left"></i>',
			'next_text' => '<i class="ti-angle-right"></i>',
		));
		$pretext = '<i class="ti-angle-left"></i>';
		$posttext = '<i class="ti-angle-right"></i>';
		$pre_deco = '<a href="" class="prev page-numbers custom">' . $pretext . '</a>';
		$post_deco = '<a href="" class="next page-numbers custom">' . $posttext . '</a>';
		$paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;
		if (1 === $paged) {
			$html = $pre_deco . $html;
		}
		if ($wp_query->max_num_pages == $paged) {
			$html = $html . $post_deco;
		}
		if (1 != $wp_query->max_num_pages) {
			echo '<div class="custom-pagination">' . prefix_ekoo($html) . '</div>';
		} else {
			return;
		}
	}
}

/**
 * Function for comments paginations
 */
if (!function_exists('prefix_comments_paginations')) {

	function prefix_comments_paginations() {
		$args = array(
			'prev_text' => '<i class="ti-angle-left"></i>',
			'next_text' => '<i class="ti-angle-right"></i>',
			'type' => 'list'
		);
		paginate_comments_links($args);
	}
}

/**
 * Function for blog author
 */
if (!function_exists('prefix_blog_author')) {
	function prefix_blog_author($style = '') {
		if ('style-1' == $style) {
			$span_tag = '<span>' . esc_html__('Posted By: ', 'text_domain') . '</span>';
		} else {
			$span_tag = '';
		}
		$author_id = get_post_field('post_author', get_the_ID());
		$author_name = get_the_author_meta('display_name', $author_id);
		$url = get_author_posts_url($author_id);
		$author = '<div class="author">' . $span_tag . '<a href="' . esc_url($url) . '">' . esc_html($author_name) . '</a></div>';
		print_r($author);
	}
}

/**
 * Function for post date
 */
if (!function_exists('prefix_post_on')) {
	function prefix_post_on($style = '') {

		if ('style-1' == $style) {
			$span_tag = '<span>' . esc_html__('Posted : ', 'text_domain') . '</span>';
		} else {
			$span_tag = '';
		}
		$year = get_the_date('Y');
		$month = get_the_time('m');
		$day = get_the_time('d');
		$url = get_day_link($year, $month, $day);
		$date = '<div class="date">' . $span_tag . '<a href="' . esc_url($url) . '">' . esc_html(get_the_date('d M Y')) . '</a></div>';
		print_r($date);
	}
}

/**
 * Function for post's single category (default category)
 */
if (!function_exists('prefix_single_cat')) {
	function prefix_single_cat($style = '') {
		if ('style-1' == $style) {
			$span_tag = '<span>' . esc_html__('Category: ', 'text_domain') . '</span>';
		} else {
			$span_tag = '';
		}
		$cats = get_the_category(get_the_ID());
		if (!empty($cats) && isset($cats[0]->name)) {
			printf('<div class="single-cat">%1$s<a href="%2$s" class="cat-links">%3$s</a></div>', $span_tag, get_category_link($cats[0]->term_id), esc_html($cats[0]->name));
		}
	}
}


/**
 * Function for show default post tags list
 */
if (!function_exists('prefix_tags_list')) {

	function prefix_tags_list($style = '') {
		if ('style-1' == $style) {
			$span_tag = '<span>' . esc_html__('Tag: ', 'text_domain') . '</span>';
		} else {
			$span_tag = '';
		}
		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list('', ' ');
		if ($tags_list) {
			/* translators: 1: list of tags. */
			printf('<div class="tags-links">%1$s %2$s</div>', $span_tag, $tags_list); // WPCS: XSS OK.
		}
	}
}

/**
 * Function for get single taxonomy
 */
if (!function_exists('prefix_get_comment_number')) {
	function prefix_get_comment_number($taxonomy = 'post_tag') {
		$taxonomy_exist = taxonomy_exists($taxonomy);
		if (!$taxonomy_exist) {
			return;
		}
		$prefix_terms = get_the_terms(get_the_id(), $taxonomy);
		$single_cat = '';
		$i = 0;
		if (is_array($prefix_terms)) {
			foreach ($prefix_terms as $prefix_term) {
				$prefix_link = get_term_link($prefix_term);
				$prefix_term_name = $prefix_term->name;
				$single_cat = sprintf('<div class="single-cat"><a href="%1$s" class="cat-links">%2$s </a></div>', $prefix_link, $prefix_term_name);
				if ($i == 0) {
					break;
				}
			}
		}
		return $single_cat;
	}
}

/**
 * Function for get comment number
 */
if (!function_exists('prefix_get_comment_number')) {

	function prefix_get_comment_number($style = '', $link = true) {
		$number = get_comments_number(get_the_ID());
		if ('style-1' == $style) {
			$span_tag = '<span>' . esc_html__('Comments: ', 'text_domain') . '</span>';
		} else {
			$span_tag = '';
		}
		if ($link == true) {
			printf('<div class="comments-number">%1$s<a href="%2$s">%3$s</a></div>', $span_tag, get_comments_link(), $number);
		} else {
			printf('<div class="comments-number">%1$s%2$s</div>', $span_tag, $number);
		}
	}
}

/**
 * Custom Archive Title modifier
 */
if (!function_exists('prefix_get_the_archive_title')) {
	function prefix_get_the_archive_title() {

		if (is_category()) {
			/* translators: Category archive title. 1: Category name */
			$title = single_cat_title('', false);
		} elseif (is_tag()) {
			/* translators: Tag archive title. 1: Tag name */
			$title = single_tag_title('', false);
		} elseif (is_author()) {
			/* translators: Author archive title. 1: Author name */
			$title = get_the_author();
		} elseif (is_year()) {
			/* translators: Yearly archive title. 1: Year */
			$title = get_the_date(_x('Y', 'yearly archives date format', 'text_domain'));
		} elseif (is_month()) {
			/* translators: Monthly archive title. 1: Month name and year */
			$title = get_the_date(_x('F Y', 'monthly archives date format', 'text_domain'));
		} elseif (is_day()) {
			/* translators: Daily archive title. 1: Date */
			$title = get_the_date(_x('F j, Y', 'daily archives date format', 'text_domain'));
		} elseif (is_post_type_archive()) {
			/* translators: Post type archive title. 1: Post type name */
			$title = post_type_archive_title('', false);
			if (is_search()) {
				$title = esc_html__('Search Results for: ', 'text_domain') . esc_html(get_search_query());
			}
		} elseif (is_tax()) {
			$tax = get_taxonomy(get_queried_object()->taxonomy);
			/* translators: Taxonomy term archive title. 1: Taxonomy singular name, 2: Current taxonomy term */
			$title = sprintf(__('%1$s: %2$s', 'text_domain'), $tax->labels->singular_name, single_term_title('', false));
		} else {
			$title = esc_html__('Archives', 'text_domain');
		}

		/**
		 * Filters the archive title.
		 *
		 * @param string $title Archive title to be displayed.
		 */
		return apply_filters('prefix_get_the_archive_title', $title);
	}
}

/**
 * Function for breadcrumb title
 */
if (!function_exists('prefix_breadcrumb_title')) {
	function prefix_breadcrumb_title() {

		if (is_home() && is_front_page()) {
			$breadcrumb_title = esc_html(get_bloginfo('title'));
		} elseif (is_home() && !is_front_page()) {
			$breadcrumb_title = get_the_title(get_option('page_for_posts'));
		} elseif (is_archive()) {
			$breadcrumb_title = prefix_get_the_archive_title();
		} elseif (is_search()) {
			$breadcrumb_title = esc_html__('Search Results for: ', 'text_domain') . esc_html(get_search_query());
		} elseif (is_404()) {
			$breadcrumb_title = esc_html__('404', 'text_domain');
		} else {
			$breadcrumb_title = get_the_title();
		}

		return $breadcrumb_title;
	}
}

/**
 * Breadcrumb
 */
if (!function_exists('prefix_breadcrumb')) {
	function prefix_breadcrumb() {
		echo '<ul class="prefix-breadcrumb-link"><li>';

		if (!(is_home() && is_front_page())) {
			printf("<a class='active' href='%s'>" . esc_html__('Home', 'text_domain') . "</a><span class='breadcrumb-sperarator'>&#47;</span>", esc_url(home_url()));
		}
		$name = get_bloginfo('name');
		$desc = get_bloginfo('description');
		//is_home means blog page.
		if (is_home() && is_front_page()) { //home page and fornt page not set
			echo esc_html($desc);
		} elseif (!is_home() && is_front_page()) { //setting fornt page.
			echo get_the_title();
		} elseif (is_home() && !is_front_page()) { //setting blog page
			$id = (get_option('page_for_posts') != '0') ? get_option('page_for_posts') : '';
			echo get_the_title($id);
		} elseif (is_search()) {
			esc_html_e('Search Page', 'text_domain');
		} elseif (is_404()) {
			esc_html_e('404', 'text_domain');
		} elseif (is_category()) {
			echo single_term_title();
		} elseif (is_singular()) {
			$pt_name = get_post_type(get_the_ID());
			$obj = get_post_type_object($pt_name);
			$name = str_replace(array('_', '-'), array(' ', ' '), $pt_name);
			if (is_single()) {
				echo get_the_title();
			} elseif (is_page()) {
				echo get_the_title();
			} else {
				echo esc_html($name);
			}
		} elseif (is_archive()) {
			echo prefix_get_the_archive_title();
		}
		echo '</li></ul>';
	}
}

/**
 * Breadcrumb area on/off
 */
if (!function_exists('prefix_breadcrumb_on_off')) {
	function prefix_breadcrumb_on_off() {
		$prefix_customizer = get_theme_mod('prefix_breadcrumb_on_off', 'show');
		$page_breadcrumb_option = prefix_page_option('_prefix_breadcrumbs_on_off');
		$prefix_page = !empty($page_breadcrumb_option) ? $page_breadcrumb_option : 'default';
		$on_off = $prefix_page;
		if ($on_off == 'on') {
			$on_off = true;
		} elseif ($on_off == 'off') {
			$on_off = false;
		} else {
			$on_off = $prefix_customizer;
		}
		return $prefix_customizer;
	}
}


/**
 * Push extra link in wp nav menu
 */
add_filter('wp_nav_menu_items', 'prefix_push_menu', 10, 2);
function prefix_push_menu($items, $args) {
	$signIn = get_theme_mod('prefix_header_sign', 'show');
	$signInTxt = get_theme_mod('prefix_header_sign_txt', __('Sing in', 'text_domain'));
	$signInurl = get_theme_mod('prefix_header_sign_url', '#');
	if (($signIn == 'show' && !empty($signInTxt)) && $args->theme_location == 'text_domain-header-menu') {
		$items = $items . '<li class="extra_push_item"><a href="' . esc_url($signInurl) . '">' . esc_html($signInTxt) . '</a></li>';
	} else {
		$items = '';
	}
	return $items;
}

/**
 * get_wysiwyg_output
 */
if (!function_exists('prefix_get_wysiwyg_output')) {
	function prefix_get_wysiwyg_output($meta_key) {
		global $wp_embed;

		$content = $wp_embed->autoembed($meta_key);
		$content = $wp_embed->run_shortcode($content);
		$content = do_shortcode($content);
		$content = wpautop($content);

		return $content;
	}
}