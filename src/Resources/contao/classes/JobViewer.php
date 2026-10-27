<?php

namespace Jonnysp\Jobs\ContentElement;

use Contao\ContentElement;
use Contao\FilesModel;
use Contao\BackendTemplate;
use Contao\System;

use Jonnysp\Jobs\Model\JobModel;
use Jonnysp\Jobs\Model\JobCategoriesModel;

class JobViewer extends ContentElement
{
	protected $strTemplate = 'ce_jobviewer';

	public function generate()
	{

		$request = System::getContainer()->get('request_stack')->getCurrentRequest();
		if ($request && System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest($request))
		{
			$objJob = JobModel::findByPK($this->jobs);
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### ' . $GLOBALS['TL_LANG']['tl_content']['job_legend'] . ' ###';
			$objTemplate->title = '['. $objJob->id.'] - '. $objJob->title;
			return $objTemplate->parse();	
		}

		return parent::generate();
	}//end generate


	protected function compile()
	{
		//global $objPage;

		//get the job
		$objJob = JobModel::findByPK($this->jobs);

		//dump($objJob);



		$JobImage = null;
		if (!empty($objJob->image)) {
			$JobImage = FilesModel::findByPk($objJob->image);
		}
		

		$JobDownload = null;
		if (!empty($objJob->download)) {
			$JobDownload = FilesModel::findByPk($objJob->download);
		}
	
		$Organization_logo = null;
		if (!empty($objJob->Organization_logo)) {
			$Organization_logo = FilesModel::findByPk($objJob->Organization_logo);
		}


		$Job = array(
				"id" => $objJob->id,
				"pid" => $objJob->pid,
				"title" => $objJob->title ?? null,
				"alias" => $objJob->alias ?? null,
				"shortdescription" => $objJob->shortdescription ?? null,
				"description" => $objJob->description ?? null,
				"published" => $objJob->published ?? null,
				"directApply" => $objJob->directApply ?? null,
				"image" =>  array(
					"path" => $JobImage->path  ?? null,
					"name" => $JobImage->name  ?? null,
					"extension" => $JobImage->extension ?? null
					),
			"download"=>  array(
				"path" => $JobDownload->path ?? null,
				"name" => $JobDownload->panameth ?? null,
				"extension" => $JobDownload->extension ?? null
					),
				"datePosted" => $objJob->datePosted ?? null,
				"validThrough" => $objJob->validThrough?? null,
				"jobLocationType" => $objJob->jobLocationType ?? null,
				"employmentType" => $objJob->employmentType ?? null,
				"Organization_name" => $objJob->Organization_name ?? null,
				"Organization_sameAs" => $objJob->Organization_sameAs ?? null,			
				"Organization_logo" =>  array(
					"path" => $Organization_logo->path  ?? null,
					"name" => $Organization_logo->name  ?? null,
					"extension" => $Organization_logo->extension  ?? null,
					),
			"street" => $objJob->street ?? null,
			"postalCode" => $objJob->postalCode ?? null,
			"Locality" => $objJob->Locality ?? null,
			"Region" => $objJob->Region ?? null,
			"Country" => $objJob->Country ?? null
			

		);

		$this->Template->Job = $Job;
		$this->Template->JobSchema  = $objJob->JobSchema();


	}//end compile

}//end class
