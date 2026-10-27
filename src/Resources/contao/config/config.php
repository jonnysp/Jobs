<?php

use Contao\Model;
use Contao\ArrayUtil;
use Contao\System;
use Symfony\Component\HttpFoundation\Request;

use Jonnysp\Jobs\Model\JobModel;
use Jonnysp\Jobs\Model\JobCategoriesModel;

use Jonnysp\Jobs\ContentElement\JobViewer;
use Jonnysp\Jobs\ContentElement\JobCategorieViewer;

use Jonnysp\Jobs\Module\ModuleJobReader;
use Jonnysp\Jobs\Module\ModuleJob;

// Models
$GLOBALS['TL_MODELS']['tl_jobs'] = JobModel::class;
$GLOBALS['TL_MODELS']['tl_jobs_categories'] = JobCategoriesModel::class;

/**
 * Content Elements
 */
$GLOBALS['TL_CTE']['includes']['job_viewer'] = JobViewer::class;
$GLOBALS['TL_CTE']['includes']['jobcategorie_viewer'] = JobCategorieViewer::class;


$GLOBALS['BE_MOD']['jobs']['jobcategorie' ] = array
(
	'tables' => array('tl_jobs_categories', 'tl_jobs')
);


// Front end modules
$GLOBALS['FE_MOD']['jobs'] = array
(
	'jobreader'    => ModuleJobReader::class
);


/**
 * Style sheet
 */
if (System::getContainer()->get('contao.routing.scope_matcher')
	->isBackendRequest(System::getContainer()->get('request_stack')->getCurrentRequest() ?? Request::create(''))
) 
{
	$GLOBALS['TL_CSS'][] = 'bundles/jonnyspjobs/jobs.css|static';
};

