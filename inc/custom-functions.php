<?php
/**
 *  Contains custom functions used for the theme
 *
 *  @package Blank Theme
 */

if ( ! function_exists( 'blank_theme_primary_classes' ) ) {

	/**
	 * Primary foundation classes.
	 *
	 * @param bool $more_classes                Provide extra classes.
	 * @param bool $override_foundation_classes Override foundation classes.
	 */
	function blank_theme_primary_classes( $more_classes = false, $override_foundation_classes = false ) {
		$sidebar_postion = get_theme_mod( 'blank_theme_sidebar_position' );

		$foundation_classes = $override_foundation_classes ? $override_foundation_classes : 'large-8 medium-8 small-12 cell column';

		if ( 'left' === $sidebar_postion ) {
			$foundation_classes .= ' large-order-2 medium-order-2 small-order-1 ';
		} elseif ( 'right' === $sidebar_postion ) {
			$foundation_classes .= ' ';
		} elseif ( 'no_sidebar' === $sidebar_postion ) {
			$foundation_classes = ' large-12 medium-12 small-12 cell column ';
		}

		echo esc_html( apply_filters( 'blank_theme_primary_classes', "blank-theme-primary {$foundation_classes} {$more_classes} clearfix", $more_classes, $foundation_classes ) );
	}
}

if ( ! function_exists( 'blank_theme_secondary_classes' ) ) {

	/**
	 * Adds Foundation classes to #primary section of all templates.
	 *
	 * @param bool $more_classes                Provide more classes.
	 * @param bool $override_foundation_classes Override foundation classes.
	 */
	function blank_theme_secondary_classes( $more_classes = false, $override_foundation_classes = false ) {
		// Override will be useful in page-templates.
		$sidebar_postion     = get_theme_mod( 'blank_theme_sidebar_position' );
		$foundation_classes  = $override_foundation_classes ? $override_foundation_classes : 'large-4 medium-4 small-12 cell column';
		$foundation_classes .= 'left' === $sidebar_postion ? ' large-order-1 medium-order-1 small-order-2' : false;

		echo esc_html( apply_filters( 'blank_theme_secondary_classes', "blank-theme-secondary widget-area {$foundation_classes} {$more_classes} clearfix", $more_classes, $foundation_classes ) );
	}
}


if ( ! function_exists( 'blank_theme_main_font_url' ) ) {

	/**
	 * Returns the main font url of the theme, we are returning it from a function to handle two things
	 * one is to handle the http problems and the other is so that we can also load it to post editor.
	 *
	 * @return string font url
	 */
	function blank_theme_main_font_url() {

		/**
		 * Use font url without http://, we do this because google font without https have
		 * problem loading on websites with https.
		 *
		 * @var font_url
		 */
		$font_url = 'fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700';

		return ( 'https://' === substr( site_url(), 0, 8 ) ) ? 'https://' . $font_url : 'http://' . $font_url;
	}
}

if ( ! function_exists( 'blank_theme_pagination' ) ) {

	/**
	 * Blank Theme Pagination.
	 */
	function blank_theme_pagination() {
		echo '<nav class="blank-theme-pagination clearfix">';
		$pagination_args = array(
			'span' => array(
				'class' => array(),
			),
			'a'    => array(
				'class' => array(),
				'href'  => array(),
			),
		);
		echo wp_kses( paginate_links(), $pagination_args );
		echo '</nav>';
	}
}

if ( ! function_exists( 'blank_theme_isset' ) ) {

	/**
	 * Created out of frustration to check isset each time getting value from array.
	 *
	 * @param array $array Array object.
	 * @param array $key1  Parameter.
	 * @param array $key2  Parameter.
	 * @param array $key3  Parameter.
	 * @param array $key4  Parameter.
	 *
	 * @return bool Check array isset.
	 */
	function blank_theme_isset( $array, $key1, $key2 = false, $key3 = false, $key4 = false ) {
		if ( $key4 ) {
			return isset( $array[ $key1 ][ $key2 ][ $key3 ][ $key4 ] ) ? $array[ $key1 ][ $key2 ][ $key3 ][ $key4 ] : false;
		}

		if ( $key3 ) {
			return isset( $array[ $key1 ][ $key2 ][ $key3 ] ) ? $array[ $key1 ][ $key2 ][ $key3 ] : false;
		}

		if ( $key2 ) {
			return isset( $array[ $key1 ][ $key2 ] ) ? $array[ $key1 ][ $key2 ] : false;
		}

		if ( $key1 ) {
			return isset( $array[ $key1 ] ) ? $array[ $key1 ] : false;
		}
	}
}


if ( ! function_exists( 'blank_theme_get_template_part' ) ) {

	/**
	 * Its an extension to WordPress get_template_part function.
	 * It can be used when you need to call a template and all pass variables to it. and they will be available in your template part.
	 *
	 * @param  string $slug file slug like you use in get_template_part.
	 * @param  array  $params pass an array of variables you want to use in array keys.
	 */
	function blank_theme_get_template_part( $slug, $params = array() ) {
		if ( ! empty( $params ) ) {
			foreach ( $params as $k => $param ) {
				set_query_var( $k, $param );
			}
		}
		get_template_part( $slug );
	}
}

if ( ! function_exists( 'db' ) ) {

	/**
	 * Function for Debugging
	 *
	 * @param mixed  $val    Variable to print.
	 * @param null   $exit   Add exit after pre.
	 * @param string $method Use `pre` or `var_dump`.
	 */
	function db( $val, $exit = null, $method = 'pre' ) {
		if ( isset( $_REQUEST['db'] ) && ! empty( $_REQUEST['db'] ) ) {

			if ( 'pre' === $method ) {
				echo '<pre>';
				print_r( $val );
				echo '</pre>';
			} elseif ( $method ) {
				var_dump( $val );
			}

			if ( $exit ) {
				exit;
			}
		}
	}
}

/**
 * To get server variable.
 *
 * @param mixed  $server_key  string Server key.
 * @param string $filter_type string Filter type.
 *
 * @return mixed
 */
function blank_theme_get_server_var( $server_key, $filter_type = 'FILTER_SANITIZE_STRING' ) {

	$server_val = '';

	if ( function_exists( 'filter_input' ) && filter_has_var( INPUT_SERVER, $server_key ) ) {
		$server_val = filter_input( INPUT_SERVER, $server_key, constant( $filter_type ) );
	} elseif ( isset( $_SERVER[ $server_key ] ) ) {
		$server_val = sanitize_text_field( wp_unslash( $_SERVER[ $server_key ] ) );
	}

	return $server_val;
}
