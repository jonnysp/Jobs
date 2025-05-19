<?php

use Jonnysp\JobCategoriesModel;


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

	public function editJobCategorie(DataContainer $dc)
	{
		$this->loadLanguageFile('tl_job_categories');
		return ($dc->value < 1) ? '' : ' <a href="contao/main.php?do=jobcategorie&amp;act=edit&amp;id=' . $dc->value . '&amp;popup=1&amp;nb=1&amp;rt=' . REQUEST_TOKEN . '" title="' . sprintf(StringUtil::specialchars($GLOBALS['TL_LANG']['tl_job_categories']['editheader'][1]), $dc->value) . '" onclick="Backend.openModalIframe({\'title\':\'' . StringUtil::specialchars(str_replace("'", "\\'", sprintf($GLOBALS['TL_LANG']['tl_job_categories']['editheader'][1], $dc->value))) . '\',\'url\':this.href});return false">' . Image::getHtml('alias.svg', $GLOBALS['TL_LANG']['tl_job_categories']['editheader'][0]) . '</a>';
	}


}