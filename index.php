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
			$matched_filenames = glob('*.' . $format);

				// Loop through each file and process it
			if (count($matched_filenames) > 0)
				foreach ($matched_filenames as $filename)
					process_image($filename, $type);
		}
	}

