<?php
/**
 * Polyfill functions for thumbnail operations.
 *
 * @package         arraypress/wp-thumbnail-polyfill
 * @copyright       Copyright (c) 2024, ArrayPress Limited
 * @license         GPL2+
 * @version         0.1.0
 * @author          David Sherlock
 */

if ( ! function_exists( 'get_post_thumbnail_html' ) ) {
	/**
	 * Generate an image thumbnail based on a post ID.
	 *
	 * @param int   $post_id The post ID.
	 * @param mixed $size    The image size. Default is 'thumbnail'.
	 * @param array $attr    Optional. Additional attributes for the image markup.
	 * @param bool  $wrap    Optional. Whether to wrap the image in a div.
	 *
	 * @return string The HTML for the image thumbnail.
	 */
	function get_post_thumbnail_html( int $post_id, $size = 'thumbnail', array $attr = [], bool $wrap = true ): string {
		$thumbnail_id = get_post_thumbnail_id( $post_id );

		return get_attachment_thumbnail( $thumbnail_id, $size, $attr, $wrap );
	}
}

if ( ! function_exists( 'get_user_thumbnail' ) ) {
	/**
	 * Generate an image thumbnail for a user based on a meta key that stores attachment ID.
	 *
	 * @param int    $user_id  The user ID.
	 * @param string $meta_key The meta key that stores the image attachment ID.
	 * @param string $size     Optional. Image size. Default 'thumbnail'.
	 * @param array  $attr     Optional. Attributes for the image markup.
	 * @param bool   $wrap     Optional. Whether to wrap the image in a div.
	 *
	 * @return string The HTML for the user image or em dash if no image.
	 */
	function get_user_thumbnail( int $user_id, string $meta_key, string $size = 'thumbnail', array $attr = [], bool $wrap = false ): string {
		$image_id = get_user_meta( $user_id, $meta_key, true );

		if ( empty( $image_id ) ) {
			return '&mdash;';
		}

		return get_attachment_thumbnail( (int) $image_id, $size, $attr, $wrap );
	}
}

if ( ! function_exists( 'get_term_thumbnail' ) ) {
	/**
	 * Generate an image thumbnail for a term based on a meta key that stores attachment ID.
	 *
	 * @param int    $term_id  The term ID.
	 * @param string $meta_key The meta key that stores the image attachment ID.
	 * @param string $size     Optional. Image size. Default 'thumbnail'.
	 * @param array  $attr     Optional. Attributes for the image markup.
	 * @param bool   $wrap     Optional. Whether to wrap the image in a div.
	 *
	 * @return string The HTML for the term image or em dash if no image.
	 */
	function get_term_thumbnail( int $term_id, string $meta_key, string $size = 'thumbnail', array $attr = [], bool $wrap = false ): string {
		$image_id = get_term_meta( $term_id, $meta_key, true );

		if ( empty( $image_id ) ) {
			return '&mdash;';
		}

		return get_attachment_thumbnail( (int) $image_id, $size, $attr, $wrap );
	}
}

if ( ! function_exists( 'get_user_avatar_thumbnail' ) ) {
	/**
	 * Generate an avatar thumbnail for a user using WordPress get_avatar.
	 *
	 * @param int    $user_id The user ID.
	 * @param int    $size    Size of the avatar in pixels.
	 * @param array  $attr    Optional. Additional attributes for the image markup.
	 * @param string $default Optional. URL of the default avatar if no Gravatar exists.
	 * @param bool   $wrap    Optional. Whether to wrap the avatar in a div.
	 *
	 * @return string The HTML for the user avatar.
	 */
	function get_user_avatar_thumbnail( int $user_id, int $size = 32, array $attr = [], string $default = '', bool $wrap = false ): string {
		$default_attr = [
			'class' => 'avatar-thumbnail',
			'style' => sprintf( 'width: %1$dpx; height: %1$dpx; object-fit: cover; border-radius: 50%%;', $size )
		];

		$attr = wp_parse_args( $attr, $default_attr );
		$args = array_merge( [ 'size' => $size, 'default' => $default ], $attr );

		$user = get_user_by( 'id', $user_id );
		if ( ! $user ) {
			return '&mdash;';
		}

		$avatar = get_avatar( $user->ID, $size, $default, $user->display_name, $args );

		if ( ! $avatar ) {
			return '&mdash;';
		}

		return $wrap ? sprintf( '<div class="avatar-wrap">%s</div>', $avatar ) : $avatar;
	}
}

