<?php
/**
 * Polyfill functions for thumbnail operations.
 *
 * @package         arraypress/wp-thumbnail-utils
 * @copyright       Copyright (c) 2024, ArrayPress Limited
 * @license         GPL2+
 * @version         1.0.0
 * @author          David Sherlock
 */

if ( ! function_exists( 'get_attachment_thumbnail' ) ) {
	/**
	 * Generate an image thumbnail based on an attachment ID.
	 *
	 * @param int   $attachment_id The attachment ID.
	 * @param mixed $size          The image size. Default is 'thumbnail'.
	 * @param array $attr          Optional. Additional attributes for the image markup.
	 * @param bool  $wrap          Optional. Whether to wrap the image in a div.
	 *
	 * @return string The HTML for the image thumbnail.
	 */
	function get_attachment_thumbnail( int $attachment_id, $size = 'thumbnail', array $attr = [], bool $wrap = true ): string {
		$default_attr = [
			'class' => 'column-thumbnail',
			'style' => 'width: 32px; height: 32px; object-fit: cover; border-radius: 4px;'
		];

		if ( ! $attachment_id ) {
			return '&mdash;';
		}

		$attr  = wp_parse_args( $attr, $default_attr );
		$image = wp_get_attachment_image( $attachment_id, $size, false, $attr );

		if ( ! $image ) {
			return '&mdash;';
		}

		return $wrap ? sprintf( '<div class="thumbnail">%s</div>', $image ) : $image;
	}
}

if ( ! function_exists( 'get_attachment_file_size' ) ) {
	/**
	 * Get the file size of an attachment.
	 *
	 * @param int $attachment_id The attachment ID.
	 *
	 * @return string The formatted file size or em dash if not found.
	 */
	function get_attachment_file_size( int $attachment_id ): string {
		$file_path = get_attached_file( $attachment_id );

		if ( $file_path && file_exists( $file_path ) ) {
			$file_size = filesize( $file_path );
			return $file_size ? sprintf( '<span>%s</span>', esc_html( size_format( $file_size ) ) ) : '&mdash;';
		}

		return '&mdash;';
	}
}

if ( ! function_exists( 'get_attachment_file_type' ) ) {
	/**
	 * Get the file type (e.g., audio, video, etc.) of an attachment.
	 *
	 * @param int $attachment_id The attachment ID.
	 *
	 * @return string The file type or 'unknown'.
	 */
	function get_attachment_file_type( int $attachment_id ): string {
		$file_path = get_attached_file( $attachment_id );

		if ( $file_path && file_exists( $file_path ) ) {
			$file_type = wp_check_filetype( $file_path );
			return esc_html( $file_type['type'] ) ?? 'unknown';
		}

		return 'unknown';
	}
}

if ( ! function_exists( 'get_attachment_file_extension' ) ) {
	/**
	 * Get the file extension of an attachment if the file is found.
	 *
	 * @param int $attachment_id The attachment ID.
	 *
	 * @return string The file extension or em dash if not found.
	 */
	function get_attachment_file_extension( int $attachment_id ): string {
		$file_path = get_attached_file( $attachment_id );

		if ( $file_path && file_exists( $file_path ) ) {
			return esc_html( pathinfo( $file_path, PATHINFO_EXTENSION ) );
		}

		return '&mdash;';
	}
}