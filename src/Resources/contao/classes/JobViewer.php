<?php

namespace Jonnysp;

use Contao\ContentElement;
use Contao\FilesModel;
use Contao\BackendTemplate;


class JobViewer extends ContentElement
{
	protected $strTemplate = 'ce_jobviewer';

	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objJob = JobModel::findByPK($this->job);
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### ' . utf8_strtoupper($GLOBALS['TL_LANG']['tl_content']['job_legend']) . ' ###';
			$objTemplate->title = '['. $objJob->id.'] - '. $objJob->title;
			return $objTemplate->parse();	
		}
		return parent::generate();
	}//end generate

	protected function compile()
	{
		global $objPage;
//		$this->loadLanguageFile('tl_job');
	

		//get the job
		$objJob = JobModel::findByPK($this->job);

		$JobImage = FilesModel::findByPk($objJob->image);
		$JobDownload = FilesModel::findbyPk($objJob->download);
		$Organization_logo = FilesModel::findbyPk($objJob->Organization_logo);

		$Job = array(
				"id" => $objJob->id,
				"pid" => $objJob->pid,
				"title" => isset($objJob->title) ? $objJob->title : '',
				"alias" => isset($objJob->alias) ? $objJob->alias : '',
				"shortdescription" => isset($objJob->shortdescription) ? $objJob->shortdescription : '',
				"description" => isset($objJob->description) ? $objJob->description : '',
				"published" => isset($objJob->published) ? $objJob->published : '',
				"directApply" => isset($objJob->directApply) ? $objJob->directApply : '',
				"image" =>  array(
					"path" => isset($JobImage->path) ? $JobImage->path  : '',
					"name" => isset($JobImage->name) ? $JobImage->name  : '',
					"extension" => isset($JobImage->extension) ? $JobImage->extension  : ''
					),
			"download"=>  array(
				"path" => isset($JobDownload->path) ? $JobDownload->path  : '',
				"name" => isset($JobDownload->name) ? $JobDownload->panameth  : '',
				"extension" => isset($JobDownload->extension) ? $JobDownload->extension : ''
					),
				"datePosted" => isset($objJob->datePosted) ? $objJob->datePosted : '',
				"validThrough" => isset($objJob->validThrough) ? $objJob->validThrough : '',
				"jobLocationType" => isset($objJob->jobLocationType) ? $objJob->jobLocationType : '',
				"employmentType" => isset($objJob->employmentType) ? $objJob->employmentType : '',
				"Organization_name" => isset($objJob->Organization_name) ? $objJob->Organization_name : '',
				"Organization_sameAs" => isset($objJob->Organization_sameAs) ? $objJob->Organization_sameAs : '',			
				"Organization_logo" =>  array(
					"path" => isset($Organization_logo->path) ? $Organization_logo->path  : '',
					"name" => isset($Organization_logo->name) ? $Organization_logo->name  : '',
					"extension" => isset($Organization_logo->extension) ? $Organization_logo->extension  : '',
					),
			"street" => isset($objJob->street) ? $objJob->street : '',
			"postalCode" => isset($objJob->postalCode) ? $objJob->postalCode : '',
			"Locality" => isset($objJob->Locality) ? $objJob->Locality : '',
			"Region" => isset($objJob->Region) ? $objJob->Region : '',
			"Country" => isset($objJob->Country) ? $objJob->Country : ''
			

		);

		$this->Template->Job = $Job;
		$this->Template->JobSchema  = $objJob->JobSchema();


	}//end compile

}//end class


class_alias(JobViewer::class, 'JobViewer');