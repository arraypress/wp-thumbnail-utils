# WordPress Thumbnail Utilities

A comprehensive PHP library providing utility functions for handling WordPress thumbnails, images, and avatars. This library offers a collection of helper functions for working with post thumbnails, term images, user images, and attachments.

## Features

- 🖼️ Post thumbnail generation and handling
- 👤 User image and avatar support
- 🏷️ Term image management
- 📎 Attachment thumbnail utilities
- 🎨 Flexible image attribute handling
- 🛠️ Simple utility functions
- ✨ Consistent interface across functions
- 🔄 Fallback handling for missing images

## Requirements

- PHP 7.4 or higher
- WordPress 5.0 or higher

## Installation

You can install the package via composer:

```bash
composer require arraypress/wp-thumbnail-utils
```

## Basic Usage

Here are some examples of how to use the thumbnail utilities:

```php
// Get a post thumbnail
$thumbnail = get_post_thumbnail_html( $post_id, 'thumbnail', [
	'class' => 'custom-thumbnail',
	'style' => 'border-radius: 8px;'
] );

// Get a term image (stored as term meta)
$term_image = get_term_thumbnail( $term_id, 'term_image_meta_key', 'medium' );

// Get a user image (stored as user meta)
$user_image = get_user_thumbnail( $user_id, 'user_image_meta_key', 'large' );

// Get an attachment thumbnail
$attachment = get_attachment_thumbnail( $attachment_id, 'thumbnail', [
	'class' => 'attachment-preview'
], true ); // true wraps in div
```

## Available Functions

### Post Thumbnails

```php
// Get post thumbnail
$thumbnail = get_post_thumbnail_html(
	123,               // Post ID
	'thumbnail',       // Size
	[ 'class' => 'my-thumb' ], // Attributes
	true              // Wrap in div
);
```

### Term Images

```php
// Get term image (from term meta)
$term_image = get_term_thumbnail(
	45,                // Term ID
	'term_image_key',  // Meta key storing attachment ID
	'medium',          // Size
	[ 'class' => 'term-img' ] // Attributes
);
```

### User Images

```php
// Get user custom image (from user meta)
$user_image = get_user_thumbnail(
	2,                 // User ID
	'profile_image',   // Meta key storing attachment ID
	'thumbnail',       // Size
	[ 'class' => 'profile-pic' ] // Attributes
);

// Get user avatar
$avatar = get_user_avatar_thumbnail(
	2,                 // User ID
	64,                // Size in pixels
	[ 'class' => 'avatar' ] // Attributes
);
```

### Attachment Utilities

```php
// Get attachment thumbnail
$image = get_attachment_thumbnail(
	789,               // Attachment ID
	'medium',          // Size
	[ 'class' => 'img' ] // Attributes
);

// Get attachment metadata
$metadata = get_thumbnail_metadata(
	789,               // Attachment ID
	'thumbnail'        // Size
);

// Get image dimensions
$dimensions = get_thumbnail_dimensions( 789 );
echo "Width: {$dimensions['width']}";
echo "Height: {$dimensions['height']}";

// Get srcset attribute
$srcset = get_thumbnail_srcset( 789, 'medium' );
```

## Image Validation

```php
// Check if thumbnail exists and is valid
if ( has_valid_thumbnail( $attachment_id, 'thumbnail' ) ) {
	// Thumbnail exists and is accessible
	$thumbnail = get_attachment_thumbnail( $attachment_id );
}
```

## Default Attributes

All thumbnail functions accept an optional array of HTML attributes:

```php
$attributes = [
	'class'   => 'custom-image',
	'style'   => 'border-radius: 4px;',
	'alt'     => 'Custom alt text',
	'loading' => 'lazy'
];

$thumbnail = get_attachment_thumbnail( $attachment_id, 'medium', $attributes );
```

## Error Handling

All functions return an em dash (`&mdash;`) when no image is found or when errors occur:

```php
// Will return &mdash; if image not found
$missing = get_term_thumbnail( $term_id, 'nonexistent_key' );
```

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request. For major changes, please open an issue first to discuss what you would like to change.

## License

This project is licensed under the GPL2+ License. See the LICENSE file for details.

## Support

For support, please use the [issue tracker](https://github.com/arraypress/wp-thumbnail-utils/issues).