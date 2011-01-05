<?php

	define('WIDTH',  0);
	define('HEIGHT', 1);

	function create_image_of_type($filename, $type)
	{
		switch ($type)
		{
		case 'jpeg':
			return imagecreatefromjpeg($filename);
			break;

		case 'gif':
			return imagecreatefromgif($filename);
			break;

		case 'png':
			return imagecreatefrompng($filename);
			break;
		}
	}

	function get_image_data($image, $type)
	{
		switch ($type)
		{
		case 'jpeg':
			return imagejpeg($image);
			break;

		case 'gif':
			return imagegif($image);
			break;

		case 'png':
			return imagepng($image);
			break;
		}
	}

	function get_overlay_size($i)
	{
			// 50% of original size
		$image_resize_amount = 0.50;

		return round($i * $image_resize_amount);
	}

	function get_overlay_position($i)
	{
		return round($i / 4);
	}

	function process_image($filename, $type)
	{
		$final_filename = dirname($filename) . '.processed.' . strrchr($filename, '.');
		$rotation_degrees = 180.0;

			// Get the size of our image and create a jpeg from it
		$image_size = getimagesize($filename);
		$image = create_image_of_type($filename, $type);

		$overlay_width = get_overlay_size($image_size[WIDTH]);
		$overlay_height = get_overlay_size($image_size[HEIGHT]);

		$overlay_image = imagecreate($overlay_width, $overlay_height);

			// Get a copy of our image resampled to our new size
		imagecopyresampled(
			$overlay_image, $image,

			0, 0,
			0, 0,

			$overlay_width, $overlay_height,
			$image_size[WIDTH], $image_size[HEIGHT]
		);

			// Rotate our image
		$overlay_image = imagerotate(
			$overlay_image, $rotation_degrees, 0
		);

		imagecopy(
			$image, $overlay_image,

			get_overlay_position($image_size[WIDTH]),
			get_overlay_position($image_size[HEIGHT]),

			0, 0,

			get_overlay_size($image_size[WIDTH]),
			get_overlay_size($image_size[HEIGHT])
		);

		header('Content-Type: ' . $image_size['mime']);
		get_image_data($image, $type);
	}

