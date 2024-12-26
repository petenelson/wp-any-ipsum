<?php
/**
 * Sanitize helper function to retrive values from $_GET, $_POST, etc.
 */

namespace WPAnyIpsum\Sanitizers;

/**
 * Gets a sanitized text field from an array. Defaults to sanitize_text_field().
 *
 * @param string $field     The field name.
 * @param array  $array     The request array ($_POST, $_GET, etc).
 * @param mixed  $sanitizer The filter constant or array with callback options.
 * @return string
 */
function sanitized_array_field( $field, $array, $sanitizer = false ) {

	if ( false === $sanitizer ) {
		$sanitizer = filter_sanitize_text_field();
	}

	$request = filter_var_array( $array, [ $field  => $sanitizer ] );
	return $request[ $field ];
}

/**
 * Callback filter for filter_var_array() to sanitize a text field.
 *
 * @return array
 */
function filter_sanitize_text_field() {
	return [
		'filter'  => FILTER_CALLBACK,
		'options' => '\sanitize_text_field',
	];
}

/**
 * Gets a sanitized text field from the $_POST variable.
 *
 * @param string $field The POST field name.
 * @return string
 */
function sanitized_post_field( $field ) {
	return sanitized_array_field( $field, $_POST ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
}

/**
 * Gets a sanitized text field from the $_GET variable.
 *
 * @param string $field The GET field name.
 * @return string
 */
function sanitized_get_field( $field ) {
	return sanitized_array_field( $field, $_GET ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
}

/**
 * Gets the REQUEST_URI from the $_SERVER variable.
 *
 * @return string
 */
function get_request_uri() {
	return sanitized_array_field( 'REQUEST_URI', $_SERVER );
}

/**
 * Gets a sanitized text array from the $_POST variable.
 *
 * @param string $field The POST field name.
 * @return string
 */
function sanitized_post_array( $field ) {
	$filter_string = filter_sanitize_text_field();
	$filter_string['flags'] = FILTER_REQUIRE_ARRAY;

	$array = sanitized_array_field( $field, $_POST, $filter_string ); // phpcs:ignore WordPress.Security.NonceVerification.Missing
	return is_array( $array ) ? $array : [];
}