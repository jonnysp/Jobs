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
		
		$Job = array();

		$filterJob = \JobModel::findAll(
			array('column' => array('pid=?','published=?'),
				   'value' => array($this->jobcategorie , 1),
				   'order' => 'sorting'
			)
		);

		//get Categorie data
		$CategorieImage = \FilesModel::findByPk($objCategorie->image);

		$Categorie = array(
			"id" => $objCategorie->id,
			"title" => $objCategorie->title,
			"description" => $objCategorie->description,
			"image" => array(
					"meta" => $this->getMetaData($CategorieImage->meta, $objPage->language),
					"path" => $CategorieImage->path,
					"name" => $CategorieImage->name,
					"extension" => $CategorieImage->extension
				)
		);

		//get Job data
		if(is_null($filterJob) == false){

			if (count($filterJob) > 0){
				foreach ($filterJob as $key => $value) {

					//main Image
					$AnimalImage = \FilesModel::findByPk($value->image);
					
					//additional sorted Images
					$AnimalImages = array();
					$AnimalUnsortedImages = \FilesModel::findMultipleByUuids(StringUtil::deserialize($value->images));
					$AnimalImagesSort = StringUtil::deserialize($value->imagessort);

			 		if ($AnimalImagesSort){
			 			foreach ($AnimalImagesSort as $sortkey => $uuid) {
							if ($AnimalUnsortedImages){
								foreach ($AnimalUnsortedImages as $Image) {
									if ($Image->uuid == $uuid) {
										array_push($AnimalImages, array
											(
												"meta" => $this->getMetaData($Image->meta, $objPage->language),
												"path" => $Image->path,
												"name" => $Image->name,
												"extension" => $Image->extension
											)
										);
									}
								}
							}
			 			}
					}

					// generate Data_array
					$Job[$key] = array(
						"id" => $value->id,
						"title" => $value->title,
						"description" => $value->description,
						"published" => $value->published,
						"tags" => StringUtil::deserialize($value->tags),
						"categories" => StringUtil::deserialize($value->categories),
						"image" =>  array(
								"meta" => $this->getMetaData($AnimalImage->meta, $objPage->language),
								"path" => $AnimalImage->path,
								"name" => $AnimalImage->name,
								"extension" => $AnimalImage->extension
								),
						"images" => $AnimalImages
					);
				}
			}
		}


		$this->Template->JobCategorie = $Categorie;
		$this->Template->Job = $Job;

	}//end compile

}//end class
