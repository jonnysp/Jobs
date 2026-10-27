<?php
use Contao\System;
use Contao\Backend;
use Contao\DataContainer;
use Contao\StringUtil;
use Contao\Image;


use Jonnysp\Jobs\Model\JobCategoriesModel;


$GLOBALS['TL_DCA']['tl_module']['palettes']['jobreader']   = '{title_legend},name,type,jobcategorie;';
$GLOBALS['TL_DCA']['tl_module']['fields']['jobcategorie'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['jobcategorie'],
	'inputType'               => 'select',
	'options_callback'        => array('tl_module_job', 'getJobCategorie'),
	'eval'                    => array('tl_class'=>'w100 clr', 'mandatory'=>true, 'chosen'=>true, 'submitOnChange'=>true),
	'wizard' 				  => array(array('tl_module_job', 'editJobCategorie')),
	'sql'                     => "int(10) unsigned NOT NULL default '0'"
);

class tl_module_job extends Backend 
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

		$this->loadLanguageFile('tl_jobs_categories');
		if ($dc->value < 1)
        {
            return '';
        }
		$title = sprintf($GLOBALS['TL_LANG']['tl_jobs_categories']['edit'][1], $dc->value);
		$href = System::getContainer()->get('router')->generate('contao_backend', array('do'=>'jobcategorie', 'table'=>'tl_jobs_categories','act'=>'edit', 'id'=>$dc->value , 'popup'=>'1', 'nb'=>'1'));
		return ' <a href="' . StringUtil::specialcharsUrl($href) . '" onclick="Backend.openModalIframe({\'title\':\'' . StringUtil::specialchars(str_replace("'", "\\'", $title)) . '\',\'url\':this.href});return false">' . Image::getHtml('edit.svg', $title) . '</a>';
	}
	
}