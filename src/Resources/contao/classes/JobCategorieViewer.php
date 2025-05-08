<?php

class JobCategorieViewer extends ContentElement
{
	protected $strTemplate = 'ce_jobcategorieviewer';

	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objCat = \JobCategoriesModel::findByPK($this->jobcategorie);
			$objTemplate = new \BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['tl_content']['job_categories_legend']) . ' ###';
			$objTemplate->title = '['. $objCat->id.'] - '. $objCat->title;
			return $objTemplate->parse();	
		}
		return parent::generate();
	}//end generate

	protected function compile()
	{
		global $objPage;
		$this->loadLanguageFile('tl_job');
		$this->loadLanguageFile('tl_job_categories');

		//gets the categorie
		$objCategorie = \JobCategoriesModel::findByPK($this->jobcategorie);
		
		$Jobs = array();

		$filterJob = \JobModel::findAll(
			array('column' => array('pid=?','published=?'),
				   'value' => array($this->jobcategorie , 1),
				   'order' => 'sorting'
			)
		);

		//print_r($filterJob);

		//get Categorie data
		$CategorieImage = \FilesModel::findByPk($objCategorie->image);

		$Categorie = array(
			"id" => $objCategorie->id,
			"title" => $objCategorie->title,
			"description" => $objCategorie->description,
			"image" => array(
					//"meta" => $this->getMetaData($CategorieImage->meta, $objPage->language),
					"path" => $CategorieImage->path,
					"name" => $CategorieImage->name,
					"extension" => $CategorieImage->extension
			),
			"jumpTo" => $objCategorie->jumpTo
		);

		//get Job data
		if(is_null($filterJob) == false){

			if (count($filterJob) > 0){
				foreach ($filterJob as $key => $value) {

					//main Image
					$JobImage = \FilesModel::findByPk($value->image);
					$JobDownload = \FilesModel::findbyPk($value->download);
					$Organization_logo = \FilesModel::findbyPk($value->Organization_logo);

					// generate Data_array
					$Jobs[$key] = array(
						"id" => $value->id,
						"title" => $value->title,
						"shortdescription" => $value->shortdescription,
						"description" => $value->description,
						"published" => $value->published,
						"directApply" => $value->directApply,
						"image" =>  array(
								"path" => $JobImage->path,
								"name" => $JobImage->name,
								"extension" => $JobImage->extension
								),
						"download"=>  array(
							"path" => $JobDownload->path,
							"name" => $JobDownload->name,
							"extension" => $JobDownload->extension
							),
						"datePosted" => $value->datePosted,
						"validThrough" => $value->validThrough,
						"jobLocationType" =>$value->jobLocationType,
						"employmentType" => $value->employmentType,
						"Organization_name" =>  $value->Organization_name,
						"Organization_sameAs" =>  $value->Organization_sameAs,					
						"Organization_logo" =>  array(
							"path" => $Organization_logo->path,
							"name" => $Organization_logo->name,
							"extension" => $Organization_logo->extension
							),
						"street" =>  $value->street,
						"postalCode" =>  $value->postalCode,
						"Locality" =>  $value->Locality,	
						"Region" =>  $value->Region,	
						"Country" =>  $value->Country,	
						
					);
				}
			}
		}



		$this->Template->JobCategorie = $Categorie;
		$this->Template->Jobs = $Jobs;

	}//end compile

}//end class
