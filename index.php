<?php

		// Functions used for processing our image
	require('./process.php');

		// Add more image formats here if need be.
	$formats = require('formats.php');

		// Scan the current directory for all image formats
		// type is used to know which PHP functions to use
	foreach ($formats as $type => $format_list)
	{

		foreach ($format_list as $format)
		{
				// Make sure that this isn't an already processed file
			$processed_extension = 'processed.' . $format;
			$extension_length = strlen($processed_extension);
			$matched_filenames = glob('*.' . $format);

				// Loop through each file and process it
			if (count($matched_filenames) > 0)
			{
				foreach ($matched_filenames as $filename)
				{

					$extension_matched = substr_compare(
						 $filename,
						 $processed_extension,
						-$extension_length,
						 $extension_length
					) === 0;

					if ($extension_matched)
						break;

					process_image($filename, $type);
				}
			}

		}
	}

