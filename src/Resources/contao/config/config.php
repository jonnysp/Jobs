<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2025 Jonny Spitzner
 *
 * @license LGPL-3.0+
 */

use Jonnysp\JobModel;
use Jonnysp\JobCategoriesModel;
use Jonnysp\JobViewer;
use Jonnysp\JobCategorieViewer;
use Jonnysp\ModuleJobReader;
use Jonnysp\ModuleJob;


$GLOBALS['BE_MOD']['job']['jobcategorie' ] = array
(
		'tables' => array('tl_job_categories', 'tl_job')
);


// Front end modules
$GLOBALS['FE_MOD']['job'] = array
(
	'jobreader'    => ModuleJobReader::class
);

// Register hooks
$GLOBALS['TL_HOOKS']['getSearchablePages'][] = array(ModuleJob::class, 'getSearchablePages');

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
$GLOBALS['TL_CTE']['includes']['job_viewer'] = JobViewer::class;
$GLOBALS['TL_CTE']['includes']['jobcategorie_viewer'] = JobCategorieViewer::class;


// Models
$GLOBALS['TL_MODELS']['tl_job'] = JobModel::class;
$GLOBALS['TL_MODELS']['tl_job_category'] = JobCategoriesModel::class;


