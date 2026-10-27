<?php

namespace Jonnysp\Jobs\ContentElement;

use Contao\ContentElement;
use Contao\FilesModel;
use Contao\PageModel;
use Contao\StringUtil;
use Contao\BackendTemplate;
use Contao\Config;
use Contao\System;

use Jonnysp\Jobs\Model\JobModel;
use Jonnysp\Jobs\Model\JobCategoriesModel;

class JobCategorieViewer extends ContentElement
{
	protected $strTemplate = 'ce_jobcategorieviewer';

	public function generate()
	{
		$request = System::getContainer()->get('request_stack')->getCurrentRequest();
		if ($request && System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest($request))
		{
			$objCat = JobCategoriesModel::findByPK($this->jobcategorie);
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### ' . $GLOBALS['TL_LANG']['tl_content']['job_categories_legend'] . ' ###';
			$objTemplate->title = '['. $objCat->id.'] - '. $objCat->title;
			return $objTemplate->parse();	
		}
		return parent::generate();
	}//end generate


	protected function compile()
	{

//		global $objPage;
//		$this->loadLanguageFile('tl_jobs');
//		$this->loadLanguageFile('tl_jobs_categories');

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
					"meta" => isset($CategorieImage) ? StringUtil::deserialize($CategorieImage->meta)  : null,
					"path" => isset($CategorieImage->path) ? $CategorieImage->path  : null,
					"name" => isset($CategorieImage->name) ? $CategorieImage->name  : null,
					"extension" => isset($CategorieImage->extension) ? $CategorieImage->extension  : null
			),
			"jumpTo" => isset($jumptoPage) ? $jumptoPage->getFrontendUrl()  : null
		);



		//get Job data
		if(is_null($filterJob) == false){

			if (count($filterJob) > 0){
				foreach ($filterJob as $key => $value) {

					//gen joblink
					if ($jumptoPage instanceof PageModel) {
						$joblink = $jumptoPage->getFrontendUrl(
							'/' . ($value->alias ?: $value->id)
						);
					}

					//main Image
					$JobImage = null;
					if (!empty($value->image)) {
						$JobImage = FilesModel::findByPk($value->image);
					}

					$JobDownload = null;
					if (!empty($value->download)) {
						$JobDownload = FilesModel::findByPk($value->download);
					}
				
					$Organization_logo = null;
					if (!empty($value->Organization_logo)) {
						$Organization_logo = FilesModel::findByPk($value->Organization_logo);
					}

					
					// generate Data_array
					$Jobs[$key] = array(

						"id" => $value->id ?? null,
						"title" => $value->title  ?? null,
						"shortdescription" => $value->shortdescription ?? null,
						"description" => $value->description ?? null,
						"published" => $value->published ?? null,
						"directApply" => $value->directApply ?? null,
						"image" =>  array(
								"path" => $JobImage->path ?? null,
								"name" => $JobImage->name ?? null,
								"extension" => $JobImage->extension ?? null,
								),

						"download"=>  array(
							"path" => $JobDownload->path ?? null,
							"name" => $JobDownload->name ?? null,
							"extension" => $JobDownload->extension ?? null,
							),
						"datePosted" => $value->datePosted,
						"validThrough" => $value->validThrough,
						"jobLocationType" =>$value->jobLocationType,
						"employmentType" => $value->employmentType,
						"Organization_name" =>  $value->Organization_name,
						"Organization_sameAs" =>  $value->Organization_sameAs,					
						"Organization_logo" =>  array(
							"path" => $Organization_logo->path ?? null,
							"name" => $Organization_logo->name ?? null,
							"extension" => $Organization_logo->extension ?? null,
							),
						"street" => $value->street ?? null,
						"postalCode" => $value->postalCode ?? null,
						"Locality" => $value->Locality ?? null,
						"Region" => $value->Region ?? null,
						"Country" => $value->Country ?? null,
						"href" => $joblink ?? null
						
					);
				}
			}
		}

		$this->Template->JobCategorie = $Categorie;
		$this->Template->Jobs = $Jobs;

	}//end compile

}//end class
