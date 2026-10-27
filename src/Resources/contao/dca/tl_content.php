<?php
use Contao\System;
use Contao\Backend;
use Contao\DataContainer;
use Contao\StringUtil;
use Contao\Image;

use Jonnysp\Jobs\Model\JobCategoriesModel;
use Jonnysp\Jobs\Model\JobModel;


$GLOBALS['TL_DCA']['tl_content']['palettes']['jobcategorie_viewer'] = '{type_legend},type;{job_categories_legend},jobcategorie;{protected_legend:hide},protected;{expert_legend:hide},cssID,space;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['fields']['jobcategorie'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['jobcategorie'],
	'inputType'               => 'select',
	'options_callback'        => array('tl_content_jobs', 'getJobCategorie'),
	'eval'                    => array('mandatory'=>true, 'chosen'=>true, 'submitOnChange'=>true),
	'wizard' 				  => array(array('tl_content_jobs', 'editJobCategorie')),
	'sql'                     => "int(10) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_content']['palettes']['job_viewer'] = '{type_legend},type;{job_legend},jobs;{protected_legend:hide},protected;{expert_legend:hide},cssID,space;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['fields']['jobs'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['jobs'],
	'inputType'               => 'select',
	'options_callback'        => array('tl_content_jobs', 'getJob'),
	'eval'                    => array('mandatory'=>true, 'chosen'=>true, 'submitOnChange'=>true),
	'wizard' 				  => array(array('tl_content_jobs', 'editJob')),
	'sql'                     => "int(10) unsigned NOT NULL default '0'"
);


class tl_content_jobs extends Backend 
{
	//JobCategory Viewer
	public function getJobCategorie()
	{
		$objCats =  JobCategoriesModel::findAll();
		$arrCats = array();
		foreach ($objCats as $objCat)
		{
			$arrCats[$objCat->id] = '[ID ' . $objCat->id . '] - '. $objCat->title;
		}
		return $arrCats;
	}

	public function editJobCategorie(DataContainer $dc): string
	{

		//$this->loadLanguageFile('tl_jobs_categories');
		if ($dc->value < 1)
        {
            return '';
        }
		$title = sprintf($GLOBALS['TL_LANG']['tl_jobs_categories']['edit'][1], $dc->value);
		$href = System::getContainer()->get('router')->generate('contao_backend', array('do'=>'jobcategorie', 'table'=>'tl_jobs_categories','act'=>'edit', 'id'=>$dc->value , 'popup'=>'1', 'nb'=>'1'));
		return ' <a href="' . StringUtil::specialcharsUrl($href) . '" onclick="Backend.openModalIframe({\'title\':\'' . StringUtil::specialchars(str_replace("'", "\\'", $title)) . '\',\'url\':this.href});return false">' . Image::getHtml('edit.svg', $title) . '</a>';
	}


	//Job Viewer
	public function getJob()
	{
		$objJobs = JobModel::findAll();
		$arrJobs = array();
		foreach ($objJobs as $objJob)
		{
			$arrJobs[$objJob->id] = '[ID ' . $objJob->id . '] - '. $objJob->title;
		}
		return $arrJobs;
	}

	public function editJob(DataContainer $dc)
	{
		//$this->loadLanguageFile('tl_jobs_categories');
		if ($dc->value < 1)
        {
            return '';
        }
		$title = sprintf($GLOBALS['TL_LANG']['tl_jobs']['edit'][1], $dc->value);
		$href = System::getContainer()->get('router')->generate('contao_backend', array('do'=>'jobcategorie', 'table'=>'tl_jobs','act'=>'edit', 'id'=>$dc->value , 'popup'=>'1', 'nb'=>'1'));
		return ' <a href="' . StringUtil::specialcharsUrl($href) . '" onclick="Backend.openModalIframe({\'title\':\'' . StringUtil::specialchars(str_replace("'", "\\'", $title)) . '\',\'url\':this.href});return false">' . Image::getHtml('edit.svg', $title) . '</a>';
	}
}


