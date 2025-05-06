<?php

$GLOBALS['TL_DCA']['tl_content']['palettes']['jobcategorie_viewer'] = '{type_legend},type;{job_categories_legend},jobcategorie;{protected_legend:hide},protected;{expert_legend:hide},cssID,space;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['fields']['jobcategorie'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_content']['jobcategorie'],
	'inputType'               => 'select',
	'options_callback'        => array('tl_content_job', 'getJobCategorie'),
	'eval'                    => array('mandatory'=>true, 'chosen'=>true, 'submitOnChange'=>true),
	'wizard' 				  => array(array('tl_content_job', 'editJobCategorie')),
	'sql'                     => "int(10) unsigned NOT NULL default '0'"
);


class tl_content_job extends Backend 
{

	public function getJobCategorie()
	{
		$objCats =  \JobCategoriesModel::findAll();
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