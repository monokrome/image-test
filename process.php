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
		$filename_extension = strrchr($filename, '.');
		$filename_base = substr(
			$filename,
			0,
			strlen($filename)-(strlen($filename_extension)-1)
		);

		$final_filename =  $filename_base . 'processed' . $filename_extension;
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

		$file = fopen($final_filename, 'wb');

		if ($file)
		{
			// TODO: Find the method to get the image data more 'properly'
			ob_start();
			get_image_data($image, $type);
			$image_contents = ob_get_contents();
			ob_end_clean();

			fwrite($file, $image_contents);
			fclose($file);
		}
		else
		{
			die('Error on file: ' . $filename);
		}
	}