if ( ! function_exists( 'get_author_thumbnail' ) ) {
	/**
	 * Generate an author thumbnail for a post.
	 *
	 * @param int   $post_id The post ID.
	 * @param int   $size    Size of the avatar in pixels.
	 * @param array $attr    Optional. Additional attributes for the image markup.
	 * @param bool  $wrap    Optional. Whether to wrap the avatar in a div.
	 *
	 * @return string The HTML for the author avatar.
	 */
	function get_author_thumbnail( int $post_id, int $size = 32, array $attr = [], bool $wrap = false ): string {
		$post = get_post( $post_id );
		if ( ! $post ) {
			return '&mdash;';
		}

		return get_user_avatar_thumbnail( $post->post_author, $size, $attr, '', $wrap );
	}
}

if ( ! function_exists( 'get_thumbnail_metadata' ) ) {
	/**
	 * Get metadata for a thumbnail image.
	 *
	 * @param int    $attachment_id The attachment ID.
	 * @param string $size          Optional. Image size. Default 'thumbnail'.
	 *
	 * @return array|false Metadata array or false if not found.
	 */
	function get_thumbnail_metadata( int $attachment_id, string $size = 'thumbnail' ) {
		$metadata = wp_get_attachment_metadata( $attachment_id );

		if ( ! $metadata ) {
			return false;
		}

		// If requesting a specific size
		if ( $size !== 'full' && isset( $metadata['sizes'][ $size ] ) ) {
			return $metadata['sizes'][ $size ];
		}

		// Return full size metadata
		return [
			'width'     => $metadata['width'] ?? 0,
			'height'    => $metadata['height'] ?? 0,
			'file'      => $metadata['file'] ?? '',
			'mime-type' => get_post_mime_type( $attachment_id ),
			'filesize'  => wp_filesize( get_attached_file( $attachment_id ) )
		];
	}
}

if ( ! function_exists( 'has_valid_thumbnail' ) ) {
	/**
	 * Check if an attachment ID represents a valid image thumbnail.
	 *
	 * @param int    $attachment_id The attachment ID to check.
	 * @param string $size          Optional. Image size to check. Default 'thumbnail'.
	 *
	 * @return bool Whether the attachment is a valid thumbnail.
	 */
	function has_valid_thumbnail( int $attachment_id, string $size = 'thumbnail' ): bool {
		if ( ! wp_attachment_is_image( $attachment_id ) ) {
			return false;
		}

		$metadata = get_thumbnail_metadata( $attachment_id, $size );
		if ( ! $metadata ) {
			return false;
		}

		// Check if the thumbnail exists on disk
		$upload_dir = wp_upload_dir();
		$file_path  = $upload_dir['basedir'] . '/' . $metadata['file'];

		return file_exists( $file_path );
	}
}

if ( ! function_exists( 'get_thumbnail_srcset' ) ) {
	/**
	 * Get the srcset attribute for a thumbnail.
	 *
	 * @param int    $attachment_id The attachment ID.
	 * @param string $size          Optional. Image size. Default 'thumbnail'.
	 *
	 * @return string The srcset attribute or empty string if not available.
	 */
	function get_thumbnail_srcset( int $attachment_id, string $size = 'thumbnail' ): string {
		$image_meta = wp_get_attachment_metadata( $attachment_id );

		if ( empty( $image_meta ) ) {
			return '';
		}

		$size_array = isset( $image_meta['sizes'][ $size ] )
			? [ $image_meta['sizes'][ $size ]['width'], $image_meta['sizes'][ $size ]['height'] ]
			: [ $image_meta['width'], $image_meta['height'] ];

		$srcset = wp_calculate_image_srcset( $size_array, get_attached_file( $attachment_id ), $image_meta );

		return $srcset ? sprintf( 'srcset="%s"', esc_attr( $srcset ) ) : '';
	}
}

if ( ! function_exists( 'get_thumbnail_dimensions' ) ) {
	/**
	 * Get the dimensions of a thumbnail.
	 *
	 * @param int    $attachment_id The attachment ID.
	 * @param string $size          Optional. Image size. Default 'thumbnail'.
	 *
	 * @return array Array with width and height, or empty array if not found.
	 */
	function get_thumbnail_dimensions( int $attachment_id, string $size = 'thumbnail' ): array {
		$metadata = get_thumbnail_metadata( $attachment_id, $size );

		if ( ! $metadata ) {
			return [];
		}

		return [
			'width'  => $metadata['width'] ?? 0,
			'height' => $metadata['height'] ?? 0
		];
	}
}