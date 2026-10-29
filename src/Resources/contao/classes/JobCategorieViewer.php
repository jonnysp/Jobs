<?php

namespace Jonnysp;

use Contao\ContentElement;
use Contao\FilesModel;
use Contao\PageModel;
use Contao\StringUtil;
use Contao\BackendTemplate;
use Contao\Config;

class JobCategorieViewer extends ContentElement
{
	protected $strTemplate = 'ce_jobcategorieviewer';

	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objCat = JobCategoriesModel::findByPK($this->jobcategorie);
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['tl_content']['job_categories_legend']) . ' ###';
			$objTemplate->title = '['. $objCat->id.'] - '. $objCat->title;
			return $objTemplate->parse();	
		}
		return parent::generate();
	}//end generate

	protected function compile()
	{

		global $objPage;
//		$this->loadLanguageFile('tl_job');
//		$this->loadLanguageFile('tl_job_categories');

		//gets the categorie
		$objCategorie = JobCategoriesModel::findByPK($this->jobcategorie);
		
		$Jobs = array();

		$filterJob = JobModel::findAll(
			array('column' => array('pid=?','published=?'),
				   'value' => array($this->jobcategorie , 1),
				   'order' => 'sorting'
			)
		);


		//get Categorie data
		$CategorieImage = FilesModel::findByPk($objCategorie->image);
		$jumptoPage = PageModel::findWithDetails($objCategorie->jumpTo);


		$Categorie = array(
			"id" => $objCategorie->id,
			"title" => $objCategorie->title,
			"description" => $objCategorie->description,
			"image" => array(
					"meta" => isset($CategorieImage) ? $this->getMetaData($CategorieImage->meta, $objPage->language)  : '',
					"path" => isset($CategorieImage->path) ? $CategorieImage->path  : '',
					"name" => isset($CategorieImage->name) ? $CategorieImage->name  : '',
					"extension" => isset($CategorieImage->extension) ? $CategorieImage->extension  : ''
			),
			"jumpTo" => isset($jumptoPage) ? $jumptoPage->getFrontendUrl()  : ''
		);



		//get Job data
		if(is_null($filterJob) == false){

			if (count($filterJob) > 0){
				foreach ($filterJob as $key => $value) {


					//gen joblink
					if ($jumptoPage)
					{
						$link = StringUtil::ampersand($jumptoPage->getFrontendUrl(Config::get('useAutoItem') ? '/%s' : '/items/%s'));
						$joblink = sprintf(preg_replace('/%(?!s)/', '%%', $link), ($value->alias ?: $value->id));
					}

					//main Image
					$JobImage = FilesModel::findByPk($value->image);
					$JobDownload = FilesModel::findbyPk($value->download);
					$Organization_logo = FilesModel::findbyPk($value->Organization_logo);

					// generate Data_array
					$Jobs[$key] = array(

						"id" => isset($value->id) ? $value->id  : '',
						"title" => isset($value->title) ? $value->title  : '',
						"shortdescription" => isset($value->shortdescription) ? $value->shortdescription  : '',
						"description" => isset($value->description) ? $value->description  : '',
						"published" => isset($value->published) ? $value->published  : '',
						"directApply" => isset($value->directApply) ? $value->directApply  : '',
						"image" =>  array(
								"path" => isset($JobImage->path) ? $JobImage->path  : '',
								"name" => isset($JobImage->name) ? $JobImage->name  : '',
								"extension" => isset($JobImage->extension) ? $JobImage->extension  : '',
								),

						"download"=>  array(
							"path" => isset($JobDownload->path) ? $JobDownload->path  : '',
							"name" => isset($JobDownload->name) ? $JobDownload->name  : '',
							"extension" => isset($JobDownload->extension) ? $JobDownload->extension  : '',
							),
						"datePosted" => $value->datePosted,
						"validThrough" => $value->validThrough,
						"jobLocationType" =>$value->jobLocationType,
						"employmentType" => $value->employmentType,
						"Organization_name" =>  $value->Organization_name,
						"Organization_sameAs" =>  $value->Organization_sameAs,					
						"Organization_logo" =>  array(
							"path" => isset($Organization_logo->path) ? $Organization_logo->path  : '',
							"name" => isset($Organization_logo->name) ? $Organization_logo->name  : '',
							"extension" => isset($Organization_logo->extension) ? $Organization_logo->extension  : '',
							),
						"street" => isset($value->street) ? $value->street  : '',
						"postalCode" => isset($value->postalCode) ? $value->postalCode  : '',
						"Locality" => isset($value->Locality) ? $value->Locality  : '',
						"Region" => isset($value->Region) ? $value->Region  : '',
						"Country" => isset($value->Country) ? $value->Country  : '',
						"href" => isset($joblink) ? $joblink  : ''
						
					);
				}
			}
		}

		$this->Template->JobCategorie = $Categorie;
		$this->Template->Jobs = $Jobs;

	}//end compile

}//end class

class_alias(JobCategorieViewer::class, 'JobCategorieViewer');