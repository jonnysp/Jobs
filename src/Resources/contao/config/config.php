<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2025 Jonny Spitzner
 *
 * @license LGPL-3.0+
 */

array_insert($GLOBALS['BE_MOD']['job'], 100, array
(
	'jobcategorie' 		=> array('tables' => array('tl_job_categories', 'tl_job'))
));


/**
 * Style sheet
 */
if (TL_MODE == 'BE')
{
	$GLOBALS['TL_CSS'][] = 'bundles/jonnyspjobs/job.css|static';
}


/**
 * Front end modules
 */
array_insert($GLOBALS['TL_CTE'], 1, array
	(
		'includes' 	=> array
			(
				'jobcategorie_viewer'	=> 'JobCategorieViewer',
				'job_viewer'			=> 'JobViewer'
			)
	)
);


